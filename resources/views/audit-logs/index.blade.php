@extends('layouts.admin')

@section('title', 'Audit Logs')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <style>
        .audit-detail-row {
            background-color: #f9fafb;
        }

        .audit-detail-row td {
            padding: 12px 20px;
        }

        .json-diff {
            font-family: 'Courier New', Courier, monospace;
            font-size: 0.8rem;
            max-height: 300px;
            overflow-y: auto;
            background: #f5f5f5;
            border-radius: 6px;
            padding: 12px;
        }

        .json-diff .diff-key {
            font-weight: 600;
            color: var(--body-font-color);
        }

        .json-diff .diff-old {
            color: #dc3545;
            text-decoration: line-through;
        }

        .json-diff .diff-new {
            color: #198754;
        }

        .expand-btn {
            cursor: pointer;
            transition: transform 0.2s;
        }

        .expand-btn.open {
            transform: rotate(90deg);
        }
    </style>
@endsection

@section('content')
    <div class="page-title-area">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h3>Audit Logs</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Audit Logs</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card mb-3">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('audit-logs.index') }}" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label small">From Date</label>
                    <input type="date" class="form-control form-control-sm" name="from_date"
                        value="{{ request('from_date') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">To Date</label>
                    <input type="date" class="form-control form-control-sm" name="to_date" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">User</label>
                    <select class="form-select form-select-sm" name="user_id">
                        <option value="">All Users</option>
                        @foreach($users ?? [] as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Event</label>
                    <select class="form-select form-select-sm" name="event">
                        <option value="">All Events</option>
                        <option value="created" {{ request('event') === 'created' ? 'selected' : '' }}>Created</option>
                        <option value="updated" {{ request('event') === 'updated' ? 'selected' : '' }}>Updated</option>
                        <option value="deleted" {{ request('event') === 'deleted' ? 'selected' : '' }}>Deleted</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Model Type</label>
                    <input type="text" class="form-control form-control-sm" name="auditable_type"
                        value="{{ request('auditable_type') }}" placeholder="e.g. User, Farmer...">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-theme btn-sm w-100"><i class="fas fa-filter me-1"></i>
                        Filter</button>
                </div>
            </form>
        </div>
    </div>

    {{-- DataTable --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="auditTable">
                    <thead>
                        <tr>
                            <th style="width:30px;"></th>
                            <th>Timestamp</th>
                            <th>User</th>
                            <th>Event</th>
                            <th>Model</th>
                            <th>ID</th>
                            <th>Changes Summary</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs ?? [] as $log)
                            <tr class="audit-row" data-log-id="{{ $log->id }}">
                                <td>
                                    <i class="fas fa-chevron-right expand-btn text-muted" data-target="detail-{{ $log->id }}"
                                        style="font-size:0.75rem;"></i>
                                </td>
                                <td class="text-muted">
                                    <small>{{ $log->created_at ? $log->created_at->format('d M Y H:i:s') : '-' }}</small>
                                </td>
                                <td>
                                    <span class="fw-medium">{{ $log->user->name ?? 'System' }}</span>
                                </td>
                                <td>
                                    @php
                                        $eventClass = match (strtolower($log->event ?? '')) {
                                            'created' => 'bg-success bg-opacity-10 text-success',
                                            'updated' => 'bg-primary bg-opacity-10 text-primary',
                                            'deleted' => 'bg-danger bg-opacity-10 text-danger',
                                            default => 'bg-secondary bg-opacity-10 text-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $eventClass }}">{{ ucfirst($log->event ?? '-') }}</span>
                                </td>
                                <td>
                                    @php
                                        $shortType = $log->auditable_type ? class_basename($log->auditable_type) : '-';
                                    @endphp
                                    <span class="badge bg-light text-dark">{{ $shortType }}</span>
                                </td>
                                <td><code>{{ $log->auditable_id ?? '-' }}</code></td>
                                <td class="text-truncate" style="max-width:280px;">
                                    @php
                                        $oldVals = is_string($log->old_values) ? json_decode($log->old_values, true) : ($log->old_values ?? []);
                                        $newVals = is_string($log->new_values) ? json_decode($log->new_values, true) : ($log->new_values ?? []);
                                        $changedKeys = array_unique(array_merge(array_keys($oldVals ?? []), array_keys($newVals ?? [])));
                                        $changeCount = count($changedKeys);
                                    @endphp
                                    @if($changeCount > 0)
                                        <span class="text-muted small">{{ $changeCount }} field{{ $changeCount !== 1 ? 's' : '' }}
                                            changed:
                                            {{ implode(', ', array_slice($changedKeys, 0, 3)) }}{{ $changeCount > 3 ? '...' : '' }}</span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                            </tr>
                            {{-- Expandable detail row (hidden by default) --}}
                            <tr class="audit-detail-row d-none" id="detail-{{ $log->id }}">
                                <td colspan="7">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="small fw-semibold text-danger mb-2"><i
                                                    class="fas fa-minus-circle me-1"></i>Old Values</h6>
                                            <div class="json-diff">
                                                @if(!empty($oldVals))
                                                    @foreach($oldVals as $key => $val)
                                                        <div><span class="diff-key">{{ $key }}:</span> <span
                                                                class="diff-old">{{ is_array($val) ? json_encode($val) : $val }}</span>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">No old values (new record)</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="small fw-semibold text-success mb-2"><i
                                                    class="fas fa-plus-circle me-1"></i>New Values</h6>
                                            <div class="json-diff">
                                                @if(!empty($newVals))
                                                    @foreach($newVals as $key => $val)
                                                        <div><span class="diff-key">{{ $key }}:</span> <span
                                                                class="diff-new">{{ is_array($val) ? json_encode($val) : $val }}</span>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">No new values (deleted record)</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox d-block mb-2" style="font-size:1.5rem;opacity:0.3;"></i>
                                    No audit logs found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Server-side pagination --}}
            @if(method_exists($logs ?? collect(), 'links'))
                <div class="mt-3">
                    {{ $logs->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            // DataTables init (client-side search only; paging via Laravel)
            $('#auditTable').DataTable({
                paging: false,
                info: false,
                searching: true,
                responsive: true,
                order: [[1, 'desc']],
                columnDefs: [
                    { orderable: false, targets: [0, 6] }
                ],
                language: {
                    emptyTable: "No audit logs found.",
                    search: "_INPUT_",
                    searchPlaceholder: "Search logs..."
                }
            });

            // Expandable row toggle
            $(document).on('click', '.expand-btn', function () {
                var targetId = $(this).data('target');
                var $detail = $('#' + targetId);
                $(this).toggleClass('open');
                $detail.toggleClass('d-none');
            });
        });
    </script>
@endsection