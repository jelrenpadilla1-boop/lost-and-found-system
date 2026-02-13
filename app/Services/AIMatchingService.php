<?php

namespace App\Services;

use App\Models\LostItem;
use App\Models\FoundItem;
use App\Models\ItemMatch;
use Illuminate\Support\Facades\Log;

class AIMatchingService
{
    private $nameWeight = 0.3;
    private $descriptionWeight = 0.25;
    private $categoryWeight = 0.2;
    private $locationWeight = 0.15;
    private $dateWeight = 0.1;

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
            $foundItem->longitude
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
    
    private function calculateTextSimilarity(string $text1, string $text2): float
    {
        $maxLength = max(strlen($text1), strlen($text2));
        if ($maxLength === 0) return 0;
        
        $distance = levenshtein($text1, $text2);
        $similarity = 1 - ($distance / $maxLength);
        
        return max(0, $similarity);
    }
    
    private function calculateLocationScore($lat1, $lon1, $lat2, $lon2): float
    {
        if (!$lat1 || !$lon1 || !$lat2 || !$lon2) {
            return 0.5;
        }
        
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
        $distance = $earthRadius * $c;
        
        if ($distance <= 10) return 1.0;
        if ($distance >= 50) return 0;
        
        return 1 - (($distance - 10) / 40);
    }
    
    private function calculateDateScore($dateLost, $dateFound): float
    {
        $daysDiff = abs($dateLost->diffInDays($dateFound));
        
        if ($daysDiff <= 2) return 1.0;
        if ($daysDiff >= 30) return 0;
        
        return 1 - (($daysDiff - 2) / 28);
    }
    
    /**
     * Find matches for a lost item
     */
    public function findMatchesForLostItem(LostItem $lostItem): array
    {
        $matches = [];
        
        $foundItems = FoundItem::where('category', $lostItem->category)
            ->where('status', 'pending')
            ->whereDate('date_found', '>=', $lostItem->date_lost->subDays(30))
            ->get();
        
        foreach ($foundItems as $foundItem) {
            $score = $this->calculateMatchScore($lostItem, $foundItem);
            
            if ($score >= 60) {
                $matches[] = [
                    'lost_item' => $lostItem,
                    'found_item' => $foundItem,
                    'score' => $score
                ];
                
                ItemMatch::updateOrCreate(
                    [
                        'lost_item_id' => $lostItem->id,
                        'found_item_id' => $foundItem->id
                    ],
                    [
                        'match_score' => $score,
                        'status' => 'pending'
                    ]
                );
            }
        }
        
        return $matches;
    }
    
    /**
     * Find matches for a found item
     */
    public function findMatchesForFoundItem(FoundItem $foundItem): array
    {
        $matches = [];
        
        $lostItems = LostItem::where('category', $foundItem->category)
            ->where('status', 'pending')
            ->whereDate('date_lost', '<=', $foundItem->date_found->addDays(30))
            ->get();
        
        foreach ($lostItems as $lostItem) {
            $score = $this->calculateMatchScore($lostItem, $foundItem);
            
            if ($score >= 60) {
                $matches[] = [
                    'lost_item' => $lostItem,
                    'found_item' => $foundItem,
                    'score' => $score
                ];
                
                ItemMatch::updateOrCreate(
                    [
                        'lost_item_id' => $lostItem->id,
                        'found_item_id' => $foundItem->id
                    ],
                    [
                        'match_score' => $score,
                        'status' => 'pending'
                    ]
                );
            }
        }
        
        return $matches;
    }
}