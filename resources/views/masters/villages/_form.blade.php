{{-- Village Form Partial — matches villages table schema --}}
<div class="row g-3">
    <div class="col-md-6">
        <label for="code" class="form-label">Village Code <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $village->code ?? '') }}" placeholder="Enter village code" required>
        @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="name" class="form-label">Village Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $village->name ?? '') }}" placeholder="Enter village name" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="taluka_id" class="form-label">Taluka <span class="text-danger">*</span></label>
        <select class="form-select @error('taluka_id') is-invalid @enderror" id="taluka_id" name="taluka_id" required>
            <option value="">-- Select Taluka --</option>
            @foreach($talukas ?? [] as $taluka)
                <option value="{{ $taluka->id }}" {{ old('taluka_id', $village->taluka_id ?? '') == $taluka->id ? 'selected' : '' }}>{{ $taluka->name }}</option>
            @endforeach
        </select>
        @error('taluka_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="pincode" class="form-label">Pincode</label>
        <input type="text" class="form-control @error('pincode') is-invalid @enderror" id="pincode" name="pincode" value="{{ old('pincode', $village->pincode ?? '') }}" placeholder="Enter pincode" maxlength="6">
        @error('pincode') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="latitude" class="form-label">Latitude</label>
        <input type="text" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude', $village->latitude ?? '') }}" placeholder="e.g. 19.0760">
        @error('latitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="longitude" class="form-label">Longitude</label>
        <input type="text" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude', $village->longitude ?? '') }}" placeholder="e.g. 72.8777">
        @error('longitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
            <option value="active" {{ old('status', $village->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $village->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
