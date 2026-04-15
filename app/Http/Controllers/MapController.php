<?php

namespace App\Http\Controllers;

use App\Models\LostItem;
use App\Models\FoundItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MapController extends Controller
{
    public function index(Request $request)
    {
        // ── Lost items: ALL EXCEPT pending and rejected ───────────
        $lostItems = LostItem::whereNotIn('status', ['pending', 'rejected'])
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

        // ── Found items: ALL EXCEPT pending and rejected ──────────
        $foundItems = FoundItem::whereNotIn('status', ['pending', 'rejected'])
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

        // ── Build the JSON payload for the Blade view ─────────────────────────
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
        ])->concat(
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
            ])
        )->values();

        // Log for debugging
        Log::info('Map - Lost: ' . $lostItems->count() . ', Found: ' . $foundItems->count() . ', Total: ' . $allItems->count());

        return view('map.index', compact(
            'lostItems',
            'foundItems',
            'allItems'
        ));
    }

    // ── AJAX endpoint ─────────────────────────────────────────────────────────
    public function getItems(Request $request)
    {
        try {
            $query = $request->get('query');
            
            // Get ALL items except pending and rejected
            $lostBase = LostItem::whereNotIn('status', ['pending', 'rejected']);
            $foundBase = FoundItem::whereNotIn('status', ['pending', 'rejected']);
            
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
            
            $all = $lost->concat($found)->values();
            
            return response()->json([
                'success' => true,
                'lost' => $lost,
                'found' => $found,
                'all' => $all,
                'total' => $all->count(),
            ]);
            
        } catch (\Exception $e) {
            Log::error('Map getItems error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch map items',
                'lost' => [],
                'found' => [],
                'all' => [],
                'total' => 0,
            ]);
        }
    }

    // ── Geocode helper ─────────────────────────────────────────────────────────
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
}