<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\DemoMaster;
use App\Models\DemoDistribution;
use App\Models\DemoFarmerAssignment;
use App\Models\DemoStageActivity;
use App\Models\User;
use App\Models\Farmer;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class DemoController extends Controller
{
    /**
     * Display the demo management overview.
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        try {
            $demoLots = DemoMaster::with(['product', 'crop'])
                ->orderByDesc('created_at')
                ->paginate(15);

            $stats = [
                'total_lots'    => DemoMaster::count(),
                'distributed'   => DemoDistribution::count(),
                'executed'      => DemoFarmerAssignment::count(),
                'reconciled'    => DemoDistribution::whereIn('status', ['acknowledged', 'reconciled'])->count(),
            ];
        } catch (\Throwable $e) {
            $demoLots = new LengthAwarePaginator([], 0, 15);
            $stats = [
                'total_lots'    => 0,
                'distributed'   => 0,
                'executed'      => 0,
                'reconciled'    => 0,
            ];
        }

        return view('demo.index', compact('demoLots', 'stats'));
    }

    /**
     * Display the demo distribution page.
     */
    public function distribution(): \Illuminate\Contracts\View\View
    {
        try {
            $distributions = DemoDistribution::with(['demoMaster', 'distributedByUser'])
                ->orderByDesc('distribution_date')
                ->paginate(15);
            $demoMasters = DemoMaster::whereIn('status', ['active', 'available', 'partially_distributed'])->get(['id', 'lot_no', 'product_name']);
            $users = User::where('status', 'active')->orderBy('name')->get(['id', 'name']);
        } catch (\Throwable $e) {
            $distributions = new LengthAwarePaginator([], 0, 15);
            $demoMasters = collect();
            $users = collect();
        }

        return view('demo.distribution', compact('distributions', 'demoMasters', 'users'));
    }

    /**
     * Store a new demo distribution.
     */
    public function storeDistribution(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'demo_master_id'   => 'required|exists:demo_masters,id',
            'from_location_type' => 'nullable|string|max:100',
            'from_zrth_id'     => 'nullable|exists:zrth_hierarchies,id',
            'from_user_id'     => 'nullable|exists:users,id',
            'to_location_type' => 'nullable|string|max:100',
            'to_zrth_id'       => 'nullable|exists:zrth_hierarchies,id',
            'to_user_id'       => 'nullable|exists:users,id',
            'to_farmer_id'     => 'nullable|exists:farmers,id',
            'distribution_date' => 'required|date',
            'quantity'         => 'required|numeric|min:0',
            'quantity_unit'    => 'required|string|max:50',
            'receiver_name'    => 'nullable|string|max:255',
            'receiver_mobile'  => 'nullable|string|max:15',
            'challan_number'   => 'nullable|string|max:100',
            'remarks'          => 'nullable|string|max:2000',
            'status'           => 'nullable|string|in:pending,in_transit,delivered,acknowledged,cancelled',
        ]);

        try {
            $validated['uuid'] = \Illuminate\Support\Str::uuid()->toString();
            $validated['distributed_by'] = auth()->id();
            if (empty($validated['status'])) {
                $validated['status'] = 'pending';
            }

            DemoDistribution::create($validated);

            return redirect()->route('demo.distribution')->with('success', 'Demo distribution created successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create distribution: ' . $e->getMessage());
        }
    }

    /**
     * Display the demo execution tracking page (farmer assignments + stage activities).
     */
    public function execution(): \Illuminate\Contracts\View\View
    {
        try {
            $executions = DemoFarmerAssignment::with([
                'demoMaster.crop', 'farmer', 'assignedByUser',
            ])
                ->orderByDesc('assignment_date')
                ->paginate(15);

            // Compute stage counts for the pipeline
            $stageCounts = [
                'Sowing'      => DemoFarmerAssignment::where('current_stage', 1)->count(),
                'Germination' => DemoFarmerAssignment::where('current_stage', 2)->count(),
                'Vegetative'  => DemoFarmerAssignment::where('current_stage', 3)->count(),
                'Flowering'   => DemoFarmerAssignment::where('current_stage', 4)->count(),
                'Harvest'     => DemoFarmerAssignment::where('current_stage', 5)->count(),
                'Completed'   => DemoFarmerAssignment::where('status', 'completed')->count(),
            ];
        } catch (\Throwable $e) {
            $executions = new LengthAwarePaginator([], 0, 15);
            $stageCounts = [];
        }

        return view('demo.execution', compact('executions', 'stageCounts'));
    }

    /**
     * Display the demo reconciliation page.
     */
    public function reconciliation(): \Illuminate\Contracts\View\View
    {
        try {
            $distributions = DemoDistribution::with(['demoMaster', 'farmerAssignments'])
                ->whereIn('status', ['delivered', 'acknowledged'])
                ->orderByDesc('distribution_date')
                ->paginate(15);
        } catch (\Throwable $e) {
            $distributions = new LengthAwarePaginator([], 0, 15);
        }

        return view('demo.reconciliation', compact('distributions'));
    }

    /**
     * Display a specific demo master with its details.
     */
    public function show(int $demo): \Illuminate\Contracts\View\View
    {
        try {
            $demoObj = DemoMaster::with([
                'product', 'crop', 'variety',
                'distributions.distributedByUser',
                'farmerAssignments.farmer',
            ])
                ->findOrFail($demo);

            $assignments = $demoObj->farmerAssignments;
            $stages = DemoStageActivity::whereNotNull('demo_farmer_assignment_id')
                ->whereHas('farmerAssignment', function ($q) use ($demo) {
                    $q->where('demo_master_id', $demo);
                })
                ->with('recordedByUser')
                ->get();
            $photos = collect(); // No photos table relationship needed for now
        } catch (\Throwable $e) {
            abort(404);
        }

        // The view uses $demo as the variable name
        return view('demo.show', [
            'demo'        => $demoObj,
            'assignments' => $assignments ?? collect(),
            'stages'      => $stages ?? collect(),
            'photos'      => $photos ?? collect(),
        ]);
    }
}
