<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LostItem;
use App\Models\FoundItem;
use App\Models\ItemMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display analytics dashboard.
     */
    public function index(Request $request)
    {
        // Time period for filtering
        $period = $request->get('period', 'month'); // day, week, month, year
        
        // Get overall statistics
        $stats = $this->getOverallStatistics();
        
        // Get trends data
        $trends = $this->getTrendsData($period);
        
        // Get user growth
        $userGrowth = $this->getUserGrowth($period);
        
        // Get match success rate
        $successRate = $this->getSuccessRate($period);
        
        // Get category distribution
        $categoryDistribution = $this->getCategoryDistribution();
        
        // Get location hotspots
        $hotspots = $this->getLocationHotspots();
        
        // Get recent activities
        $recentActivities = $this->getRecentActivities();
        
        // Get platform metrics
        $platformMetrics = $this->getPlatformMetrics();
        
        return view('analytics.index', compact(
            'stats',
            'trends',
            'userGrowth',
            'successRate',
            'categoryDistribution',
            'hotspots',
            'recentActivities',
            'platformMetrics',
            'period'
        ));
    }

    /**
     * Get overall statistics.
     */
    private function getOverallStatistics()
    {
        return [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'lost_items' => LostItem::count(),
            'found_items' => FoundItem::count(),
            'total_matches' => ItemMatch::count(),
            'confirmed_matches' => ItemMatch::where('status', 'confirmed')->count(),
            'recovery_rate' => $this->calculateRecoveryRate(),
            'avg_recovery_time' => $this->calculateAvgRecoveryTime(),
        ];
    }

    /**
     * Get trends data for charts.
     */
    private function getTrendsData($period = 'month')
    {
        $startDate = $this->getStartDate($period);
        
        $trends = [
            'labels' => [],
            'lost_items' => [],
            'found_items' => [],
            'matches' => [],
        ];
        
        if ($period === 'day') {
            // Last 30 days
            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $dateStr = $date->format('M j');
                
                $trends['labels'][] = $dateStr;
                $trends['lost_items'][] = LostItem::whereDate('created_at', $date)->count();
                $trends['found_items'][] = FoundItem::whereDate('created_at', $date)->count();
                $trends['matches'][] = ItemMatch::whereDate('created_at', $date)->count();
            }
        } elseif ($period === 'week') {
            // Last 12 weeks
            for ($i = 11; $i >= 0; $i--) {
                $weekStart = now()->subWeeks($i)->startOfWeek();
                $weekEnd = now()->subWeeks($i)->endOfWeek();
                $label = 'Week ' . $weekStart->weekOfYear;
                
                $trends['labels'][] = $label;
                $trends['lost_items'][] = LostItem::whereBetween('created_at', [$weekStart, $weekEnd])->count();
                $trends['found_items'][] = FoundItem::whereBetween('created_at', [$weekStart, $weekEnd])->count();
                $trends['matches'][] = ItemMatch::whereBetween('created_at', [$weekStart, $weekEnd])->count();
            }
        } else {
            // Last 12 months
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $dateStr = $date->format('M Y');
                
                $trends['labels'][] = $dateStr;
                $trends['lost_items'][] = LostItem::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
                $trends['found_items'][] = FoundItem::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
                $trends['matches'][] = ItemMatch::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
            }
        }
        
        return $trends;
    }

    /**
     * Get user growth data.
     */
    private function getUserGrowth($period)
    {
        $startDate = $this->getStartDate($period);
        
        $growth = [
            'labels' => [],
            'total' => [],
            'new' => [],
        ];
        
        if ($period === 'day') {
            // Daily user growth for last 30 days
            $usersByDay = User::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN DATE(created_at) = DATE(created_at) THEN 1 ELSE 0 END) as new')
            )
                ->where('created_at', '>=', $startDate)
                ->groupBy('date')
                ->orderBy('date')
                ->get();
            
            foreach ($usersByDay as $day) {
                $growth['labels'][] = date('M j', strtotime($day->date));
                $growth['total'][] = User::whereDate('created_at', '<=', $day->date)->count();
                $growth['new'][] = $day->new;
            }
        } else {
            // Monthly user growth for last 12 months
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $dateStr = $date->format('M Y');
                
                $totalUsers = User::whereYear('created_at', '<=', $date->year)
                    ->whereMonth('created_at', '<=', $date->month)
                    ->count();
                
                $newUsers = User::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
                
                $growth['labels'][] = $dateStr;
                $growth['total'][] = $totalUsers;
                $growth['new'][] = $newUsers;
            }
        }
        
        return $growth;
    }

    /**
     * Get match success rate.
     */
    private function getSuccessRate($period)
    {
        $startDate = $this->getStartDate($period);
        
        $totalMatches = ItemMatch::where('created_at', '>=', $startDate)->count();
        $confirmedMatches = ItemMatch::where('status', 'confirmed')
            ->where('created_at', '>=', $startDate)
            ->count();
        
        if ($totalMatches > 0) {
            return round(($confirmedMatches / $totalMatches) * 100, 1);
        }
        
        return 0;
    }

    /**
     * Get category distribution.
     */
    private function getCategoryDistribution()
    {
        // Assuming you have a category field in your items
        $lostCategories = LostItem::select('category')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('category')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
            
        $foundCategories = FoundItem::select('category')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('category')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
        
        return [
            'lost' => $lostCategories,
            'found' => $foundCategories,
        ];
    }

    /**
     * Get location hotspots.
     */
    private function getLocationHotspots()
    {
        // Assuming you have location/city field
        $lostHotspots = LostItem::select('location_city')
            ->selectRaw('COUNT(*) as count')
            ->whereNotNull('location_city')
            ->groupBy('location_city')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
            
        $foundHotspots = FoundItem::select('location_city')
            ->selectRaw('COUNT(*) as count')
            ->whereNotNull('location_city')
            ->groupBy('location_city')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
        
        return [
            'lost' => $lostHotspots,
            'found' => $foundHotspots,
        ];
    }

    /**
     * Get recent activities.
     */
    private function getRecentActivities()
    {
        $activities = collect();
        
        // Recent lost items
        $recentLost = LostItem::with('user')
            ->latest()
            ->take(10)
            ->get();
        
        // Recent found items
        $recentFound = FoundItem::with('user')
            ->latest()
            ->take(10)
            ->get();
        
        // Recent matches
        $recentMatches = ItemMatch::with(['lostItem', 'foundItem'])
            ->latest()
            ->take(10)
            ->get();
        
        // Recent users
        $recentUsers = User::latest()
            ->take(10)
            ->get();
        
        return compact('recentLost', 'recentFound', 'recentMatches', 'recentUsers');
    }

    /**
     * Get platform metrics.
     */
    private function getPlatformMetrics()
    {
        return [
            'avg_response_time' => $this->calculateAvgResponseTime(),
            'user_satisfaction' => $this->calculateUserSatisfaction(),
            'peak_hours' => $this->getPeakHours(),
            'device_usage' => $this->getDeviceUsage(),
        ];
    }

    /**
     * Calculate recovery rate.
     */
    private function calculateRecoveryRate()
    {
        $totalLost = LostItem::count();
        $recovered = LostItem::whereHas('matches', function ($query) {
            $query->where('status', 'confirmed');
        })->count();
        
        if ($totalLost > 0) {
            return round(($recovered / $totalLost) * 100, 1);
        }
        
        return 0;
    }

    /**
     * Calculate average recovery time.
     */
    private function calculateAvgRecoveryTime()
    {
        $matches = ItemMatch::where('status', 'confirmed')
            ->whereNotNull('confirmed_at')
            ->get();
        
        if ($matches->isEmpty()) {
            return 0;
        }
        
        $totalHours = 0;
        foreach ($matches as $match) {
            if ($match->lostItem && $match->foundItem) {
                $lostDate = $match->lostItem->created_at;
                $foundDate = $match->foundItem->created_at;
                $hours = $foundDate->diffInHours($lostDate);
                $totalHours += $hours;
            }
        }
        
        return round($totalHours / $matches->count(), 1);
    }

    /**
     * Calculate average response time.
     */
    private function calculateAvgResponseTime()
    {
        // This would depend on your implementation
        // Could be average time between item posting and first match
        return rand(4, 48); // Mock data
    }

    /**
     * Calculate user satisfaction.
     */
    private function calculateUserSatisfaction()
    {
        // This would come from user feedback/ratings
        return rand(80, 98); // Mock data
    }

    /**
     * Get peak usage hours.
     */
    private function getPeakHours()
    {
        // Group by hour of day
        $hourlyData = LostItem::select(
            DB::raw('HOUR(created_at) as hour'),
            DB::raw('COUNT(*) as count')
        )
            ->groupBy('hour')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();
        
        return $hourlyData;
    }

    /**
     * Get device usage statistics.
     */
    private function getDeviceUsage()
    {
        // Mock data - in real app, this would come from user agent analysis
        return [
            'mobile' => 65,
            'desktop' => 30,
            'tablet' => 5,
        ];
    }

    /**
     * Get start date based on period.
     */
    private function getStartDate($period)
    {
        return match ($period) {
            'day' => now()->subDays(30),
            'week' => now()->subWeeks(12),
            'year' => now()->subYears(1),
            default => now()->subMonths(12),
        };
    }

    /**
     * Export analytics data.
     */
    public function export(Request $request)
    {
        $period = $request->get('period', 'month');
        $type = $request->get('type', 'overview');
        
        // Generate CSV or Excel export based on type
        // This is a placeholder - implement based on your needs
        
        return response()->json([
            'message' => 'Export functionality to be implemented',
            'period' => $period,
            'type' => $type,
        ]);
    }
}