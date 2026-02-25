<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class StateController extends Controller
{
    /**
     * Display a listing of states.
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        try {
            $items = State::withCount('districts')
                ->orderBy('name')
                ->paginate(15);
        } catch (\Throwable $e) {
            $items = new LengthAwarePaginator([], 0, 15);
        }

        $states = $items;
        return view('masters.states.index', compact('states'));
    }

    /**
     * Show the form for creating a new state.
     */
    public function create(): \Illuminate\Contracts\View\View
    {
        return view('masters.states.create');
    }

    /**
     * Store a newly created state.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'code'   => 'required|string|max:50|unique:states,code',
            'status' => 'required|string|in:active,inactive',
        ]);

        try {
            State::create($validated);

            return redirect()->route('states.index')->with('success', 'State created successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create state: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified state.
     */
    public function show(State $state): \Illuminate\Contracts\View\View
    {
        try {
            $state->load('districts');
        } catch (\Throwable $e) {
            // Continue with base state object
        }

        return view('masters.states.show', compact('state'));
    }

    /**
     * Show the form for editing the specified state.
     */
    public function edit(State $state): \Illuminate\Contracts\View\View
    {
        return view('masters.states.edit', compact('state'));
    }

    /**
     * Update the specified state.
     */
    public function update(Request $request, State $state): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'code'   => 'required|string|max:50|unique:states,code,' . $state->id,
            'status' => 'required|string|in:active,inactive',
        ]);

        try {
            $state->update($validated);

            return redirect()->route('states.index')->with('success', 'State updated successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update state: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified state (soft delete).
     */
    public function destroy(State $state): \Illuminate\Http\RedirectResponse
    {
        try {
            $state->delete();

            return redirect()->route('states.index')->with('success', 'State deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('states.index')->with('error', 'Failed to delete state: ' . $e->getMessage());
        }
    }
}
