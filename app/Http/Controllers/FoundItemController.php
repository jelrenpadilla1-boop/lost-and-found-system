<?php

namespace App\Http\Controllers;

use App\Models\FoundItem;
use App\Services\AIMatchingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FoundItemController extends Controller
{
    protected $matchingService;

    public function __construct(AIMatchingService $matchingService)
    {
        $this->matchingService = $matchingService;
    }

    public function index(Request $request)
    {
        $query = FoundItem::with('user');
        
        // Apply filters
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('found_location', 'like', "%{$search}%"); // Add search in location
            });
        }
        
        // Get paginated results
        $foundItems = $query->latest()->paginate(10)->withQueryString();
        
        // Calculate stats for the cards
        $totalItems = FoundItem::count();
        $pendingCount = FoundItem::where('status', 'pending')->count();
        $claimedCount = FoundItem::where('status', 'claimed')->count();
        $disposedCount = FoundItem::where('status', 'disposed')->count();
        $activeReporters = FoundItem::distinct('user_id')->count('user_id');
        
        return view('found-items.index', compact(
            'foundItems', 
            'totalItems', 
            'pendingCount', 
            'claimedCount', 
            'disposedCount',
            'activeReporters'
        ));
    }

    public function create()
    {
        return view('found-items.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'date_found' => 'required|date',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'found_location' => 'nullable|string|max:255', // Add validation
        ]);

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('found-items', 'public');
            $validated['photo'] = $photoPath;
        }

        $validated['user_id'] = Auth::id();
        
        if (!$request->latitude && Auth::user()->latitude) {
            $validated['latitude'] = Auth::user()->latitude;
            $validated['longitude'] = Auth::user()->longitude;
            
            // Optional: Reverse geocode to get address
            // $validated['found_location'] = $this->getAddressFromCoordinates($validated['latitude'], $validated['longitude']);
        }

        $foundItem = FoundItem::create($validated);

        // Trigger AI matching
        $matches = $this->matchingService->findMatchesForFoundItem($foundItem);

        return redirect()->route('found-items.show', $foundItem)
            ->with('success', 'Found item reported successfully!')
            ->with('matches_found', count($matches) > 0);
    }

    public function show(FoundItem $foundItem)
    {
        $matches = $foundItem->matches()->with('lostItem.user')->orderBy('match_score', 'desc')->get();
        return view('found-items.show', compact('foundItem', 'matches'));
    }

    public function edit(FoundItem $foundItem)
    {
        $this->authorize('update', $foundItem);
        return view('found-items.edit', compact('foundItem'));
    }

    public function update(Request $request, FoundItem $foundItem)
    {
        $this->authorize('update', $foundItem);

        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'date_found' => 'required|date',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'found_location' => 'nullable|string|max:255', // Add validation
            'status' => 'required|in:pending,claimed,disposed',
        ]);

        if ($request->hasFile('photo')) {
            if ($foundItem->photo) {
                Storage::disk('public')->delete($foundItem->photo);
            }
            $photoPath = $request->file('photo')->store('found-items', 'public');
            $validated['photo'] = $photoPath;
        }

        $foundItem->update($validated);

        if ($request->hasAny(['item_name', 'description', 'category', 'latitude', 'longitude', 'found_location'])) {
            $this->matchingService->findMatchesForFoundItem($foundItem);
        }

        return redirect()->route('found-items.show', $foundItem)
            ->with('success', 'Found item updated successfully!');
    }

    public function destroy(FoundItem $foundItem)
    {
        $this->authorize('delete', $foundItem);
        
        if ($foundItem->photo) {
            Storage::disk('public')->delete($foundItem->photo);
        }
        
        $foundItem->delete();
        
        return redirect()->route('found-items.index')
            ->with('success', 'Found item deleted successfully!');
    }

    public function myItems()
    {
        $foundItems = Auth::user()->foundItems()->latest()->paginate(10);
        
        // Calculate stats for user's items
        $totalItems = $foundItems->total();
        $pendingCount = Auth::user()->foundItems()->where('status', 'pending')->count();
        $claimedCount = Auth::user()->foundItems()->where('status', 'claimed')->count();
        $disposedCount = Auth::user()->foundItems()->where('status', 'disposed')->count();
        
        return view('found-items.my-items', compact('foundItems', 'totalItems', 'pendingCount', 'claimedCount', 'disposedCount'));
    }

    // Optional: Add a method to reverse geocode coordinates to address
    private function getAddressFromCoordinates($latitude, $longitude)
    {
        try {
            $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$latitude}&lon={$longitude}&zoom=18&addressdetails=1";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Your App Name');
            $response = curl_exec($ch);
            curl_close($ch);
            
            $data = json_decode($response, true);
            return $data['display_name'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }
}