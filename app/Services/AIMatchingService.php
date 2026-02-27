<?php

namespace App\Services;

use App\Models\LostItem;
use App\Models\FoundItem;
use App\Models\ItemMatch;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AIMatchingService
{
    private $nameWeight = 0.3;
    private $descriptionWeight = 0.25;
    private $categoryWeight = 0.2;
    private $locationWeight = 0.15;
    private $dateWeight = 0.1;
    private $minMatchScore = 60; // Minimum score to create a match

    /**
     * Calculate match score between lost and found items
     */
    public function calculateMatchScore(LostItem $lostItem, FoundItem $foundItem): float
    {
        $score = 0;
        
        // 1. Item Name Similarity (30%)
        $nameScore = $this->calculateTextSimilarity(
            strtolower($lostItem->item_name),
            strtolower($foundItem->item_name)
        );
        $score += $nameScore * $this->nameWeight;
        
        // 2. Description Similarity (25%)
        $descriptionScore = $this->calculateTextSimilarity(
            strtolower($lostItem->description),
            strtolower($foundItem->description)
        );
        $score += $descriptionScore * $this->descriptionWeight;
        
        // 3. Category Match (20%)
        $categoryScore = strtolower($lostItem->category) === strtolower($foundItem->category) ? 1 : 0;
        $score += $categoryScore * $this->categoryWeight;
        
        // 4. Location Proximity (15%)
        $locationScore = $this->calculateLocationScore(
            $lostItem->latitude,
            $lostItem->longitude,
            $foundItem->latitude,
            $foundItem->longitude,
            $lostItem->lost_location,
            $foundItem->found_location
        );
        $score += $locationScore * $this->locationWeight;
        
        // 5. Date Proximity (10%)
        $dateScore = $this->calculateDateScore(
            $lostItem->date_lost,
            $foundItem->date_found
        );
        $score += $dateScore * $this->dateWeight;
        
        // Normalize to percentage
        return round($score * 100, 2);
    }
    
    /**
     * Calculate text similarity using multiple algorithms
     */
    private function calculateTextSimilarity(string $text1, string $text2): float
    {
        $maxLength = max(strlen($text1), strlen($text2));
        if ($maxLength === 0) return 0;
        
        // Try exact match first
        if ($text1 === $text2) return 1.0;
        
        // Try contains match
        if (str_contains($text1, $text2) || str_contains($text2, $text1)) {
            $minLength = min(strlen($text1), strlen($text2));
            return round($minLength / $maxLength, 2);
        }
        
        // Use Levenshtein distance for fuzzy matching
        $distance = levenshtein($text1, $text2);
        $similarity = 1 - ($distance / $maxLength);
        
        return max(0, min(1, $similarity));
    }
    
    /**
     * Calculate location score using coordinates and location names
     */
    private function calculateLocationScore($lat1, $lon1, $lat2, $lon2, $loc1 = null, $loc2 = null): float
    {
        $score = 0.5; // Default medium score
        
        // If both coordinates are available, use precise calculation
        if ($lat1 && $lon1 && $lat2 && $lon2) {
            $distance = $this->calculateDistance($lat1, $lon1, $lat2, $lon2);
            
            if ($distance <= 1) return 1.0; // Within 1km
            if ($distance <= 5) return 0.9;  // Within 5km
            if ($distance <= 10) return 0.8; // Within 10km
            if ($distance <= 20) return 0.6; // Within 20km
            if ($distance <= 50) return 0.4; // Within 50km
            return 0.2; // > 50km
        }
        
        // If only location names are available, check for similarity
        if ($loc1 && $loc2) {
            $locationSimilarity = $this->calculateTextSimilarity(
                strtolower($loc1),
                strtolower($loc2)
            );
            return $locationSimilarity * 0.8; // Slightly lower confidence for text-based matching
        }
        
        return $score;
    }
    
    /**
     * Calculate distance between two coordinates in kilometers
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371;
        
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);
        
        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;
        
        $a = sin($dlat/2) * sin($dlat/2) + 
             cos($lat1) * cos($lat2) * 
             sin($dlon/2) * sin($dlon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $earthRadius * $c;
    }
    
    /**
     * Calculate date proximity score
     */
    private function calculateDateScore($dateLost, $dateFound): float
    {
        $daysDiff = abs($dateLost->diffInDays($dateFound));
        
        if ($daysDiff <= 1) return 1.0;      // Same or next day
        if ($daysDiff <= 3) return 0.9;      // Within 3 days
        if ($daysDiff <= 7) return 0.8;      // Within a week
        if ($daysDiff <= 14) return 0.6;     // Within 2 weeks
        if ($daysDiff <= 30) return 0.4;     // Within a month
        if ($daysDiff <= 60) return 0.2;     // Within 2 months
        
        return 0.1; // Older than 2 months
    }
    
    /**
     * Find matches for a lost item
     */
    public function findMatchesForLostItem(LostItem $lostItem): array
    {
        $matches = [];
        
        try {
            // Only find matches for approved items or if specifically requested
            if ($lostItem->status !== 'approved' && $lostItem->status !== 'pending') {
                return $matches;
            }
            
            $query = FoundItem::where('category', $lostItem->category)
                ->whereIn('status', ['approved', 'pending']);
            
            // Add date range filter (items found within 60 days of loss)
            $query->whereDate('date_found', '>=', $lostItem->date_lost->copy()->subDays(60))
                  ->whereDate('date_found', '<=', $lostItem->date_lost->copy()->addDays(60));
            
            $foundItems = $query->get();
            
            foreach ($foundItems as $foundItem) {
                // Skip if it's the same user (optional)
                if ($foundItem->user_id === $lostItem->user_id) {
                    continue;
                }
                
                $score = $this->calculateMatchScore($lostItem, $foundItem);
                
                if ($score >= $this->minMatchScore) {
                    $match = ItemMatch::updateOrCreate(
                        [
                            'lost_item_id' => $lostItem->id,
                            'found_item_id' => $foundItem->id
                        ],
                        [
                            'match_score' => $score,
                            'status' => 'pending'
                        ]
                    );
                    
                    $matches[] = [
                        'match' => $match,
                        'lost_item' => $lostItem,
                        'found_item' => $foundItem,
                        'score' => $score
                    ];
                    
                    Log::info('Match created', [
                        'lost_item_id' => $lostItem->id,
                        'found_item_id' => $foundItem->id,
                        'score' => $score
                    ]);
                }
            }
            
            // Sort matches by score descending
            usort($matches, function($a, $b) {
                return $b['score'] <=> $a['score'];
            });
            
        } catch (\Exception $e) {
            Log::error('Error finding matches for lost item: ' . $e->getMessage(), [
                'lost_item_id' => $lostItem->id,
                'exception' => $e
            ]);
        }
        
        return $matches;
    }
    
    /**
     * Find matches for a found item
     */
    public function findMatchesForFoundItem(FoundItem $foundItem): array
    {
        $matches = [];
        
        try {
            // Only find matches for approved items or if specifically requested
            if ($foundItem->status !== 'approved' && $foundItem->status !== 'pending') {
                return $matches;
            }
            
            $query = LostItem::where('category', $foundItem->category)
                ->whereIn('status', ['approved', 'pending']);
            
            // Add date range filter (items lost within 60 days of found date)
            $query->whereDate('date_lost', '>=', $foundItem->date_found->copy()->subDays(60))
                  ->whereDate('date_lost', '<=', $foundItem->date_found->copy()->addDays(60));
            
            $lostItems = $query->get();
            
            foreach ($lostItems as $lostItem) {
                // Skip if it's the same user (optional)
                if ($lostItem->user_id === $foundItem->user_id) {
                    continue;
                }
                
                $score = $this->calculateMatchScore($lostItem, $foundItem);
                
                if ($score >= $this->minMatchScore) {
                    $match = ItemMatch::updateOrCreate(
                        [
                            'lost_item_id' => $lostItem->id,
                            'found_item_id' => $foundItem->id
                        ],
                        [
                            'match_score' => $score,
                            'status' => 'pending'
                        ]
                    );
                    
                    $matches[] = [
                        'match' => $match,
                        'lost_item' => $lostItem,
                        'found_item' => $foundItem,
                        'score' => $score
                    ];
                    
                    Log::info('Match created', [
                        'lost_item_id' => $lostItem->id,
                        'found_item_id' => $foundItem->id,
                        'score' => $score
                    ]);
                }
            }
            
            // Sort matches by score descending
            usort($matches, function($a, $b) {
                return $b['score'] <=> $a['score'];
            });
            
        } catch (\Exception $e) {
            Log::error('Error finding matches for found item: ' . $e->getMessage(), [
                'found_item_id' => $foundItem->id,
                'exception' => $e
            ]);
        }
        
        return $matches;
    }
    
    /**
     * Find matches for both lost and found items (batch processing)
     */
    public function findMatchesForAll(): array
    {
        $stats = [
            'lost_items_processed' => 0,
            'found_items_processed' => 0,
            'matches_created' => 0
        ];
        
        try {
            DB::beginTransaction();
            
            // Process approved lost items
            $lostItems = LostItem::where('status', 'approved')->get();
            foreach ($lostItems as $lostItem) {
                $matches = $this->findMatchesForLostItem($lostItem);
                $stats['lost_items_processed']++;
                $stats['matches_created'] += count($matches);
            }
            
            // Process approved found items
            $foundItems = FoundItem::where('status', 'approved')->get();
            foreach ($foundItems as $foundItem) {
                $matches = $this->findMatchesForFoundItem($foundItem);
                $stats['found_items_processed']++;
                $stats['matches_created'] += count($matches);
            }
            
            DB::commit();
            
            Log::info('Batch matching completed', $stats);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in batch matching: ' . $e->getMessage(), [
                'exception' => $e
            ]);
        }
        
        return $stats;
    }
    
    /**
     * Get match statistics
     */
    public function getMatchStats(): array
    {
        return [
            'total_matches' => ItemMatch::count(),
            'pending_matches' => ItemMatch::where('status', 'pending')->count(),
            'confirmed_matches' => ItemMatch::where('status', 'confirmed')->count(),
            'rejected_matches' => ItemMatch::where('status', 'rejected')->count(),
            'high_confidence' => ItemMatch::where('match_score', '>=', 80)->count(),
            'medium_confidence' => ItemMatch::whereBetween('match_score', [60, 79])->count(),
            'low_confidence' => ItemMatch::where('match_score', '<', 60)->count(),
        ];
    }
}