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
     * Get all map items - matches web MapController logic exactly
     */
    public function index(Request $request)
    {
        try {
            // ── Lost items: approved + has at least one location source ───────────
            $lostItems = LostItem::where('status', 'approved')
                ->where(function ($q) {
                    $q->where(function ($sub) {
                        $sub->whereNotNull('latitude')->whereNotNull('longitude');
                    })->orWhere(function ($sub) {
                        $sub->whereNotNull('lost_location')->where('lost_location', '!=', '');
                    });
                })
                ->select(['id', 'item_name', 'description', 'category', 'photo',
                          'latitude', 'longitude', 'lost_location', 'status', 'created_at'])
                ->latest()
                ->get();

            // ── Found items: approved + has at least one location source ──────────
            $foundItems = FoundItem::where('status', 'approved')
                ->where(function ($q) {
                    $q->where(function ($sub) {
                        $sub->whereNotNull('latitude')->whereNotNull('longitude');
                    })->orWhere(function ($sub) {
                        $sub->whereNotNull('found_location')->where('found_location', '!=', '');
                    });
                })
                ->select(['id', 'item_name', 'description', 'category', 'photo',
                          'latitude', 'longitude', 'found_location', 'status', 'created_at'])
                ->latest()
                ->get();

            // ── Split for statistics ─────────────────────────────────────────────
            $lostWithCoords  = $lostItems->filter(fn ($i) => $i->latitude && $i->longitude);
            $foundWithCoords = $foundItems->filter(fn ($i) => $i->latitude && $i->longitude);
            $lostToGeocode   = $lostItems->filter(fn ($i) => (!$i->latitude || !$i->longitude) && $i->lost_location);
            $foundToGeocode  = $foundItems->filter(fn ($i) => (!$i->latitude || !$i->longitude) && $i->found_location);

            // ── Build the JSON payload ───────────────────────────────────────────
            $allItems = $lostItems->map(fn ($item) => [
                'id'            => $item->id,
                'item_name'     => $item->item_name,
                'description'   => $item->description,
                'category'      => $item->category,
                'photo'         => $item->photo ? asset('storage/' . $item->photo) : null,
                'latitude'      => $item->latitude  ? (float) $item->latitude  : null,
                'longitude'     => $item->longitude ? (float) $item->longitude : null,
                'location_name' => $item->lost_location,
                'status'        => $item->status,
                'created_at'    => $item->created_at,
                'type'          => 'lost',
                'url'           => route('lost-items.show', $item->id),
                'has_coordinates' => $item->latitude && $item->longitude,
            ])->merge(
                $foundItems->map(fn ($item) => [
                    'id'            => $item->id,
                    'item_name'     => $item->item_name,
                    'description'   => $item->description,
                    'category'      => $item->category,
                    'photo'         => $item->photo ? asset('storage/' . $item->photo) : null,
                    'latitude'      => $item->latitude  ? (float) $item->latitude  : null,
                    'longitude'     => $item->longitude ? (float) $item->longitude : null,
                    'location_name' => $item->found_location,
                    'status'        => $item->status,
                    'created_at'    => $item->created_at,
                    'type'          => 'found',
                    'url'           => route('found-items.show', $item->id),
                    'has_coordinates' => $item->latitude && $item->longitude,
                ])
            )->values();

            return response()->json([
                'success' => true,
                'data' => $allItems,
                'lost_items' => $lostItems->map(fn ($i) => $this->formatLostItem($i)),
                'found_items' => $foundItems->map(fn ($i) => $this->formatFoundItem($i)),
                'stats' => [
                    'lost_total' => $lostItems->count(),
                    'found_total' => $foundItems->count(),
                    'lost_with_coords' => $lostWithCoords->count(),
                    'found_with_coords' => $foundWithCoords->count(),
                    'lost_to_geocode' => $lostToGeocode->count(),
                    'found_to_geocode' => $foundToGeocode->count(),
                    'total_items' => $lostItems->count() + $foundItems->count(),
                    'total_with_coords' => $lostWithCoords->count() + $foundWithCoords->count(),
                    'total_needs_geocoding' => $lostToGeocode->count() + $foundToGeocode->count(),
                ],
                'total' => $lostItems->count() + $foundItems->count(),
            ]);

        } catch (\Exception $e) {
            Log::error('API Map index error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch map items: ' . $e->getMessage(),
                'data' => [],
                'total' => 0
            ], 500);
        }
    }

    /**
     * Get items with optional filtering (matches web getItems logic)
     */
    public function getItems(Request $request)
    {
        try {
            $query = $request->get('query');
            $bounds = $request->only(['north', 'south', 'east', 'west']);

            $lostBase = LostItem::where('status', 'approved')
                ->where(fn ($q) => $q
                    ->where(fn ($s) => $s->whereNotNull('latitude')->whereNotNull('longitude'))
                    ->orWhere(fn ($s) => $s->whereNotNull('lost_location')->where('lost_location', '!=', ''))
                );

            $foundBase = FoundItem::where('status', 'approved')
                ->where(fn ($q) => $q
                    ->where(fn ($s) => $s->whereNotNull('latitude')->whereNotNull('longitude'))
                    ->orWhere(fn ($s) => $s->whereNotNull('found_location')->where('found_location', '!=', ''))
                );

            if ($query) {
                $lostBase->where(fn ($q) => $q
                    ->where('lost_location', 'like', "%{$query}%")
                    ->orWhere('item_name',   'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                );
                $foundBase->where(fn ($q) => $q
                    ->where('found_location', 'like', "%{$query}%")
                    ->orWhere('item_name',    'like', "%{$query}%")
                    ->orWhere('description',  'like', "%{$query}%")
                );
            }

            // Apply bounds filter if provided (only for items with coordinates)
            if ($bounds && isset($bounds['north']) && isset($bounds['south']) && isset($bounds['east']) && isset($bounds['west'])) {
                $lostBase->whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->whereBetween('latitude', [(float)$bounds['south'], (float)$bounds['north']])
                    ->whereBetween('longitude', [(float)$bounds['west'], (float)$bounds['east']]);
                    
                $foundBase->whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->whereBetween('latitude', [(float)$bounds['south'], (float)$bounds['north']])
                    ->whereBetween('longitude', [(float)$bounds['west'], (float)$bounds['east']]);
            }

            $lost = $lostBase
                ->select(['id', 'item_name', 'description', 'category', 'photo',
                          'latitude', 'longitude', 'lost_location', 'status', 'created_at'])
                ->limit(200)->get()
                ->map(fn ($i) => $this->formatLostItem($i));

            $found = $foundBase
                ->select(['id', 'item_name', 'description', 'category', 'photo',
                          'latitude', 'longitude', 'found_location', 'status', 'created_at'])
                ->limit(200)->get()
                ->map(fn ($i) => $this->formatFoundItem($i));

            $all = $lost->merge($found)->values();

            return response()->json([
                'success' => true,
                'lost'    => $lost,
                'found'   => $found,
                'all'     => $all,
                'total'   => $lost->count() + $found->count(),
            ]);

        } catch (\Exception $e) {
            Log::error('API Map getItems error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch map items',
                'lost' => [], 'found' => [], 'all' => [], 'total' => 0,
            ]);
        }
    }

    /**
     * Server-side geocode helper
     */
    public function geocode(Request $request)
    {
        try {
            $address = trim($request->get('address', ''));
            if (!$address) {
                return response()->json(['success' => false, 'message' => 'Address is required']);
            }

            $attempts = array_filter([
                $address,
                stripos($address, 'philippines') === false ? "{$address}, Philippines" : null,
                stripos($address, 'bohol')       === false ? "{$address}, Bohol, Philippines" : null,
            ]);

            foreach ($attempts as $query) {
                $resp = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
                    'address' => $query,
                    'key'     => env('GOOGLE_MAPS_API_KEY'),
                ]);

                if ($resp->successful() && !empty($resp->json()['results'][0])) {
                    $loc = $resp->json()['results'][0]['geometry']['location'];
                    Log::info("Geocoded '{$address}' via '{$query}': ({$loc['lat']}, {$loc['lng']})");
                    return response()->json([
                        'success'           => true,
                        'lat'               => $loc['lat'],
                        'lng'               => $loc['lng'],
                        'formatted_address' => $resp->json()['results'][0]['formatted_address'],
                    ]);
                }
            }

            Log::warning("Failed to geocode: {$address}");
            return response()->json(['success' => false, 'message' => "Could not geocode: {$address}"]);

        } catch (\Exception $e) {
            Log::error('Geocode error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Geocoding failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Batch geocode multiple addresses
     */
    public function batchGeocode(Request $request)
    {
        try {
            $addresses = $request->get('addresses', []);
            if (empty($addresses)) {
                return response()->json(['success' => false, 'message' => 'Addresses array is required']);
            }

            $results = [];
            foreach ($addresses as $address) {
                if (empty($address)) {
                    $results[] = ['address' => $address, 'success' => false, 'message' => 'Empty address'];
                    continue;
                }

                $attempts = array_filter([
                    $address,
                    stripos($address, 'philippines') === false ? "{$address}, Philippines" : null,
                    stripos($address, 'bohol')       === false ? "{$address}, Bohol, Philippines" : null,
                ]);

                $found = false;
                foreach ($attempts as $query) {
                    $resp = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
                        'address' => $query,
                        'key'     => env('GOOGLE_MAPS_API_KEY'),
                    ]);

                    if ($resp->successful() && !empty($resp->json()['results'][0])) {
                        $loc = $resp->json()['results'][0]['geometry']['location'];
                        $results[] = [
                            'address'           => $address,
                            'success'           => true,
                            'lat'               => $loc['lat'],
                            'lng'               => $loc['lng'],
                            'formatted_address' => $resp->json()['results'][0]['formatted_address'],
                        ];
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $results[] = ['address' => $address, 'success' => false, 'message' => 'No results found'];
                }
            }

            return response()->json(['success' => true, 'results' => $results]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Batch geocoding failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get statistics
     */
    public function getStats(Request $request)
    {
        try {
            $lostWithCoords = LostItem::where('status', 'approved')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->count();
                
            $lostWithoutCoords = LostItem::where('status', 'approved')
                ->where(fn ($q) => $q->whereNull('latitude')->orWhereNull('longitude'))
                ->whereNotNull('lost_location')
                ->where('lost_location', '!=', '')
                ->count();
                
            $foundWithCoords = FoundItem::where('status', 'approved')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->count();
                
            $foundWithoutCoords = FoundItem::where('status', 'approved')
                ->where(fn ($q) => $q->whereNull('latitude')->orWhereNull('longitude'))
                ->whereNotNull('found_location')
                ->where('found_location', '!=', '')
                ->count();

            return response()->json([
                'success' => true,
                'stats' => [
                    'lost_total' => LostItem::where('status', 'approved')->count(),
                    'found_total' => FoundItem::where('status', 'approved')->count(),
                    'lost_with_coordinates' => $lostWithCoords,
                    'lost_needs_geocoding' => $lostWithoutCoords,
                    'found_with_coordinates' => $foundWithCoords,
                    'found_needs_geocoding' => $foundWithoutCoords,
                    'total_with_coordinates' => $lostWithCoords + $foundWithCoords,
                    'total_needs_geocoding' => $lostWithoutCoords + $foundWithoutCoords,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to fetch stats: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get a single item by type and ID
     */
    public function getItem(Request $request, $type, $id)
    {
        try {
            if ($type === 'lost') {
                $item = LostItem::where('id', $id)
                    ->where('status', 'approved')
                    ->first();
                    
                if ($item) {
                    return response()->json([
                        'success' => true,
                        'item' => $this->formatLostItem($item)
                    ]);
                }
            } elseif ($type === 'found') {
                $item = FoundItem::where('id', $id)
                    ->where('status', 'approved')
                    ->first();
                    
                if ($item) {
                    return response()->json([
                        'success' => true,
                        'item' => $this->formatFoundItem($item)
                    ]);
                }
            }

            return response()->json(['success' => false, 'message' => 'Item not found'], 404);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to fetch item: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Format a lost item for JSON response
     */
    private function formatLostItem($item)
    {
        return [
            'id'            => $item->id,
            'item_name'     => $item->item_name,
            'description'   => $item->description,
            'category'      => $item->category,
            'photo'         => $item->photo ? asset('storage/' . $item->photo) : null,
            'latitude'      => $item->latitude  ? (float) $item->latitude  : null,
            'longitude'     => $item->longitude ? (float) $item->longitude : null,
            'location_name' => $item->lost_location,
            'status'        => $item->status,
            'created_at'    => $item->created_at,
            'type'          => 'lost',
            'url'           => route('lost-items.show', $item->id),
            'has_coordinates' => $item->latitude && $item->longitude,
        ];
    }

    /**
     * Format a found item for JSON response
     */
    private function formatFoundItem($item)
    {
        return [
            'id'            => $item->id,
            'item_name'     => $item->item_name,
            'description'   => $item->description,
            'category'      => $item->category,
            'photo'         => $item->photo ? asset('storage/' . $item->photo) : null,
            'latitude'      => $item->latitude  ? (float) $item->latitude  : null,
            'longitude'     => $item->longitude ? (float) $item->longitude : null,
            'location_name' => $item->found_location,
            'status'        => $item->status,
            'created_at'    => $item->created_at,
            'type'          => 'found',
            'url'           => route('found-items.show', $item->id),
            'has_coordinates' => $item->latitude && $item->longitude,
        ];
    }
}