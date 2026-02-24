{{-- Headquarters Form Partial (zrth_hierarchies where level='hq') --}}
<div class="row g-3">
    <div class="col-md-6">
        <label for="name" class="form-label">Headquarters Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $headquarters->name ?? '') }}" placeholder="Enter headquarters name" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="code" class="form-label">Headquarters Code <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $headquarters->code ?? '') }}" placeholder="Enter headquarters code" required>
        @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="parent_id" class="form-label">Territory <span class="text-danger">*</span></label>
        <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id" required>
            <option value="">-- Select Territory --</option>
            @foreach($territories ?? [] as $territory)
                <option value="{{ $territory->id }}" {{ old('parent_id', $headquarters->parent_id ?? '') == $territory->id ? 'selected' : '' }}>{{ $territory->name }}</option>
            @endforeach
        </select>
        @error('parent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
            <option value="active" {{ old('status', $headquarters->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $headquarters->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-12">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control @error('description') is-invalid @enderror"
                  id="description" name="description" rows="2"
                  placeholder="Enter headquarters description">{{ old('description', $headquarters->description ?? '') }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <input type="hidden" name="level" value="hq">
</div>
