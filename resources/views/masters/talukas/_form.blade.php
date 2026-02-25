{{-- Taluka Form Partial --}}
<div class="row g-3">
    <div class="col-md-6">
        <label for="name" class="form-label">Taluka Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $taluka->name ?? '') }}" placeholder="Enter taluka name" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="code" class="form-label">Taluka Code <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $taluka->code ?? '') }}" placeholder="Enter taluka code" required>
        @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="district_id" class="form-label">District <span class="text-danger">*</span></label>
        <select class="form-select @error('district_id') is-invalid @enderror" id="district_id" name="district_id" required>
            <option value="">-- Select District --</option>
            @foreach($districts ?? [] as $district)
                <option value="{{ $district->id }}" {{ old('district_id', $taluka->district_id ?? '') == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
            @endforeach
        </select>
        @error('district_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
            <option value="active" {{ old('status', $taluka->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $taluka->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
