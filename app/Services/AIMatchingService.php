<?php

namespace App\Services;

use App\Models\LostItem;
use App\Models\FoundItem;
use App\Models\ItemMatch;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AIMatchingService
{
    // Base weights (sum to 1.0)
    private array $weights = [
        'name'        => 0.30,
        'description' => 0.25,
        'category'    => 0.20,
        'location'    => 0.15,
        'date'        => 0.10,
    ];

    private int $minMatchScore = 50;

    private array $colorKeywords = [
        'red', 'blue', 'green', 'yellow', 'black', 'white', 'brown', 'gray', 'grey',
        'pink', 'purple', 'orange', 'gold', 'silver', 'beige', 'navy', 'cyan', 'maroon',
        'violet', 'turquoise', 'cream', 'tan', 'khaki', 'dark', 'light',
    ];

    private array $stopwords = [
        'a', 'an', 'the', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for',
        'of', 'with', 'by', 'from', 'is', 'it', 'its', 'was', 'are', 'be',
        'this', 'that', 'my', 'i', 'have', 'had', 'has', 'some', 'very',
        'when', 'lost', 'found', 'item', 'last', 'saw', 'please', 'near',
    ];

    private array $synonymGroups = [
        ['phone', 'mobile', 'cellphone', 'smartphone', 'iphone', 'android', 'handphone'],
        ['wallet', 'purse', 'billfold', 'cardholder', 'card holder', 'card case'],
        ['bag', 'backpack', 'handbag', 'satchel', 'tote', 'pouch', 'sack', 'knapsack'],
        ['laptop', 'computer', 'notebook', 'macbook', 'chromebook'],
        ['watch', 'timepiece', 'wristwatch', 'smartwatch'],
        ['glasses', 'spectacles', 'eyeglasses', 'sunglasses', 'shades', 'goggles'],
        ['keys', 'key', 'keychain', 'keyring'],
        ['earphones', 'earbuds', 'headphones', 'airpods', 'headset', 'earpiece'],
        ['umbrella', 'brolly', 'parasol'],
        ['id', 'identification', 'id card', 'passport', 'license'],
        ['charger', 'adapter', 'cable', 'cord'],
        ['book', 'notebook', 'journal', 'diary', 'planner'],
    ];

    /**
     * Calculate match score between a lost item and a found item.
     */
    public function calculateMatchScore(LostItem $lostItem, FoundItem $foundItem): float
    {
        $weights = $this->weights;

        // Redistribute location weight when no location data exists at all
        $hasLocation = ($lostItem->latitude && $lostItem->longitude) ||
                       ($foundItem->latitude && $foundItem->longitude) ||
                       $lostItem->lost_location || $foundItem->found_location;
        if (!$hasLocation) {
            $extra = $weights['location'];
            $weights['location'] = 0;
            $weights['name']        += $extra * 0.5;
            $weights['description'] += $extra * 0.5;
        }

        // 1. Item Name Similarity (30%)
        $nameScore = $this->calculateNameSimilarity(
            strtolower($lostItem->item_name),
            strtolower($foundItem->item_name)
        );

        // 2. Description Similarity (25%) — word-token based, not character-based
        $descScore = $this->calculateDescriptionSimilarity(
            strtolower($lostItem->description ?? ''),
            strtolower($foundItem->description ?? '')
        );

        // 3. Category Match (20%)
        $categoryScore = strtolower($lostItem->category) === strtolower($foundItem->category) ? 1.0 : 0.0;

        // 4. Location Proximity (15%)
        $locationScore = $this->calculateLocationScore(
            $lostItem->latitude, $lostItem->longitude,
            $foundItem->latitude, $foundItem->longitude,
            $lostItem->lost_location, $foundItem->found_location
        );

        // 5. Date Proximity with direction bias (10%)
        $dateScore = $this->calculateDateScore($lostItem->date_lost, $foundItem->date_found);

        $score = ($nameScore        * $weights['name'])
               + ($descScore        * $weights['description'])
               + ($categoryScore    * $weights['category'])
               + ($locationScore    * $weights['location'])
               + ($dateScore        * $weights['date']);

        // Bonus/penalty: color match between combined name+description
        $colorBonus = $this->calculateColorBonus(
            $lostItem->item_name . ' ' . ($lostItem->description ?? ''),
            $foundItem->item_name . ' ' . ($foundItem->description ?? '')
        );

        return min(100, max(0, round($score * 100 + $colorBonus, 2)));
    }

    /**
     * Name similarity: Jaccard on tokens + synonym detection + Levenshtein blend.
     */
    private function calculateNameSimilarity(string $a, string $b): float
    {
        if ($a === $b) return 1.0;
        if (!$a || !$b) return 0.0;

        if ($this->areSynonyms($a, $b)) return 0.85;

        $wordsA = $this->tokenize($a);
        $wordsB = $this->tokenize($b);
        $jaccard = $this->jaccardSimilarity($wordsA, $wordsB);

        $maxLen = max(strlen($a), strlen($b));
        $leven  = max(0, 1 - levenshtein($a, $b) / $maxLen);

        // Jaccard is more reliable for names; Levenshtein handles typos/abbreviations
        return round($jaccard * 0.6 + $leven * 0.4, 4);
    }

    /**
     * Description similarity: Jaccard + Dice on word tokens.
     * Levenshtein is intentionally NOT used here — it is too noisy on long text.
     */
    private function calculateDescriptionSimilarity(string $a, string $b): float
    {
        if (!$a && !$b) return 0.5; // Both empty — neutral, don't penalise
        if (!$a || !$b) return 0.1;

        $wordsA = $this->tokenize($a);
        $wordsB = $this->tokenize($b);

        if (empty($wordsA) || empty($wordsB)) return 0.0;

        $jaccard = $this->jaccardSimilarity($wordsA, $wordsB);
        $dice    = $this->diceSimilarity($wordsA, $wordsB);

        return round(($jaccard + $dice) / 2, 4);
    }

    /**
     * Color bonus: +5 if matching colors detected, -3 if conflicting colors detected.
     */
    private function calculateColorBonus(string $textA, string $textB): float
    {
        $colorsA = $this->extractColors($textA);
        $colorsB = $this->extractColors($textB);

        if (empty($colorsA) || empty($colorsB)) return 0.0;

        if (!empty(array_intersect($colorsA, $colorsB))) return 5.0;

        return -3.0; // Both have colors but they don't match
    }

    private function extractColors(string $text): array
    {
        $text  = strtolower($text);
        $found = [];
        foreach ($this->colorKeywords as $color) {
            if (str_contains($text, $color)) {
                $found[] = $color;
            }
        }
        return $found;
    }

    /**
     * Location score using GPS coordinates when available, falling back to text.
     */
    private function calculateLocationScore($lat1, $lon1, $lat2, $lon2, $loc1 = null, $loc2 = null): float
    {
        if ($lat1 && $lon1 && $lat2 && $lon2) {
            $km = $this->calculateDistance($lat1, $lon1, $lat2, $lon2);
            if ($km <= 0.5) return 1.00;
            if ($km <= 1)   return 0.95;
            if ($km <= 5)   return 0.85;
            if ($km <= 10)  return 0.70;
            if ($km <= 20)  return 0.50;
            if ($km <= 50)  return 0.30;
            return 0.10;
        }

        if ($loc1 && $loc2) {
            // Use name similarity on location strings, capped at 0.75 (lower confidence)
            return round($this->calculateNameSimilarity(strtolower($loc1), strtolower($loc2)) * 0.75, 4);
        }

        return 0.35; // Unknown — below neutral, don't inflate score
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $r    = 6371;
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $a = sin(($lat2 - $lat1) / 2) ** 2
           + cos($lat1) * cos($lat2) * sin(($lon2 - $lon1) / 2) ** 2;

        return $r * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    /**
     * Date score with direction bias: item found AFTER it was lost scores higher.
     */
    private function calculateDateScore($dateLost, $dateFound): float
    {
        // Signed diff: positive = found after lost (expected), negative = found before lost (unusual)
        $days = $dateLost->diffInDays($dateFound, false);

        if ($days < 0) {
            // Found before lost date — possible (late reporting), but penalise
            $abs = abs($days);
            if ($abs <= 3)  return 0.70;
            if ($abs <= 7)  return 0.50;
            if ($abs <= 14) return 0.30;
            return 0.10;
        }

        if ($days <= 1)  return 1.00;
        if ($days <= 3)  return 0.90;
        if ($days <= 7)  return 0.80;
        if ($days <= 14) return 0.65;
        if ($days <= 30) return 0.45;
        if ($days <= 60) return 0.25;
        if ($days <= 90) return 0.15;
        return 0.05;
    }

    // -----------------------------------------------------------------------
    // Token / similarity helpers
    // -----------------------------------------------------------------------

    /**
     * Lowercase, strip punctuation, split into words, remove stopwords, deduplicate.
     */
    private function tokenize(string $text): array
    {
        $text    = preg_replace('/[^a-z0-9\s]/', ' ', strtolower($text));
        $words   = preg_split('/\s+/', trim($text), -1, PREG_SPLIT_NO_EMPTY);
        $filtered = array_diff($words, $this->stopwords);
        return array_values(array_unique($filtered));
    }

    /** Jaccard: |A ∩ B| / |A ∪ B| */
    private function jaccardSimilarity(array $a, array $b): float
    {
        if (empty($a) && empty($b)) return 1.0;
        $intersection = count(array_intersect($a, $b));
        $union        = count(array_unique(array_merge($a, $b)));
        return $union > 0 ? round($intersection / $union, 4) : 0.0;
    }

    /** Dice: 2|A ∩ B| / (|A| + |B|) */
    private function diceSimilarity(array $a, array $b): float
    {
        if (empty($a) && empty($b)) return 1.0;
        $intersection = count(array_intersect($a, $b));
        $total        = count($a) + count($b);
        return $total > 0 ? round((2 * $intersection) / $total, 4) : 0.0;
    }

    /**
     * Returns true if either string contains a word from the same synonym group.
     */
    private function areSynonyms(string $a, string $b): bool
    {
        foreach ($this->synonymGroups as $group) {
            $inA = false;
            $inB = false;
            foreach ($group as $synonym) {
                if (str_contains($a, $synonym)) $inA = true;
                if (str_contains($b, $synonym)) $inB = true;
            }
            if ($inA && $inB) return true;
        }
        return false;
    }

    // -----------------------------------------------------------------------
    // Match finders
    // -----------------------------------------------------------------------

    /**
     * Find matching found items for a given lost item.
     */
    public function findMatchesForLostItem(LostItem $lostItem): array
    {
        $matches = [];
        if (!in_array($lostItem->status, ['approved', 'pending'])) return $matches;

        try {
            // Allow a small window before lost date (item found, owner reports later)
            $foundItems = FoundItem::whereIn('status', ['approved', 'pending'])
                ->where('user_id', '!=', $lostItem->user_id)
                ->whereDate('date_found', '>=', $lostItem->date_lost->copy()->subDays(7))
                ->whereDate('date_found', '<=', $lostItem->date_lost->copy()->addDays(90))
                ->get();

            foreach ($foundItems as $foundItem) {
                $score = $this->calculateMatchScore($lostItem, $foundItem);
                if ($score >= $this->minMatchScore) {
                    $wasNew = !ItemMatch::where('lost_item_id', $lostItem->id)
                        ->where('found_item_id', $foundItem->id)
                        ->exists();

                    $match = ItemMatch::updateOrCreate(
                        ['lost_item_id' => $lostItem->id, 'found_item_id' => $foundItem->id],
                        ['match_score' => $score, 'status' => 'pending']
                    );

                    if ($wasNew) {
                        $this->notifyMatchFound($lostItem->user_id, $foundItem->user_id, $lostItem->item_name, $foundItem->item_name, $score);
                    }

                    $matches[] = [
                        'match'      => $match,
                        'lost_item'  => $lostItem,
                        'found_item' => $foundItem,
                        'score'      => $score,
                    ];
                    Log::info('Match created/updated', [
                        'lost_item_id'  => $lostItem->id,
                        'found_item_id' => $foundItem->id,
                        'score'         => $score,
                    ]);
                }
            }

            usort($matches, fn($a, $b) => $b['score'] <=> $a['score']);
        } catch (\Exception $e) {
            Log::error('Error finding matches for lost item: ' . $e->getMessage(), [
                'lost_item_id' => $lostItem->id,
            ]);
        }

        return $matches;
    }

    /**
     * Find matching lost items for a given found item.
     */
    public function findMatchesForFoundItem(FoundItem $foundItem): array
    {
        $matches = [];
        if (!in_array($foundItem->status, ['approved', 'pending'])) return $matches;

        try {
            $lostItems = LostItem::whereIn('status', ['approved', 'pending'])
                ->where('user_id', '!=', $foundItem->user_id)
                ->whereDate('date_lost', '>=', $foundItem->date_found->copy()->subDays(90))
                ->whereDate('date_lost', '<=', $foundItem->date_found->copy()->addDays(7))
                ->get();

            foreach ($lostItems as $lostItem) {
                $score = $this->calculateMatchScore($lostItem, $foundItem);
                if ($score >= $this->minMatchScore) {
                    $wasNew = !ItemMatch::where('lost_item_id', $lostItem->id)
                        ->where('found_item_id', $foundItem->id)
                        ->exists();

                    $match = ItemMatch::updateOrCreate(
                        ['lost_item_id' => $lostItem->id, 'found_item_id' => $foundItem->id],
                        ['match_score' => $score, 'status' => 'pending']
                    );

                    if ($wasNew) {
                        $this->notifyMatchFound($lostItem->user_id, $foundItem->user_id, $lostItem->item_name, $foundItem->item_name, $score);
                    }

                    $matches[] = [
                        'match'      => $match,
                        'lost_item'  => $lostItem,
                        'found_item' => $foundItem,
                        'score'      => $score,
                    ];
                    Log::info('Match created/updated', [
                        'lost_item_id'  => $lostItem->id,
                        'found_item_id' => $foundItem->id,
                        'score'         => $score,
                    ]);
                }
            }

            usort($matches, fn($a, $b) => $b['score'] <=> $a['score']);
        } catch (\Exception $e) {
            Log::error('Error finding matches for found item: ' . $e->getMessage(), [
                'found_item_id' => $foundItem->id,
            ]);
        }

        return $matches;
    }

    /**
     * Batch process all items. Runs only from the lost-item side to avoid
     * creating duplicate matches and double-counting stats.
     */
    public function findMatchesForAll(): array
    {
        $stats = [
            'lost_items_processed'  => 0,
            'found_items_processed' => 0,
            'matches_created'       => 0,
        ];

        try {
            DB::beginTransaction();

            $lostItems = LostItem::whereIn('status', ['approved', 'pending'])->get();
            foreach ($lostItems as $lostItem) {
                $matches = $this->findMatchesForLostItem($lostItem);
                $stats['lost_items_processed']++;
                $stats['matches_created'] += count($matches);
            }

            DB::commit();
            Log::info('Batch matching completed', $stats);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in batch matching: ' . $e->getMessage());
        }

        return $stats;
    }

    /**
     * Get match statistics.
     */
    public function getMatchStats(): array
    {
        return [
            'total_matches'     => ItemMatch::count(),
            'pending_matches'   => ItemMatch::where('status', 'pending')->count(),
            'confirmed_matches' => ItemMatch::where('status', 'confirmed')->count(),
            'rejected_matches'  => ItemMatch::where('status', 'rejected')->count(),
            'high_confidence'   => ItemMatch::where('match_score', '>=', 80)->count(),
            'medium_confidence' => ItemMatch::whereBetween('match_score', [50, 79])->count(),
            'low_confidence'    => ItemMatch::where('match_score', '<', 50)->count(),
        ];
    }

    /**
     * Notify both parties when a new match is found.
     */
    private function notifyMatchFound(int $lostOwnerId, int $foundOwnerId, string $lostItemName, string $foundItemName, float $score): void
    {
        try {
            $matchesUrl = route('matches.my-matches');
            $confidence = $score >= 80 ? 'high' : ($score >= 50 ? 'medium' : 'low');

            // Notify the person who lost the item
            Notification::create([
                'user_id' => $lostOwnerId,
                'type'    => 'match',
                'title'   => '🔗 Potential Match Found!',
                'body'    => "We found a potential match for your lost \"{$lostItemName}\" ({$confidence} confidence, {$score}% score).",
                'url'     => $matchesUrl,
                'data'    => json_encode(['icon' => 'exchange-alt', 'color' => '#00f0c8']),
                'is_read' => false,
            ]);

            // Notify the person who found the item
            Notification::create([
                'user_id' => $foundOwnerId,
                'type'    => 'match',
                'title'   => '🔗 Potential Match Found!',
                'body'    => "Your found item \"{$foundItemName}\" may match someone's lost item \"{$lostItemName}\".",
                'url'     => $matchesUrl,
                'data'    => json_encode(['icon' => 'exchange-alt', 'color' => '#00f0c8']),
                'is_read' => false,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create match notifications: ' . $e->getMessage());
        }
    }
}
