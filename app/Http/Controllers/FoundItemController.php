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

    public function index()
    {
        $foundItems = FoundItem::with('user')->latest()->paginate(10);
        return view('found-items.index', compact('foundItems'));
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
        ]);

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('found-items', 'public');
            $validated['photo'] = $photoPath;
        }

        $validated['user_id'] = Auth::id();
        
        if (!$request->latitude && Auth::user()->latitude) {
            $validated['latitude'] = Auth::user()->latitude;
            $validated['longitude'] = Auth::user()->longitude;
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

        if ($request->hasAny(['item_name', 'description', 'category', 'latitude', 'longitude'])) {
            $this->matchingService->findMatchesForFoundItem($foundItem);
        }

        return redirect()->route('found-items.show', $foundItem)
            ->with('success', 'Found item updated successfully!');
    }

    public function destroy(FoundItem $foundItem)
    {
        
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
        return view('found-items.my-items', compact('foundItems'));
    }
}