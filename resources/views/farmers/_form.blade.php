{{-- Farmer Form Partial - Shared between Create and Edit --}}
<div class="row g-3">
    @if(isset($farmer) && $farmer->exists)
    <div class="col-md-6">
        <label for="farmer_code" class="form-label">Farmer Code</label>
        <input type="text" class="form-control" id="farmer_code"
               value="{{ $farmer->farmer_code ?? '' }}" readonly disabled>
    </div>
    @endif

    <div class="col-md-6">
        <label for="name" class="form-label">Farmer Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror"
               id="name" name="name"
               value="{{ old('name', $farmer->name ?? '') }}"
               placeholder="Enter farmer name" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="father_name" class="form-label">Father's Name</label>
        <input type="text" class="form-control @error('father_name') is-invalid @enderror"
               id="father_name" name="father_name"
               value="{{ old('father_name', $farmer->father_name ?? '') }}"
               placeholder="Enter father's name">
        @error('father_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="mobile" class="form-label">Mobile Number <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('mobile') is-invalid @enderror"
               id="mobile" name="mobile"
               value="{{ old('mobile', $farmer->mobile ?? '') }}"
               placeholder="Enter mobile number" required maxlength="15">
        @error('mobile')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="gender" class="form-label">Gender</label>
        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
            <option value="">-- Select Gender --</option>
            <option value="male" {{ old('gender', $farmer->gender ?? '') === 'male' ? 'selected' : '' }}>Male</option>
            <option value="female" {{ old('gender', $farmer->gender ?? '') === 'female' ? 'selected' : '' }}>Female</option>
            <option value="other" {{ old('gender', $farmer->gender ?? '') === 'other' ? 'selected' : '' }}>Other</option>
        </select>
        @error('gender')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="date_of_birth" class="form-label">Date of Birth</label>
        <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
               id="date_of_birth" name="date_of_birth"
               value="{{ old('date_of_birth', $farmer->date_of_birth ?? '') }}">
        @error('date_of_birth')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="farmer_type" class="form-label">Farmer Type <span class="text-danger">*</span></label>
        <select class="form-select @error('farmer_type') is-invalid @enderror" id="farmer_type" name="farmer_type" required>
            <option value="">-- Select Type --</option>
            @php $farmerTypes = ['pda' => 'PDA', 'demo' => 'Demo', 'user' => 'User', 'non_user' => 'Non-User']; @endphp
            @foreach($farmerTypes as $val => $lbl)
                <option value="{{ $val }}" {{ old('farmer_type', $farmer->farmer_type ?? '') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
            @endforeach
        </select>
        @error('farmer_type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Cascading SDTV Dropdowns --}}
    <div class="col-md-6">
        <label for="state_id" class="form-label">State <span class="text-danger">*</span></label>
        <select class="form-select @error('state_id') is-invalid @enderror" id="state_id" name="state_id" required>
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
        <label for="district_id" class="form-label">District <span class="text-danger">*</span></label>
        <select class="form-select @error('district_id') is-invalid @enderror" id="district_id" name="district_id" required>
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
        <label for="taluka_id" class="form-label">Taluka <span class="text-danger">*</span></label>
        <select class="form-select @error('taluka_id') is-invalid @enderror" id="taluka_id" name="taluka_id" required>
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
                <option value="{{ $village->id }}" {{ old('village_id', $farmer->village_id ?? '') == $village->id ? 'selected' : '' }}>{{ $village->name }}</option>
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
                <option value="{{ $beat->id }}" {{ old('beat_id', $farmer->beat_id ?? '') == $beat->id ? 'selected' : '' }}>{{ $beat->name }}</option>
            @endforeach
        </select>
        @error('beat_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="total_landholding_acres" class="form-label">Total Landholding (acres)</label>
        <input type="number" step="0.01" class="form-control @error('total_landholding_acres') is-invalid @enderror"
               id="total_landholding_acres" name="total_landholding_acres"
               value="{{ old('total_landholding_acres', $farmer->total_landholding_acres ?? '') }}"
               placeholder="Enter total landholding">
        @error('total_landholding_acres')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="cultivable_land_acres" class="form-label">Cultivable Land (acres)</label>
        <input type="number" step="0.01" class="form-control @error('cultivable_land_acres') is-invalid @enderror"
               id="cultivable_land_acres" name="cultivable_land_acres"
               value="{{ old('cultivable_land_acres', $farmer->cultivable_land_acres ?? '') }}"
               placeholder="Enter cultivable land">
        @error('cultivable_land_acres')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12">
        <label for="crops" class="form-label">Crops Grown</label>
        <select class="form-select @error('crops') is-invalid @enderror" id="crops" name="crops[]" multiple>
            @foreach($crops ?? [] as $crop)
                <option value="{{ $crop->id }}"
                    {{ in_array($crop->id, old('crops', isset($farmer) && $farmer->farmerCrops ? $farmer->farmerCrops->pluck('crop_id')->toArray() : [])) ? 'selected' : '' }}>
                    {{ $crop->name }}
                </option>
            @endforeach
        </select>
        <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple crops (via farmer_crops pivot)</small>
        @error('crops')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="status" class="form-label">Status</label>
        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
            <option value="active" {{ old('status', $farmer->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $farmer->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
