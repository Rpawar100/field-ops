{{-- Region Form Partial (zrth_hierarchies where level='region') --}}
<div class="row g-3">
    <div class="col-md-6">
        <label for="name" class="form-label">Region Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror"
               id="name" name="name" value="{{ old('name', $region->name ?? '') }}"
               placeholder="Enter region name" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="code" class="form-label">Region Code <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('code') is-invalid @enderror"
               id="code" name="code" value="{{ old('code', $region->code ?? '') }}"
               placeholder="Enter region code (e.g. NR, SR)" required>
        @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="parent_id" class="form-label">Zone <span class="text-danger">*</span></label>
        <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id" required>
            <option value="">-- Select Zone --</option>
            @foreach($zones ?? [] as $zone)
                <option value="{{ $zone->id }}" {{ old('parent_id', $region->parent_id ?? '') == $zone->id ? 'selected' : '' }}>{{ $zone->name }}</option>
            @endforeach
        </select>
        @error('parent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
            <option value="active" {{ old('status', $region->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $region->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-12">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control @error('description') is-invalid @enderror"
                  id="description" name="description" rows="2"
                  placeholder="Enter region description">{{ old('description', $region->description ?? '') }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <input type="hidden" name="level" value="region">
</div>
