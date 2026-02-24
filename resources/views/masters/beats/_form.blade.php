{{-- Beat Form Partial — matches beats table schema --}}
<div class="row g-3">
    <div class="col-md-6">
        <label for="beat_code" class="form-label">Beat Code <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('beat_code') is-invalid @enderror" id="beat_code" name="beat_code" value="{{ old('beat_code', $beat->beat_code ?? '') }}" placeholder="Enter beat code" required>
        @error('beat_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="name" class="form-label">Beat Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $beat->name ?? '') }}" placeholder="Enter beat name" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-12">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="2" placeholder="Enter beat description">{{ old('description', $beat->description ?? '') }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="zrth_hierarchy_id" class="form-label">Headquarters (HQ) <span class="text-danger">*</span></label>
        <select class="form-select @error('zrth_hierarchy_id') is-invalid @enderror" id="zrth_hierarchy_id" name="zrth_hierarchy_id" required>
            <option value="">-- Select Headquarters --</option>
            @foreach($headquarters ?? [] as $hq)
                <option value="{{ $hq->id }}" {{ old('zrth_hierarchy_id', $beat->zrth_hierarchy_id ?? '') == $hq->id ? 'selected' : '' }}>{{ $hq->name }}</option>
            @endforeach
        </select>
        @error('zrth_hierarchy_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="village_id" class="form-label">Primary Village</label>
        <select class="form-select @error('village_id') is-invalid @enderror" id="village_id" name="village_id">
            <option value="">-- Select Village --</option>
            @foreach($villages ?? [] as $village)
                <option value="{{ $village->id }}" {{ old('village_id', $beat->village_id ?? '') == $village->id ? 'selected' : '' }}>{{ $village->name }}</option>
            @endforeach
        </select>
        @error('village_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="beat_day" class="form-label">Beat Day <span class="text-danger">*</span></label>
        <select class="form-select @error('beat_day') is-invalid @enderror" id="beat_day" name="beat_day" required>
            <option value="">-- Select Day --</option>
            @php $days = ['monday' => 'Monday', 'tuesday' => 'Tuesday', 'wednesday' => 'Wednesday', 'thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday', 'sunday' => 'Sunday']; @endphp
            @foreach($days as $value => $label)
                <option value="{{ $value }}" {{ old('beat_day', $beat->beat_day ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error('beat_day') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="visit_frequency_days" class="form-label">Visit Frequency (Days)</label>
        <input type="number" class="form-control @error('visit_frequency_days') is-invalid @enderror" id="visit_frequency_days" name="visit_frequency_days" value="{{ old('visit_frequency_days', $beat->visit_frequency_days ?? '') }}" placeholder="e.g. 7" min="1">
        @error('visit_frequency_days') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
            <option value="active" {{ old('status', $beat->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $beat->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
