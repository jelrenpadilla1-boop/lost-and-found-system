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

        // ── Split for legacy template variables (still used by the sidebar counts) ──
        $lostWithCoords  = $lostItems->filter(fn ($i) => $i->latitude && $i->longitude);
        $foundWithCoords = $foundItems->filter(fn ($i) => $i->latitude && $i->longitude);
        $lostToGeocode   = $lostItems->filter(fn ($i) => (!$i->latitude || !$i->longitude) && $i->lost_location);
        $foundToGeocode  = $foundItems->filter(fn ($i) => (!$i->latitude || !$i->longitude) && $i->found_location);

        // ── Build the JSON payload for the Blade view ─────────────────────────
        // IMPORTANT:
        //   • lat/lng are cast to float|null  — never let PHP serialize them as the
        //     string "null", which parseFloat("null") → NaN in JS.
        //   • 'url' is pre-built here so JS never has to reconstruct route paths.
        //   • ->values() re-indexes the collection so JSON encodes as an array [].
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
            ])
        )->values();

        return view('map.index', compact(
            'lostItems',
            'foundItems',
            'lostWithCoords',
            'foundWithCoords',
            'lostToGeocode',
            'foundToGeocode',
            'allItems'
        ));
    }

    // ── AJAX endpoint ─────────────────────────────────────────────────────────
    public function getItems(Request $request)
    {
        try {
            $query = $request->get('query');

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

            $lost = $lostBase
                ->select(['id', 'item_name', 'description', 'category', 'photo',
                          'latitude', 'longitude', 'lost_location', 'status', 'created_at'])
                ->limit(200)->get()
                ->map(fn ($i) => [
                    'id'            => $i->id,
                    'item_name'     => $i->item_name,
                    'description'   => $i->description,
                    'category'      => $i->category,
                    'photo'         => $i->photo ? asset('storage/' . $i->photo) : null,
                    'latitude'      => $i->latitude  ? (float) $i->latitude  : null,
                    'longitude'     => $i->longitude ? (float) $i->longitude : null,
                    'location_name' => $i->lost_location,
                    'type'          => 'lost',
                    'url'           => route('lost-items.show', $i->id),
                ]);

            $found = $foundBase
                ->select(['id', 'item_name', 'description', 'category', 'photo',
                          'latitude', 'longitude', 'found_location', 'status', 'created_at'])
                ->limit(200)->get()
                ->map(fn ($i) => [
                    'id'            => $i->id,
                    'item_name'     => $i->item_name,
                    'description'   => $i->description,
                    'category'      => $i->category,
                    'photo'         => $i->photo ? asset('storage/' . $i->photo) : null,
                    'latitude'      => $i->latitude  ? (float) $i->latitude  : null,
                    'longitude'     => $i->longitude ? (float) $i->longitude : null,
                    'location_name' => $i->found_location,
                    'type'          => 'found',
                    'url'           => route('found-items.show', $i->id),
                ]);

            return response()->json([
                'success' => true,
                'lost'    => $lost,
                'found'   => $found,
                'all'     => $lost->merge($found)->values(),
                'total'   => $lost->count() + $found->count(),
            ]);

        } catch (\Exception $e) {
            Log::error('Map getItems error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch map items',
                'lost' => [], 'found' => [], 'all' => [], 'total' => 0,
            ]);
        }
    }

    // ── Server-side geocode helper ────────────────────────────────────────────
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
}