<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Crop;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class CropController extends Controller
{
    /**
     * Display a listing of crops.
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        try {
            $items = Crop::withCount(['products', 'varieties'])
                ->orderBy('name')
                ->paginate(15);
        } catch (\Throwable $e) {
            $items = new LengthAwarePaginator([], 0, 15);
        }

        $crops = $items;
        return view('masters.crops.index', compact('crops'));
    }

    /**
     * Show the form for creating a new crop.
     */
    public function create(): \Illuminate\Contracts\View\View
    {
        return view('masters.crops.create');
    }

    /**
     * Store a newly created crop.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'code'                 => 'required|string|max:50|unique:crops,code',
            'name'                 => 'required|string|max:255',
            'local_name'           => 'nullable|string|max:255',
            'crop_type'            => 'required|string|in:kharif,rabi,zaid,perennial',
            'typical_duration_days' => 'nullable|integer|min:1',
            'description'          => 'nullable|string',
            'image_path'           => 'nullable|string|max:500',
            'status'               => 'required|string|in:active,inactive',
        ]);

        try {
            Crop::create($validated);

            return redirect()->route('crops.index')->with('success', 'Crop created successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create crop: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified crop.
     */
    public function show(Crop $crop): \Illuminate\Contracts\View\View
    {
        try {
            $crop->load(['products', 'varieties']);
        } catch (\Throwable $e) {
            // Continue with base crop object
        }

        return view('masters.crops.show', compact('crop'));
    }

    /**
     * Show the form for editing the specified crop.
     */
    public function edit(Crop $crop): \Illuminate\Contracts\View\View
    {
        return view('masters.crops.edit', compact('crop'));
    }

    /**
     * Update the specified crop.
     */
    public function update(Request $request, Crop $crop): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'code'                 => 'required|string|max:50|unique:crops,code,' . $crop->id,
            'name'                 => 'required|string|max:255',
            'local_name'           => 'nullable|string|max:255',
            'crop_type'            => 'required|string|in:kharif,rabi,zaid,perennial',
            'typical_duration_days' => 'nullable|integer|min:1',
            'description'          => 'nullable|string',
            'image_path'           => 'nullable|string|max:500',
            'status'               => 'required|string|in:active,inactive',
        ]);

        try {
            $crop->update($validated);

            return redirect()->route('crops.index')->with('success', 'Crop updated successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update crop: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified crop (soft delete).
     */
    public function destroy(Crop $crop): \Illuminate\Http\RedirectResponse
    {
        try {
            $crop->delete();

            return redirect()->route('crops.index')->with('success', 'Crop deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('crops.index')->with('error', 'Failed to delete crop: ' . $e->getMessage());
        }
    }
}
