{{-- Zone Form Partial (zrth_hierarchies where level='zone') --}}
<div class="row g-3">
    <div class="col-md-6">
        <label for="name" class="form-label">Zone Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror"
               id="name" name="name"
               value="{{ old('name', $zone->name ?? '') }}"
               placeholder="Enter zone name" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="code" class="form-label">Zone Code <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('code') is-invalid @enderror"
               id="code" name="code"
               value="{{ old('code', $zone->code ?? '') }}"
               placeholder="Enter zone code (e.g. NZ, SZ)" required>
        @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control @error('description') is-invalid @enderror"
                  id="description" name="description" rows="2"
                  placeholder="Enter zone description">{{ old('description', $zone->description ?? '') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
            <option value="active" {{ old('status', $zone->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $zone->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <input type="hidden" name="level" value="zone">
</div>
