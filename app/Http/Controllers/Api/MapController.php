<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LostItem;
use App\Models\FoundItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MapController extends Controller
{
    /**
     * Get map items - display ALL items EXCEPT pending and rejected (same as web)
     */
    public function getItems(Request $request)
    {
        try {
            $query = $request->get('query');
            
            // Get ALL lost items EXCEPT pending and rejected
            $lostBase = LostItem::whereNotIn('status', ['pending', 'rejected']);
            
            // Get ALL found items EXCEPT pending and rejected
            $foundBase = FoundItem::whereNotIn('status', ['pending', 'rejected']);
            
            // Apply search filter if provided
            if ($query) {
                $lostBase->where(function($q) use ($query) {
                    $q->where('lost_location', 'like', "%{$query}%")
                      ->orWhere('item_name', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                });
                $foundBase->where(function($q) use ($query) {
                    $q->where('found_location', 'like', "%{$query}%")
                      ->orWhere('item_name', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                });
            }
            
            // Get lost items
            $lost = $lostBase
                ->select('id', 'item_name', 'description', 'category', 'photo',
                         'latitude', 'longitude', 'lost_location', 'status', 'created_at')
                ->limit(500)
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'item_name' => $item->item_name,
                        'description' => $item->description,
                        'category' => $item->category,
                        'photo' => $item->photo ? asset('storage/' . $item->photo) : null,
                        'latitude' => $item->latitude ? (float) $item->latitude : null,
                        'longitude' => $item->longitude ? (float) $item->longitude : null,
                        'location_name' => $item->lost_location,
                        'status' => $item->status,
                        'type' => 'lost',
                        'url' => route('lost-items.show', $item->id),
                    ];
                });
            
            // Get found items
            $found = $foundBase
                ->select('id', 'item_name', 'description', 'category', 'photo',
                         'latitude', 'longitude', 'found_location', 'status', 'created_at')
                ->limit(500)
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'item_name' => $item->item_name,
                        'description' => $item->description,
                        'category' => $item->category,
                        'photo' => $item->photo ? asset('storage/' . $item->photo) : null,
                        'latitude' => $item->latitude ? (float) $item->latitude : null,
                        'longitude' => $item->longitude ? (float) $item->longitude : null,
                        'location_name' => $item->found_location,
                        'status' => $item->status,
                        'type' => 'found',
                        'url' => route('found-items.show', $item->id),
                    ];
                });
            
            // Merge all items
            $all = $lost->concat($found)->values();
            
            // Log for debugging
            Log::info('Map API - Lost: ' . $lost->count() . ', Found: ' . $found->count() . ', Total: ' . $all->count());
            
            // Count items with coordinates
            $withCoords = $all->filter(function($item) {
                return $item['latitude'] && $item['longitude'];
            })->count();
            
            Log::info('Items with coordinates: ' . $withCoords . ', Items needing geocoding: ' . ($all->count() - $withCoords));
            
            return response()->json([
                'success' => true,
                'lost' => $lost,
                'found' => $found,
                'all' => $all,
                'total' => $all->count(),
                'stats' => [
                    'with_coordinates' => $withCoords,
                    'needs_geocoding' => $all->count() - $withCoords,
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Map getItems error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch map items: ' . $e->getMessage(),
                'lost' => [],
                'found' => [],
                'all' => [],
                'total' => 0,
            ]);
        }
    }
    
    /**
     * Geocode a single address (for frontend use)
     */
    public function geocode(Request $request)
    {
        try {
            $address = trim($request->get('address', ''));
            if (!$address) {
                return response()->json(['success' => false, 'message' => 'Address is required']);
            }
            
            // Try different address formats
            $attempts = array_filter([
                $address,
                stripos($address, 'philippines') === false ? "{$address}, Philippines" : null,
                stripos($address, 'bohol') === false ? "{$address}, Bohol, Philippines" : null,
                stripos($address, 'cebu') === false ? "{$address}, Cebu, Philippines" : null,
            ]);
            
            foreach ($attempts as $query) {
                $resp = Http::get('https://nominatim.openstreetmap.org/search', [
                    'q' => $query,
                    'format' => 'json',
                    'limit' => 1,
                ]);
                
                if ($resp->successful()) {
                    $data = $resp->json();
                    if (!empty($data)) {
                        return response()->json([
                            'success' => true,
                            'lat' => (float) $data[0]['lat'],
                            'lng' => (float) $data[0]['lon'],
                            'display_name' => $data[0]['display_name'],
                        ]);
                    }
                }
            }
            
            return response()->json(['success' => false, 'message' => 'Could not geocode address']);
            
        } catch (\Exception $e) {
            Log::error('Geocode error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    /**
     * Get items by bounds (for map viewport optimization)
     */
    public function getItemsByBounds(Request $request)
    {
        try {
            $bounds = $request->get('bounds');
            $query = $request->get('query');
            
            $lostBase = LostItem::whereNotIn('status', ['pending', 'rejected']);
            $foundBase = FoundItem::whereNotIn('status', ['pending', 'rejected']);
            
            // Filter by map bounds if provided
            if ($bounds && isset($bounds['_southWest']) && isset($bounds['_northEast'])) {
                $sw = $bounds['_southWest'];
                $ne = $bounds['_northEast'];
                
                $lostBase->whereBetween('latitude', [$sw['lat'], $ne['lat']])
                         ->whereBetween('longitude', [$sw['lng'], $ne['lng']]);
                $foundBase->whereBetween('latitude', [$sw['lat'], $ne['lat']])
                          ->whereBetween('longitude', [$sw['lng'], $ne['lng']]);
            }
            
            // Apply search filter
            if ($query) {
                $lostBase->where(function($q) use ($query) {
                    $q->where('lost_location', 'like', "%{$query}%")
                      ->orWhere('item_name', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                });
                $foundBase->where(function($q) use ($query) {
                    $q->where('found_location', 'like', "%{$query}%")
                      ->orWhere('item_name', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                });
            }
            
            $lost = $lostBase
                ->select('id', 'item_name', 'description', 'category', 'photo',
                         'latitude', 'longitude', 'lost_location', 'status', 'created_at')
                ->limit(200)
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'item_name' => $item->item_name,
                        'description' => $item->description,
                        'category' => $item->category,
                        'photo' => $item->photo ? asset('storage/' . $item->photo) : null,
                        'latitude' => $item->latitude ? (float) $item->latitude : null,
                        'longitude' => $item->longitude ? (float) $item->longitude : null,
                        'location_name' => $item->lost_location,
                        'status' => $item->status,
                        'type' => 'lost',
                        'url' => route('lost-items.show', $item->id),
                    ];
                });
            
            $found = $foundBase
                ->select('id', 'item_name', 'description', 'category', 'photo',
                         'latitude', 'longitude', 'found_location', 'status', 'created_at')
                ->limit(200)
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'item_name' => $item->item_name,
                        'description' => $item->description,
                        'category' => $item->category,
                        'photo' => $item->photo ? asset('storage/' . $item->photo) : null,
                        'latitude' => $item->latitude ? (float) $item->latitude : null,
                        'longitude' => $item->longitude ? (float) $item->longitude : null,
                        'location_name' => $item->found_location,
                        'status' => $item->status,
                        'type' => 'found',
                        'url' => route('found-items.show', $item->id),
                    ];
                });
            
            $all = $lost->concat($found)->values();
            
            return response()->json([
                'success' => true,
                'items' => $all,
                'total' => $all->count(),
            ]);
            
        } catch (\Exception $e) {
            Log::error('Map getItemsByBounds error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch map items',
                'items' => [],
                'total' => 0,
            ]);
        }
    }
}