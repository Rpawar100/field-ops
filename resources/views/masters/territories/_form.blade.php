{{-- Territory Form Partial (zrth_hierarchies where level='territory') --}}
<div class="row g-3">
    <div class="col-md-6">
        <label for="name" class="form-label">Territory Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $territory->name ?? '') }}" placeholder="Enter territory name" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="code" class="form-label">Territory Code <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $territory->code ?? '') }}" placeholder="Enter territory code" required>
        @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="parent_id" class="form-label">Region <span class="text-danger">*</span></label>
        <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id" required>
            <option value="">-- Select Region --</option>
            @foreach($regions ?? [] as $region)
                <option value="{{ $region->id }}" {{ old('parent_id', $territory->parent_id ?? '') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
            @endforeach
        </select>
        @error('parent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
            <option value="active" {{ old('status', $territory->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $territory->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-12">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control @error('description') is-invalid @enderror"
                  id="description" name="description" rows="2"
                  placeholder="Enter territory description">{{ old('description', $territory->description ?? '') }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <input type="hidden" name="level" value="territory">
</div>
