<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Pagination\LengthAwarePaginator;

class AuditLogController extends Controller
{
    /**
     * Display a listing of audit logs.
     * Schema: audit_logs has user_id, auditable_type, auditable_id, event,
     *         old_values (JSON), new_values (JSON), ip_address, user_agent, url.
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        try {
            $logs = AuditLog::with('user')
                ->orderByDesc('created_at')
                ->paginate(25);

            $users = \App\Models\User::orderBy('name')->get(['id', 'name']);
        } catch (\Throwable $e) {
            $logs = new LengthAwarePaginator([], 0, 25);
            $users = collect();
        }

        return view('audit-logs.index', compact('logs', 'users'));
    }
}
