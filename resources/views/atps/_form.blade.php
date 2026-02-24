{{-- ATP Form Partial - Shared between Create and Edit --}}
<div class="row g-3">
    <div class="col-md-6">
        <label for="month" class="form-label">Month <span class="text-danger">*</span></label>
        <input type="month" class="form-control @error('month') is-invalid @enderror"
               id="month" name="month"
               value="{{ old('month', $atp->month ?? '') }}" required>
        @error('month')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="user_id" class="form-label">Field Assistant <span class="text-danger">*</span></label>
        <select class="form-select @error('user_id') is-invalid @enderror"
                id="user_id" name="user_id" required>
            <option value="">-- Select Field Assistant --</option>
            @foreach($users ?? [] as $user)
                <option value="{{ $user->id }}" {{ old('user_id', $atp->user_id ?? '') == $user->id ? 'selected' : '' }}>
                    {{ $user->name ?? '' }} ({{ strtoupper($user->role ?? '') }})
                </option>
            @endforeach
        </select>
        @error('user_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="status" class="form-label">Status</label>
        <select class="form-select @error('status') is-invalid @enderror"
                id="status" name="status">
            <option value="draft" {{ old('status', $atp->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="submitted" {{ old('status', $atp->status ?? '') === 'submitted' ? 'selected' : '' }}>Submitted</option>
            <option value="approved" {{ old('status', $atp->status ?? '') === 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="rejected" {{ old('status', $atp->status ?? '') === 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12">
        <label class="form-label">Beat Assignments</label>
        <div class="card bg-light border">
            <div class="card-body">
                @if(isset($beats) && $beats->count() > 0)
                    <div class="row g-2">
                        @foreach($beats as $beat)
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="beats[]"
                                       value="{{ $beat->id }}" id="beat_{{ $beat->id }}"
                                       {{ in_array($beat->id, old('beats', isset($atp) && $atp->beats ? $atp->beats->pluck('id')->toArray() : [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="beat_{{ $beat->id }}">
                                    <span class="text-muted small">[{{ $beat->beat_code }}]</span> {{ $beat->name }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0 text-center">No beats available. Please create beats first.</p>
                @endif
            </div>
        </div>
        @error('beats')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12">
        <label for="notes" class="form-label">Notes</label>
        <textarea class="form-control @error('notes') is-invalid @enderror"
                  id="notes" name="notes" rows="3"
                  placeholder="Additional notes for this tour plan">{{ old('notes', $atp->notes ?? '') }}</textarea>
        @error('notes')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
