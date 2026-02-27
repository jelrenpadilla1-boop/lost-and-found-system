<?php

namespace App\Http\Controllers;

use App\Models\LostItem;
use App\Models\FoundItem;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index(Request $request)
    {
        // Get lost items with location (either coordinates or location name)
        $lostItems = LostItem::where(function($query) {
                $query->whereNotNull('latitude')
                    ->whereNotNull('longitude');
            })
            ->orWhereNotNull('lost_location')
            ->where('status', 'pending')
            ->select(['id', 'item_name', 'description', 'category', 'photo', 'latitude', 'longitude', 'lost_location', 'created_at'])
            ->latest()
            ->get();
            
        // Get found items with location (either coordinates or location name)
        $foundItems = FoundItem::where(function($query) {
                $query->whereNotNull('latitude')
                    ->whereNotNull('longitude');
            })
            ->orWhereNotNull('found_location')
            ->where('status', 'pending')
            ->select(['id', 'item_name', 'description', 'category', 'photo', 'latitude', 'longitude', 'found_location', 'created_at'])
            ->latest()
            ->get();
        
        return view('map.index', compact('lostItems', 'foundItems'));
    }
    
    public function getItems(Request $request)
    {
        $bounds = $request->validate([
            'north' => 'required|numeric',
            'south' => 'required|numeric',
            'east' => 'required|numeric',
            'west' => 'required|numeric',
        ]);
        
        $lostItems = LostItem::where(function($query) use ($bounds) {
                $query->whereBetween('latitude', [$bounds['south'], $bounds['north']])
                    ->whereBetween('longitude', [$bounds['west'], $bounds['east']]);
            })
            ->orWhereNotNull('lost_location')
            ->where('status', 'pending')
            ->select(['id', 'item_name', 'description', 'category', 'photo', 'latitude', 'longitude', 'lost_location', 'created_at'])
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->item_name,
                    'lat' => $item->latitude,
                    'lng' => $item->longitude,
                    'location_name' => $item->lost_location,
                    'category' => $item->category,
                    'photo' => $item->photo ? asset('storage/' . $item->photo) : null,
                    'description' => substr($item->description, 0, 100) . '...',
                    'type' => 'lost',
                    'url' => route('lost-items.show', $item->id)
                ];
            });
            
        $foundItems = FoundItem::where(function($query) use ($bounds) {
                $query->whereBetween('latitude', [$bounds['south'], $bounds['north']])
                    ->whereBetween('longitude', [$bounds['west'], $bounds['east']]);
            })
            ->orWhereNotNull('found_location')
            ->where('status', 'pending')
            ->select(['id', 'item_name', 'description', 'category', 'photo', 'latitude', 'longitude', 'found_location', 'created_at'])
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->item_name,
                    'lat' => $item->latitude,
                    'lng' => $item->longitude,
                    'location_name' => $item->found_location,
                    'category' => $item->category,
                    'photo' => $item->photo ? asset('storage/' . $item->photo) : null,
                    'description' => substr($item->description, 0, 100) . '...',
                    'type' => 'found',
                    'url' => route('found-items.show', $item->id)
                ];
            });
        
        return response()->json([
            'lost_items' => $lostItems,
            'found_items' => $foundItems
        ]);
    }
}