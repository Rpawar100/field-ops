<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ATP;
use App\Models\ATPItem;
use App\Models\User;
use App\Models\Beat;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ATPController extends Controller
{
    /**
     * Display a listing of tour plans (ATPs).
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        try {
            $atps = ATP::with(['user', 'items.beat'])
                ->orderByDesc('plan_date')
                ->paginate(15);
        } catch (\Throwable $e) {
            $atps = new LengthAwarePaginator([], 0, 15);
        }

        return view('atps.index', compact('atps'));
    }

    /**
     * Show the form for creating a new tour plan.
     */
    public function create(): \Illuminate\Contracts\View\View
    {
        try {
            $users = User::where('status', 'active')->orderBy('name')->get(['id', 'name']);
            $beats = Beat::where('status', 'active')->orderBy('name')->get(['id', 'name']);
        } catch (\Throwable $e) {
            $users = $beats = collect();
        }

        return view('atps.create', compact('users', 'beats'));
    }

    /**
     * Store a newly created tour plan.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'user_id'   => 'required|exists:users,id',
            'plan_date' => 'required|date',
            'status'    => 'nullable|string|max:50',
            'remarks'   => 'nullable|string|max:1000',
        ]);

        try {
            ATP::create($validated);

            return redirect()->route('atps.index')->with('success', 'Tour plan created successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create tour plan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified tour plan.
     */
    public function show(ATP $atp): \Illuminate\Contracts\View\View
    {
        try {
            $atp->load(['user', 'items.beat']);
        } catch (\Throwable $e) {
            // Continue with base atp object
        }

        return view('atps.show', compact('atp'));
    }

    /**
     * Show the form for editing the specified tour plan.
     */
    public function edit(ATP $atp): \Illuminate\Contracts\View\View
    {
        try {
            $users = User::where('status', 'active')->orderBy('name')->get(['id', 'name']);
            $beats = Beat::where('status', 'active')->orderBy('name')->get(['id', 'name']);
        } catch (\Throwable $e) {
            $users = $beats = collect();
        }

        return view('atps.edit', compact('atp', 'users', 'beats'));
    }

    /**
     * Update the specified tour plan.
     */
    public function update(Request $request, ATP $atp): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'user_id'   => 'required|exists:users,id',
            'plan_date' => 'required|date',
            'status'    => 'nullable|string|max:50',
            'remarks'   => 'nullable|string|max:1000',
        ]);

        try {
            $atp->update($validated);

            return redirect()->route('atps.index')->with('success', 'Tour plan updated successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update tour plan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified tour plan.
     */
    public function destroy(ATP $atp): \Illuminate\Http\RedirectResponse
    {
        try {
            $atp->items()->delete();
            $atp->delete();

            return redirect()->route('atps.index')->with('success', 'Tour plan deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('atps.index')->with('error', 'Failed to delete tour plan: ' . $e->getMessage());
        }
    }

    /**
     * Display beats assigned to a tour plan.
     */
    public function beats(ATP $atp): \Illuminate\Contracts\View\View
    {
        try {
            $atp->load('items.beat');
            $atpItems = $atp->items()->with('beat')->paginate(15);
            $availableBeats = Beat::where('status', 'active')->orderBy('name')->get(['id', 'name']);
        } catch (\Throwable $e) {
            $atpItems = new LengthAwarePaginator([], 0, 15);
            $availableBeats = collect();
        }

        return view('atps.beats', compact('atp', 'atpItems', 'availableBeats'));
    }

    /**
     * Add a beat to a tour plan.
     */
    public function addBeat(Request $request, ATP $atp): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'beat_id'      => 'required|exists:beats,id',
            'planned_time' => 'nullable|date',
            'description'  => 'nullable|string|max:500',
            'status'       => 'nullable|string|max:50',
        ]);

        try {
            $validated['atp_id'] = $atp->id;
            ATPItem::create($validated);

            return redirect()->route('atps.beats', $atp)->with('success', 'Beat added to tour plan successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('atps.beats', $atp)->with('error', 'Failed to add beat: ' . $e->getMessage());
        }
    }
}
