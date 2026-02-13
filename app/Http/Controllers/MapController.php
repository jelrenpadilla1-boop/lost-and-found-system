<?php

namespace App\Http\Controllers;

use App\Models\LostItem;
use App\Models\FoundItem;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index(Request $request)
    {
        // Get lost items with location
        $lostItems = LostItem::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('status', 'pending')
            ->select(['id', 'item_name', 'description', 'category', 'photo', 'latitude', 'longitude', 'created_at'])
            ->get();
            
        // Get found items with location
        $foundItems = FoundItem::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('status', 'pending')
            ->select(['id', 'item_name', 'description', 'category', 'photo', 'latitude', 'longitude', 'created_at'])
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
        
        $lostItems = LostItem::whereBetween('latitude', [$bounds['south'], $bounds['north']])
            ->whereBetween('longitude', [$bounds['west'], $bounds['east']])
            ->where('status', 'pending')
            ->select(['id', 'item_name', 'description', 'category', 'photo', 'latitude', 'longitude', 'created_at'])
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->item_name,
                    'lat' => $item->latitude,
                    'lng' => $item->longitude,
                    'category' => $item->category,
                    'photo' => $item->photo ? asset('storage/' . $item->photo) : null,
                    'description' => substr($item->description, 0, 100) . '...',
                    'type' => 'lost',
                    'url' => route('lost-items.show', $item->id)
                ];
            });
            
        $foundItems = FoundItem::whereBetween('latitude', [$bounds['south'], $bounds['north']])
            ->whereBetween('longitude', [$bounds['west'], $bounds['east']])
            ->where('status', 'pending')
            ->select(['id', 'item_name', 'description', 'category', 'photo', 'latitude', 'longitude', 'created_at'])
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->item_name,
                    'lat' => $item->latitude,
                    'lng' => $item->longitude,
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