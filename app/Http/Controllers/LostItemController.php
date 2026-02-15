<?php

namespace App\Http\Controllers;

use App\Models\LostItem;
use App\Services\AIMatchingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LostItemController extends Controller
{
    protected $matchingService;

    public function __construct(AIMatchingService $matchingService)
    {
        $this->matchingService = $matchingService;
    }

    public function index(Request $request)
    {
        $query = LostItem::with('user');
        
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
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Get paginated results with preserved query string
        $lostItems = $query->latest()->paginate(10)->withQueryString();
        
        // Calculate stats for the cards
        $totalItems = LostItem::count();
        $pendingCount = LostItem::where('status', 'pending')->count();
        $foundCount = LostItem::where('status', 'found')->count();
        $returnedCount = LostItem::where('status', 'returned')->count();
        $activeReporters = LostItem::distinct('user_id')->count('user_id');
        
        return view('lost-items.index', compact(
            'lostItems', 
            'totalItems', 
            'pendingCount', 
            'foundCount', 
            'returnedCount',
            'activeReporters'
        ));
    }

    public function create()
    {
        return view('lost-items.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'date_lost' => 'required|date',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('lost-items', 'public');
            $validated['photo'] = $photoPath;
        }

        $validated['user_id'] = Auth::id();
        
        // Use user's location if not provided
        if (!$request->latitude && Auth::user()->latitude) {
            $validated['latitude'] = Auth::user()->latitude;
            $validated['longitude'] = Auth::user()->longitude;
        }

        $lostItem = LostItem::create($validated);

        // Trigger AI matching
        $matches = $this->matchingService->findMatchesForLostItem($lostItem);

        return redirect()->route('lost-items.show', $lostItem)
            ->with('success', 'Lost item reported successfully!')
            ->with('matches_found', count($matches) > 0);
    }

    public function show(LostItem $lostItem)
    {
        $matches = $lostItem->matches()->with('foundItem.user')->orderBy('match_score', 'desc')->get();
        return view('lost-items.show', compact('lostItem', 'matches'));
    }

    public function edit(LostItem $lostItem)
    {
        $this->authorize('update', $lostItem);
        return view('lost-items.edit', compact('lostItem'));
    }

    public function update(Request $request, LostItem $lostItem)
    {
        $this->authorize('update', $lostItem);

        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'date_lost' => 'required|date',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'status' => 'required|in:pending,found,returned',
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($lostItem->photo) {
                Storage::disk('public')->delete($lostItem->photo);
            }
            $photoPath = $request->file('photo')->store('lost-items', 'public');
            $validated['photo'] = $photoPath;
        }

        $lostItem->update($validated);

        // Re-run matching if relevant fields changed
        if ($request->hasAny(['item_name', 'description', 'category', 'latitude', 'longitude'])) {
            $this->matchingService->findMatchesForLostItem($lostItem);
        }

        return redirect()->route('lost-items.show', $lostItem)
            ->with('success', 'Lost item updated successfully!');
    }

    public function destroy(LostItem $lostItem)
    {
        $this->authorize('delete', $lostItem);
        
        if ($lostItem->photo) {
            Storage::disk('public')->delete($lostItem->photo);
        }
        
        $lostItem->delete();
        
        return redirect()->route('lost-items.index')
            ->with('success', 'Lost item deleted successfully!');
    }

    public function myItems()
    {
        $lostItems = Auth::user()->lostItems()->latest()->paginate(10);
        
        // Calculate stats for user's items
        $totalItems = $lostItems->total();
        $pendingCount = Auth::user()->lostItems()->where('status', 'pending')->count();
        $foundCount = Auth::user()->lostItems()->where('status', 'found')->count();
        $returnedCount = Auth::user()->lostItems()->where('status', 'returned')->count();
        
        return view('lost-items.my-items', compact('lostItems', 'totalItems', 'pendingCount', 'foundCount', 'returnedCount'));
    }
}