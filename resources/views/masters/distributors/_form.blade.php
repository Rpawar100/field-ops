{{-- Distributor Form Partial — matches distributors table schema --}}
<div class="row g-3">
    {{-- Row 1: Code + Name --}}
    <div class="col-md-6">
        <label for="distributor_code" class="form-label">Distributor Code <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('distributor_code') is-invalid @enderror" id="distributor_code" name="distributor_code" value="{{ old('distributor_code', $distributor->distributor_code ?? '') }}" placeholder="Enter distributor code" required>
        @error('distributor_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="name" class="form-label">Distributor Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $distributor->name ?? '') }}" placeholder="Enter distributor name" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Row 2: Contact --}}
    <div class="col-md-4">
        <label for="contact_person" class="form-label">Contact Person</label>
        <input type="text" class="form-control @error('contact_person') is-invalid @enderror" id="contact_person" name="contact_person" value="{{ old('contact_person', $distributor->contact_person ?? '') }}" placeholder="Enter contact person name">
        @error('contact_person') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label for="mobile" class="form-label">Mobile <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('mobile') is-invalid @enderror" id="mobile" name="mobile" value="{{ old('mobile', $distributor->mobile ?? '') }}" placeholder="Enter mobile number" required maxlength="15">
        @error('mobile') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $distributor->email ?? '') }}" placeholder="Enter email address">
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Row 3: Type + ZRTH --}}
    <div class="col-md-6">
        <label for="type" class="form-label">Distributor Type <span class="text-danger">*</span></label>
        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
            <option value="">-- Select Type --</option>
            @php $distTypes = ['super_stockist' => 'Super Stockist', 'distributor' => 'Distributor', 'sub_distributor' => 'Sub Distributor']; @endphp
            @foreach($distTypes as $value => $label)
                <option value="{{ $value }}" {{ old('type', $distributor->type ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="zrth_hierarchy_id" class="form-label">ZRTH Hierarchy</label>
        <select class="form-select @error('zrth_hierarchy_id') is-invalid @enderror" id="zrth_hierarchy_id" name="zrth_hierarchy_id">
            <option value="">-- Select ZRTH --</option>
            @foreach($zrthHierarchies ?? [] as $zrth)
                <option value="{{ $zrth->id }}" {{ old('zrth_hierarchy_id', $distributor->zrth_hierarchy_id ?? '') == $zrth->id ? 'selected' : '' }}>{{ $zrth->name }} ({{ ucfirst($zrth->level) }})</option>
            @endforeach
        </select>
        @error('zrth_hierarchy_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Row 4: Address --}}
    <div class="col-md-12">
        <label for="address" class="form-label">Address</label>
        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2" placeholder="Enter full address">{{ old('address', $distributor->address ?? '') }}</textarea>
        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Row 5: Village + Pincode --}}
    <div class="col-md-6">
        <label for="village_id" class="form-label">Village</label>
        <select class="form-select @error('village_id') is-invalid @enderror" id="village_id" name="village_id">
            <option value="">-- Select Village --</option>
            @foreach($villages ?? [] as $village)
                <option value="{{ $village->id }}" {{ old('village_id', $distributor->village_id ?? '') == $village->id ? 'selected' : '' }}>{{ $village->name }}</option>
            @endforeach
        </select>
        @error('village_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="pincode" class="form-label">Pincode</label>
        <input type="text" class="form-control @error('pincode') is-invalid @enderror" id="pincode" name="pincode" value="{{ old('pincode', $distributor->pincode ?? '') }}" placeholder="Enter pincode" maxlength="6">
        @error('pincode') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Row 6: Tax --}}
    <div class="col-md-6">
        <label for="gst_number" class="form-label">GST Number</label>
        <input type="text" class="form-control @error('gst_number') is-invalid @enderror" id="gst_number" name="gst_number" value="{{ old('gst_number', $distributor->gst_number ?? '') }}" placeholder="Enter GST number" maxlength="15">
        @error('gst_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="pan_number" class="form-label">PAN Number</label>
        <input type="text" class="form-control @error('pan_number') is-invalid @enderror" id="pan_number" name="pan_number" value="{{ old('pan_number', $distributor->pan_number ?? '') }}" placeholder="Enter PAN number" maxlength="10">
        @error('pan_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Row 7: Credit --}}
    <div class="col-md-6">
        <label for="credit_limit" class="form-label">Credit Limit (INR)</label>
        <input type="number" step="0.01" class="form-control @error('credit_limit') is-invalid @enderror" id="credit_limit" name="credit_limit" value="{{ old('credit_limit', $distributor->credit_limit ?? '') }}" placeholder="Enter credit limit">
        @error('credit_limit') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="credit_days" class="form-label">Credit Days</label>
        <input type="number" class="form-control @error('credit_days') is-invalid @enderror" id="credit_days" name="credit_days" value="{{ old('credit_days', $distributor->credit_days ?? '') }}" placeholder="e.g. 30" min="0">
        @error('credit_days') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Row 8: Status --}}
    <div class="col-md-6">
        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
            <option value="active" {{ old('status', $distributor->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $distributor->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
