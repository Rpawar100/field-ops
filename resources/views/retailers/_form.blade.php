{{-- Retailer Form Partial - Shared between Create and Edit --}}
<div class="row g-3">
    @if(isset($retailer) && $retailer->exists)
    <div class="col-md-6">
        <label for="retailer_code" class="form-label">Retailer Code</label>
        <input type="text" class="form-control" id="retailer_code"
               value="{{ $retailer->retailer_code ?? '' }}" readonly disabled>
    </div>
    @endif

    <div class="col-md-6">
        <label for="shop_name" class="form-label">Shop Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('shop_name') is-invalid @enderror"
               id="shop_name" name="shop_name"
               value="{{ old('shop_name', $retailer->shop_name ?? '') }}"
               placeholder="Enter shop name" required>
        @error('shop_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="owner_name" class="form-label">Owner Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('owner_name') is-invalid @enderror"
               id="owner_name" name="owner_name"
               value="{{ old('owner_name', $retailer->owner_name ?? '') }}"
               placeholder="Enter owner name" required>
        @error('owner_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="firm_name" class="form-label">Firm Name</label>
        <input type="text" class="form-control @error('firm_name') is-invalid @enderror"
               id="firm_name" name="firm_name"
               value="{{ old('firm_name', $retailer->firm_name ?? '') }}"
               placeholder="Enter firm / company name">
        @error('firm_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="contact_person" class="form-label">Contact Person</label>
        <input type="text" class="form-control @error('contact_person') is-invalid @enderror"
               id="contact_person" name="contact_person"
               value="{{ old('contact_person', $retailer->contact_person ?? '') }}"
               placeholder="Enter contact person name">
        @error('contact_person')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="mobile" class="form-label">Mobile Number <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('mobile') is-invalid @enderror"
               id="mobile" name="mobile"
               value="{{ old('mobile', $retailer->mobile ?? '') }}"
               placeholder="Enter mobile number" required maxlength="15">
        @error('mobile')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror"
               id="email" name="email"
               value="{{ old('email', $retailer->email ?? '') }}"
               placeholder="Enter email address">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="business_type" class="form-label">Business Type <span class="text-danger">*</span></label>
        <select class="form-select @error('business_type') is-invalid @enderror"
                id="business_type" name="business_type" required>
            <option value="">-- Select Business Type --</option>
            @php
                $businessTypes = ['proprietorship' => 'Proprietorship', 'partnership' => 'Partnership', 'pvt_ltd' => 'Pvt Ltd', 'cooperative' => 'Cooperative', 'other' => 'Other'];
            @endphp
            @foreach($businessTypes as $val => $lbl)
                <option value="{{ $val }}" {{ old('business_type', $retailer->business_type ?? '') === $val ? 'selected' : '' }}>
                    {{ $lbl }}
                </option>
            @endforeach
        </select>
        @error('business_type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12">
        <label for="address" class="form-label">Address</label>
        <textarea class="form-control @error('address') is-invalid @enderror"
                  id="address" name="address" rows="2"
                  placeholder="Enter full address">{{ old('address', $retailer->address ?? '') }}</textarea>
        @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- SDTV Location --}}
    <div class="col-md-6">
        <label for="state_id" class="form-label">State</label>
        <select class="form-select @error('state_id') is-invalid @enderror" id="state_id" name="state_id">
            <option value="">-- Select State --</option>
            @foreach($states ?? [] as $state)
                <option value="{{ $state->id }}" {{ old('state_id', $selectedState ?? '') == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
            @endforeach
        </select>
        @error('state_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="district_id" class="form-label">District</label>
        <select class="form-select @error('district_id') is-invalid @enderror" id="district_id" name="district_id">
            <option value="">-- Select District --</option>
            @foreach($districts ?? [] as $district)
                <option value="{{ $district->id }}" {{ old('district_id', $selectedDistrict ?? '') == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
            @endforeach
        </select>
        @error('district_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="taluka_id" class="form-label">Taluka</label>
        <select class="form-select @error('taluka_id') is-invalid @enderror" id="taluka_id" name="taluka_id">
            <option value="">-- Select Taluka --</option>
            @foreach($talukas ?? [] as $taluka)
                <option value="{{ $taluka->id }}" {{ old('taluka_id', $selectedTaluka ?? '') == $taluka->id ? 'selected' : '' }}>{{ $taluka->name }}</option>
            @endforeach
        </select>
        @error('taluka_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="village_id" class="form-label">Village <span class="text-danger">*</span></label>
        <select class="form-select @error('village_id') is-invalid @enderror" id="village_id" name="village_id" required>
            <option value="">-- Select Village --</option>
            @foreach($villages ?? [] as $village)
                <option value="{{ $village->id }}" {{ old('village_id', $retailer->village_id ?? '') == $village->id ? 'selected' : '' }}>{{ $village->name }}</option>
            @endforeach
        </select>
        @error('village_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="beat_id" class="form-label">Beat</label>
        <select class="form-select @error('beat_id') is-invalid @enderror" id="beat_id" name="beat_id">
            <option value="">-- Select Beat --</option>
            @foreach($beats ?? [] as $beat)
                <option value="{{ $beat->id }}" {{ old('beat_id', $retailer->beat_id ?? '') == $beat->id ? 'selected' : '' }}>{{ $beat->name }}</option>
            @endforeach
        </select>
        @error('beat_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="gst_number" class="form-label">GST Number</label>
        <input type="text" class="form-control @error('gst_number') is-invalid @enderror"
               id="gst_number" name="gst_number"
               value="{{ old('gst_number', $retailer->gst_number ?? '') }}"
               placeholder="Enter GST number (e.g. 22AAAAA0000A1Z5)">
        @error('gst_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="pan_number" class="form-label">PAN Number</label>
        <input type="text" class="form-control @error('pan_number') is-invalid @enderror"
               id="pan_number" name="pan_number"
               value="{{ old('pan_number', $retailer->pan_number ?? '') }}"
               placeholder="Enter PAN number" maxlength="10">
        @error('pan_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="pesticide_license_number" class="form-label">Pesticide License No.</label>
        <input type="text" class="form-control @error('pesticide_license_number') is-invalid @enderror"
               id="pesticide_license_number" name="pesticide_license_number"
               value="{{ old('pesticide_license_number', $retailer->pesticide_license_number ?? '') }}"
               placeholder="Enter pesticide license number">
        @error('pesticide_license_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="retailer_grade" class="form-label">Retailer Grade</label>
        <select class="form-select @error('retailer_grade') is-invalid @enderror" id="retailer_grade" name="retailer_grade">
            <option value="">-- Select Grade --</option>
            @foreach(['A', 'B', 'C', 'D'] as $grade)
                <option value="{{ $grade }}" {{ old('retailer_grade', $retailer->retailer_grade ?? '') === $grade ? 'selected' : '' }}>Grade {{ $grade }}</option>
            @endforeach
        </select>
        @error('retailer_grade')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="status" class="form-label">Status</label>
        <select class="form-select @error('status') is-invalid @enderror"
                id="status" name="status">
            <option value="active" {{ old('status', $retailer->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $retailer->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
