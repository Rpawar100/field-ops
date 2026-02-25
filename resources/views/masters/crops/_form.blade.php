{{-- Crop Form Partial — matches crops table schema --}}
<div class="row g-3">
    <div class="col-md-6">
        <label for="code" class="form-label">Crop Code <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $crop->code ?? '') }}" placeholder="Enter crop code" required>
        @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="name" class="form-label">Crop Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $crop->name ?? '') }}" placeholder="Enter crop name" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="local_name" class="form-label">Local Name</label>
        <input type="text" class="form-control @error('local_name') is-invalid @enderror" id="local_name" name="local_name" value="{{ old('local_name', $crop->local_name ?? '') }}" placeholder="Enter local/regional name">
        @error('local_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="crop_type" class="form-label">Crop Type <span class="text-danger">*</span></label>
        <select class="form-select @error('crop_type') is-invalid @enderror" id="crop_type" name="crop_type" required>
            <option value="">-- Select Crop Type --</option>
            @php $cropTypes = ['kharif' => 'Kharif', 'rabi' => 'Rabi', 'zaid' => 'Zaid', 'perennial' => 'Perennial']; @endphp
            @foreach($cropTypes as $value => $label)
                <option value="{{ $value }}" {{ old('crop_type', $crop->crop_type ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error('crop_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="typical_duration_days" class="form-label">Typical Duration (Days)</label>
        <input type="number" class="form-control @error('typical_duration_days') is-invalid @enderror" id="typical_duration_days" name="typical_duration_days" value="{{ old('typical_duration_days', $crop->typical_duration_days ?? '') }}" placeholder="e.g. 120" min="1">
        @error('typical_duration_days') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
            <option value="active" {{ old('status', $crop->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $crop->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-12">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="2" placeholder="Enter crop description">{{ old('description', $crop->description ?? '') }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
