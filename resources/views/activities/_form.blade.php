{{-- Activity Form Partial - Shared between Create and Edit --}}
<div class="row g-3">
    <div class="col-md-6">
        <label for="activity_type_id" class="form-label">Activity Type <span class="text-danger">*</span></label>
        <select class="form-select @error('activity_type_id') is-invalid @enderror"
                id="activity_type_id" name="activity_type_id" required>
            <option value="">-- Select Activity Type --</option>
            @foreach($activityTypes ?? [] as $type)
                <option value="{{ $type->id }}" {{ old('activity_type_id', $activity->activity_type_id ?? '') == $type->id ? 'selected' : '' }}>
                    {{ $type->name }}
                </option>
            @endforeach
        </select>
        @error('activity_type_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="user_id" class="form-label">Assigned User <span class="text-danger">*</span></label>
        <select class="form-select @error('user_id') is-invalid @enderror"
                id="user_id" name="user_id" required>
            <option value="">-- Select User --</option>
            @foreach($users ?? [] as $user)
                <option value="{{ $user->id }}" {{ old('user_id', $activity->user_id ?? '') == $user->id ? 'selected' : '' }}>
                    {{ $user->name ?? '' }} ({{ strtoupper($user->role ?? '') }})
                </option>
            @endforeach
        </select>
        @error('user_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="execution_date" class="form-label">Execution Date <span class="text-danger">*</span></label>
        <input type="date" class="form-control @error('execution_date') is-invalid @enderror"
               id="execution_date" name="execution_date"
               value="{{ old('execution_date', $activity->execution_date ?? '') }}" required>
        @error('execution_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="village_id" class="form-label">Village <span class="text-danger">*</span></label>
        <select class="form-select @error('village_id') is-invalid @enderror"
                id="village_id" name="village_id" required>
            <option value="">-- Select Village --</option>
            @foreach($villages ?? [] as $village)
                <option value="{{ $village->id }}" {{ old('village_id', $activity->village_id ?? '') == $village->id ? 'selected' : '' }}>
                    {{ $village->name }}
                </option>
            @endforeach
        </select>
        @error('village_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="beat_id" class="form-label">Beat</label>
        <select class="form-select @error('beat_id') is-invalid @enderror"
                id="beat_id" name="beat_id">
            <option value="">-- Select Beat --</option>
            @foreach($beats ?? [] as $beat)
                <option value="{{ $beat->id }}" {{ old('beat_id', $activity->beat_id ?? '') == $beat->id ? 'selected' : '' }}>
                    {{ $beat->name }}
                </option>
            @endforeach
        </select>
        @error('beat_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="farmer_id" class="form-label">Farmer</label>
        <select class="form-select @error('farmer_id') is-invalid @enderror"
                id="farmer_id" name="farmer_id">
            <option value="">-- Select Farmer (optional) --</option>
            @foreach($farmers ?? [] as $farmer)
                <option value="{{ $farmer->id }}" {{ old('farmer_id', $activity->farmer_id ?? '') == $farmer->id ? 'selected' : '' }}>
                    {{ $farmer->name }}
                </option>
            @endforeach
        </select>
        @error('farmer_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="retailer_id" class="form-label">Retailer</label>
        <select class="form-select @error('retailer_id') is-invalid @enderror"
                id="retailer_id" name="retailer_id">
            <option value="">-- Select Retailer (optional) --</option>
            @foreach($retailers ?? [] as $retailer)
                <option value="{{ $retailer->id }}" {{ old('retailer_id', $activity->retailer_id ?? '') == $retailer->id ? 'selected' : '' }}>
                    {{ $retailer->shop_name }}
                </option>
            @endforeach
        </select>
        @error('retailer_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12">
        <label for="remarks" class="form-label">Remarks</label>
        <textarea class="form-control @error('remarks') is-invalid @enderror"
                  id="remarks" name="remarks" rows="3"
                  placeholder="Enter remarks or notes">{{ old('remarks', $activity->remarks ?? '') }}</textarea>
        @error('remarks')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="status" class="form-label">Status</label>
        <select class="form-select @error('status') is-invalid @enderror"
                id="status" name="status">
            <option value="draft" {{ old('status', $activity->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="submitted" {{ old('status', $activity->status ?? '') === 'submitted' ? 'selected' : '' }}>Submitted</option>
            <option value="completed" {{ old('status', $activity->status ?? '') === 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="cancelled" {{ old('status', $activity->status ?? '') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
