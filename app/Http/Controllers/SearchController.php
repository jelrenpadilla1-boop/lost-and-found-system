<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LostItem;
use App\Models\FoundItem;
use App\Models\Match;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    /**
     * User search - search within user's own items and matches
     */
    public function userSearch(Request $request)
    {
        $query = $request->get('query');
        $filters = $request->get('filter', []);
        $status = $request->get('status');
        $itemType = $request->get('item_type');
        $dateRange = $request->get('date_range');
        
        $results = [];
        $userId = Auth::id();
        
        // Search in user's lost items
        if (empty($filters) || in_array('my_lost', $filters)) {
            $lostQuery = LostItem::where('user_id', $userId);
            
            if ($query) {
                $lostQuery->where(function($q) use ($query) {
                    $q->where('item_name', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%")
                      ->orWhere('location', 'LIKE', "%{$query}%");
                });
            }
            
            if ($status) {
                $lostQuery->where('status', $status);
            }
            
            if ($dateRange) {
                $lostQuery = $this->applyDateFilter($lostQuery, $dateRange);
            }
            
            $lostItems = $lostQuery->latest()->get();
            
            foreach ($lostItems as $item) {
                $results[] = [
                    'id' => $item->id,
                    'type' => 'lost',
                    'title' => $item->item_name,
                    'subtitle' => $item->description,
                    'status' => $item->status,
                    'date' => $item->created_at->format('M d, Y'),
                    'url' => route('lost-items.show', $item),
                    'icon' => 'fa-exclamation-circle'
                ];
            }
        }
        
        // Search in user's found items
        if (empty($filters) || in_array('my_found', $filters)) {
            $foundQuery = FoundItem::where('user_id', $userId);
            
            if ($query) {
                $foundQuery->where(function($q) use ($query) {
                    $q->where('item_name', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%")
                      ->orWhere('location', 'LIKE', "%{$query}%");
                });
            }
            
            if ($status) {
                $foundQuery->where('status', $status);
            }
            
            if ($dateRange) {
                $foundQuery = $this->applyDateFilter($foundQuery, $dateRange);
            }
            
            $foundItems = $foundQuery->latest()->get();
            
            foreach ($foundItems as $item) {
                $results[] = [
                    'id' => $item->id,
                    'type' => 'found',
                    'title' => $item->item_name,
                    'subtitle' => $item->description,
                    'status' => $item->status,
                    'date' => $item->created_at->format('M d, Y'),
                    'url' => route('found-items.show', $item),
                    'icon' => 'fa-check-circle'
                ];
            }
        }
        
        // Search in user's matches
        if (empty($filters) || in_array('my_matches', $filters)) {
            $matchQuery = ItemMatch::where(function($q) use ($userId) {
                $q->whereHas('lostItem', function($q) use ($userId) {
                    $q->where('user_id', $userId);
                })->orWhereHas('foundItem', function($q) use ($userId) {
                    $q->where('user_id', $userId);
                });
            });
            
            if ($query) {
                $matchQuery->where(function($q) use ($query) {
                    $q->whereHas('lostItem', function($q) use ($query) {
                        $q->where('item_name', 'LIKE', "%{$query}%");
                    })->orWhereHas('foundItem', function($q) use ($query) {
                        $q->where('item_name', 'LIKE', "%{$query}%");
                    });
                });
            }
            
            if ($status) {
                $matchQuery->where('status', $status);
            }
            
            if ($dateRange) {
                $matchQuery = $this->applyDateFilter($matchQuery, $dateRange, 'created_at');
            }
            
            $matches = $matchQuery->latest()->get();
            
            foreach ($matches as $match) {
                $itemName = $match->lostItem ? $match->lostItem->item_name : ($match->foundItem ? $match->foundItem->item_name : 'Unknown');
                $results[] = [
                    'id' => $match->id,
                    'type' => 'match',
                    'title' => 'Match: ' . $itemName,
                    'subtitle' => 'Score: ' . $match->match_score . '%',
                    'status' => $match->status,
                    'date' => $match->created_at->format('M d, Y'),
                    'url' => route('matches.show', $match),
                    'icon' => 'fa-exchange-alt'
                ];
            }
        }
        
        // Sort results by date
        usort($results, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        return view('search.results', compact('results', 'query'));
    }
    
    /**
     * Admin search - search across all items, users, and matches
     */
    public function adminSearch(Request $request)
    {
        $query = $request->get('query');
        $filters = $request->get('filter', []);
        $status = $request->get('status');
        $dateRange = $request->get('date_range');
        
        $results = [];
        
        // Search in lost items
        if (empty($filters) || in_array('lost_items', $filters)) {
            $lostQuery = LostItem::with('user');
            
            if ($query) {
                $lostQuery->where(function($q) use ($query) {
                    $q->where('item_name', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%")
                      ->orWhere('location', 'LIKE', "%{$query}%")
                      ->orWhereHas('user', function($q) use ($query) {
                          $q->where('name', 'LIKE', "%{$query}%");
                      });
                });
            }
            
            if ($status) {
                $lostQuery->where('status', $status);
            }
            
            if ($dateRange) {
                $lostQuery = $this->applyDateFilter($lostQuery, $dateRange);
            }
            
            $lostItems = $lostQuery->latest()->limit(10)->get();
            
            foreach ($lostItems as $item) {
                $results[] = [
                    'id' => $item->id,
                    'type' => 'lost',
                    'title' => $item->item_name,
                    'subtitle' => 'Reported by: ' . ($item->user->name ?? 'Unknown'),
                    'status' => $item->status,
                    'date' => $item->created_at->format('M d, Y'),
                    'url' => route('lost-items.show', $item),
                    'icon' => 'fa-exclamation-circle'
                ];
            }
        }
        
        // Search in found items
        if (empty($filters) || in_array('found_items', $filters)) {
            $foundQuery = FoundItem::with('user');
            
            if ($query) {
                $foundQuery->where(function($q) use ($query) {
                    $q->where('item_name', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%")
                      ->orWhere('location', 'LIKE', "%{$query}%")
                      ->orWhereHas('user', function($q) use ($query) {
                          $q->where('name', 'LIKE', "%{$query}%");
                      });
                });
            }
            
            if ($status) {
                $foundQuery->where('status', $status);
            }
            
            if ($dateRange) {
                $foundQuery = $this->applyDateFilter($foundQuery, $dateRange);
            }
            
            $foundItems = $foundQuery->latest()->limit(10)->get();
            
            foreach ($foundItems as $item) {
                $results[] = [
                    'id' => $item->id,
                    'type' => 'found',
                    'title' => $item->item_name,
                    'subtitle' => 'Reported by: ' . ($item->user->name ?? 'Unknown'),
                    'status' => $item->status,
                    'date' => $item->created_at->format('M d, Y'),
                    'url' => route('found-items.show', $item),
                    'icon' => 'fa-check-circle'
                ];
            }
        }
        
        // Search in users
        if (empty($filters) || in_array('users', $filters)) {
            $userQuery = User::query();
            
            if ($query) {
                $userQuery->where(function($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('email', 'LIKE', "%{$query}%");
                });
            }
            
            $users = $userQuery->latest()->limit(10)->get();
            
            foreach ($users as $user) {
                $results[] = [
                    'id' => $user->id,
                    'type' => 'user',
                    'title' => $user->name,
                    'subtitle' => $user->email,
                    'status' => $user->isAdmin() ? 'Admin' : 'User',
                    'date' => $user->created_at->format('M d, Y'),
                    'url' => route('admin.users.show', $user),
                    'icon' => 'fa-user'
                ];
            }
        }
        
        // Search in matches
        if (empty($filters) || in_array('matches', $filters)) {
            $matchQuery = ItemMatch::with(['lostItem', 'foundItem']);
            
            if ($query) {
                $matchQuery->where(function($q) use ($query) {
                    $q->whereHas('lostItem', function($q) use ($query) {
                        $q->where('item_name', 'LIKE', "%{$query}%");
                    })->orWhereHas('foundItem', function($q) use ($query) {
                        $q->where('item_name', 'LIKE', "%{$query}%");
                    });
                });
            }
            
            if ($status) {
                $matchQuery->where('status', $status);
            }
            
            if ($dateRange) {
                $matchQuery = $this->applyDateFilter($matchQuery, $dateRange, 'created_at');
            }
            
            $matches = $matchQuery->latest()->limit(10)->get();
            
            foreach ($matches as $match) {
                $lostName = $match->lostItem ? $match->lostItem->item_name : 'Unknown';
                $foundName = $match->foundItem ? $match->foundItem->item_name : 'Unknown';
                $results[] = [
                    'id' => $match->id,
                    'type' => 'match',
                    'title' => "Match: {$lostName} ↔ {$foundName}",
                    'subtitle' => 'Score: ' . $match->match_score . '%',
                    'status' => $match->status,
                    'date' => $match->created_at->format('M d, Y'),
                    'url' => route('matches.show', $match),
                    'icon' => 'fa-exchange-alt'
                ];
            }
        }
        
        // Sort results by date
        usort($results, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        return view('search.results', compact('results', 'query'));
    }
    
    /**
     * Live search for users (AJAX)
     */
    public function userLiveSearch(Request $request)
    {
        $query = $request->get('query');
        $filters = $request->get('filters') ? explode(',', $request->get('filters')) : [];
        $status = $request->get('status');
        $itemType = $request->get('item_type');
        $dateRange = $request->get('date_range');
        
        $results = [];
        $userId = Auth::id();
        $total = 0;
        
        // Similar search logic but return JSON
        // Search in user's lost items
        if (empty($filters) || in_array('my_lost', $filters)) {
            $lostQuery = LostItem::where('user_id', $userId);
            
            if ($query) {
                $lostQuery->where(function($q) use ($query) {
                    $q->where('item_name', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%")
                      ->orWhere('location', 'LIKE', "%{$query}%");
                });
            }
            
            if ($status) {
                $lostQuery->where('status', $status);
            }
            
            if ($dateRange) {
                $lostQuery = $this->applyDateFilter($lostQuery, $dateRange);
            }
            
            $total += $lostQuery->count();
            $lostItems = $lostQuery->latest()->limit(5)->get();
            
            foreach ($lostItems as $item) {
                $results[] = [
                    'type' => 'lost',
                    'title' => $item->item_name,
                    'subtitle' => $item->description,
                    'status' => $item->status,
                    'date' => $item->created_at->format('M d, Y'),
                    'url' => route('lost-items.show', $item),
                    'icon' => 'fa-exclamation-circle'
                ];
            }
        }
        
        // Similar for found items and matches...
        // (Add the same logic for found items and matches)
        
        return response()->json([
            'results' => $results,
            'total' => $total
        ]);
    }
    
    /**
     * Live search for admin (AJAX)
     */
    public function adminLiveSearch(Request $request)
    {
        $query = $request->get('query');
        $filters = $request->get('filters') ? explode(',', $request->get('filters')) : [];
        $status = $request->get('status');
        $dateRange = $request->get('date_range');
        
        $results = [];
        $total = 0;
        
        // Similar search logic but return JSON
        // Search in lost items
        if (empty($filters) || in_array('lost_items', $filters)) {
            $lostQuery = LostItem::with('user');
            
            if ($query) {
                $lostQuery->where(function($q) use ($query) {
                    $q->where('item_name', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%")
                      ->orWhere('location', 'LIKE', "%{$query}%")
                      ->orWhereHas('user', function($q) use ($query) {
                          $q->where('name', 'LIKE', "%{$query}%");
                      });
                });
            }
            
            if ($status) {
                $lostQuery->where('status', $status);
            }
            
            if ($dateRange) {
                $lostQuery = $this->applyDateFilter($lostQuery, $dateRange);
            }
            
            $total += $lostQuery->count();
            $lostItems = $lostQuery->latest()->limit(5)->get();
            
            foreach ($lostItems as $item) {
                $results[] = [
                    'type' => 'lost',
                    'title' => $item->item_name,
                    'subtitle' => 'Reported by: ' . ($item->user->name ?? 'Unknown'),
                    'status' => $item->status,
                    'date' => $item->created_at->format('M d, Y'),
                    'url' => route('lost-items.show', $item),
                    'icon' => 'fa-exclamation-circle'
                ];
            }
        }
        
        // Similar for found items, users, and matches...
        // (Add the same logic for other types)
        
        return response()->json([
            'results' => $results,
            'total' => $total
        ]);
    }
    
    /**
     * Apply date filter to query
     */
    private function applyDateFilter($query, $dateRange, $column = 'created_at')
    {
        switch ($dateRange) {
            case 'today':
                return $query->whereDate($column, today());
            case 'week':
                return $query->whereBetween($column, [now()->startOfWeek(), now()->endOfWeek()]);
            case 'month':
                return $query->whereMonth($column, now()->month);
            case 'year':
                return $query->whereYear($column, now()->year);
            default:
                return $query;
        }
    }
}