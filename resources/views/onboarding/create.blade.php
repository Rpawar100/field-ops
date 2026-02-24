@extends('layouts.admin')

@section('title', 'New Onboarding Application')

@section('styles')
<style>
    .wizard-steps { display: flex; justify-content: center; margin-bottom: 2rem; }
    .wizard-step { text-align: center; flex: 1; position: relative; }
    .wizard-step .step-circle {
        width: 40px; height: 40px; border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        font-weight: 600; font-size: 0.9rem; border: 2px solid #dee2e6;
        color: #6c757d; background: #fff; transition: all 0.3s;
    }
    .wizard-step.active .step-circle { border-color: var(--theme-default); color: #fff; background: var(--theme-default); }
    .wizard-step.completed .step-circle { border-color: #198754; color: #fff; background: #198754; }
    .wizard-step .step-label { display: block; font-size: 0.78rem; margin-top: 6px; color: #6c757d; }
    .wizard-step.active .step-label { color: var(--theme-default); font-weight: 500; }
    .wizard-panel { display: none; }
    .wizard-panel.active { display: block; }
</style>
@endsection

@section('content')
<div class="page-title-area">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h3>New Onboarding Application</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('onboarding.index') }}">Onboarding</a></li>
                    <li class="breadcrumb-item active">New Application</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h6 class="mb-0 fw-semibold">Onboarding Application</h6>
    </div>
    <div class="card-body">
        {{-- Wizard Steps --}}
        <div class="wizard-steps">
            <div class="wizard-step active" data-step="1">
                <span class="step-circle">1</span>
                <span class="step-label">Personal Info</span>
            </div>
            <div class="wizard-step" data-step="2">
                <span class="step-circle">2</span>
                <span class="step-label">KYC Documents</span>
            </div>
            <div class="wizard-step" data-step="3">
                <span class="step-circle">3</span>
                <span class="step-label">ZRTH Assignment</span>
            </div>
        </div>

        <form method="POST" action="{{ route('onboarding.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Step 1: Personal Info (from users table) --}}
            <div class="wizard-panel active" id="step-1">
                <h6 class="fw-semibold text-muted mb-3">Step 1: Personal Information</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name') }}"
                               placeholder="Enter full name" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="mobile" class="form-label">Mobile <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('mobile') is-invalid @enderror"
                               id="mobile" name="mobile" value="{{ old('mobile') }}"
                               placeholder="Enter mobile number" required maxlength="15">
                        @error('mobile') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}"
                               placeholder="Enter email address">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-select @error('role') is-invalid @enderror"
                                id="role" name="role" required>
                            <option value="">-- Select Role --</option>
                            @php $roles = ['nsm' => 'NSM', 'zm' => 'ZM', 'rm' => 'RM', 'tsl' => 'TSL', 'fa' => 'FA']; @endphp
                            @foreach($roles as $val => $lbl)
                                <option value="{{ $val }}" {{ old('role') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="designation" class="form-label">Designation</label>
                        <input type="text" class="form-control @error('designation') is-invalid @enderror"
                               id="designation" name="designation" value="{{ old('designation') }}"
                               placeholder="Enter designation (e.g. Field Assistant)" maxlength="100">
                        @error('designation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="fa_type" class="form-label">FA Type</label>
                        <select class="form-select @error('fa_type') is-invalid @enderror"
                                id="fa_type" name="fa_type">
                            <option value="">-- Select FA Type --</option>
                            <option value="permanent" {{ old('fa_type') === 'permanent' ? 'selected' : '' }}>Permanent</option>
                            <option value="temporary" {{ old('fa_type') === 'temporary' ? 'selected' : '' }}>Temporary</option>
                        </select>
                        @error('fa_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="date_of_joining" class="form-label">Date of Joining</label>
                        <input type="date" class="form-control @error('date_of_joining') is-invalid @enderror"
                               id="date_of_joining" name="date_of_joining" value="{{ old('date_of_joining') }}">
                        @error('date_of_joining') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-12">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror"
                                  id="address" name="address" rows="2"
                                  placeholder="Enter full address">{{ old('address') }}</textarea>
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="mt-4 text-end">
                    <button type="button" class="btn btn-theme" onclick="showStep(2)">
                        Next <i class="fas fa-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>

            {{-- Step 2: KYC Documents (from fa_onboarding_details) --}}
            <div class="wizard-panel" id="step-2">
                <h6 class="fw-semibold text-muted mb-3">Step 2: KYC Documents</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="pan_number" class="form-label">PAN Number</label>
                        <input type="text" class="form-control @error('pan_number') is-invalid @enderror"
                               id="pan_number" name="pan_number" value="{{ old('pan_number') }}"
                               placeholder="Enter PAN number" maxlength="10">
                        @error('pan_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="pan_document" class="form-label">PAN Document</label>
                        <input type="file" class="form-control @error('pan_document') is-invalid @enderror"
                               id="pan_document" name="pan_document" accept="image/*,.pdf">
                        @error('pan_document') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="aadhaar_number" class="form-label">Aadhaar Number</label>
                        <input type="text" class="form-control @error('aadhaar_number') is-invalid @enderror"
                               id="aadhaar_number" name="aadhaar_number" value="{{ old('aadhaar_number') }}"
                               placeholder="Enter Aadhaar number" maxlength="12">
                        @error('aadhaar_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="aadhaar_document" class="form-label">Aadhaar Document</label>
                        <input type="file" class="form-control @error('aadhaar_document') is-invalid @enderror"
                               id="aadhaar_document" name="aadhaar_document" accept="image/*,.pdf">
                        @error('aadhaar_document') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="driving_license_number" class="form-label">Driving License Number</label>
                        <input type="text" class="form-control @error('driving_license_number') is-invalid @enderror"
                               id="driving_license_number" name="driving_license_number"
                               value="{{ old('driving_license_number') }}"
                               placeholder="Enter DL number">
                        @error('driving_license_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="driving_license_document" class="form-label">DL Document</label>
                        <input type="file" class="form-control @error('driving_license_document') is-invalid @enderror"
                               id="driving_license_document" name="driving_license_document" accept="image/*,.pdf">
                        @error('driving_license_document') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="qualification" class="form-label">Qualification</label>
                        <input type="text" class="form-control @error('qualification') is-invalid @enderror"
                               id="qualification" name="qualification" value="{{ old('qualification') }}"
                               placeholder="Enter highest qualification">
                        @error('qualification') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="qualification_document" class="form-label">Qualification Certificate</label>
                        <input type="file" class="form-control @error('qualification_document') is-invalid @enderror"
                               id="qualification_document" name="qualification_document" accept="image/*,.pdf">
                        @error('qualification_document') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-12">
                        <label for="experience_details" class="form-label">Experience Details</label>
                        <textarea class="form-control @error('experience_details') is-invalid @enderror"
                                  id="experience_details" name="experience_details" rows="2"
                                  placeholder="Previous work experience">{{ old('experience_details') }}</textarea>
                        @error('experience_details') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Banking Details --}}
                    <div class="col-12"><hr><h6 class="fw-semibold text-muted">Banking Details</h6></div>
                    <div class="col-md-6">
                        <label for="bank_name" class="form-label">Bank Name</label>
                        <input type="text" class="form-control @error('bank_name') is-invalid @enderror"
                               id="bank_name" name="bank_name" value="{{ old('bank_name') }}"
                               placeholder="Enter bank name">
                        @error('bank_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="bank_branch" class="form-label">Branch</label>
                        <input type="text" class="form-control @error('bank_branch') is-invalid @enderror"
                               id="bank_branch" name="bank_branch" value="{{ old('bank_branch') }}"
                               placeholder="Enter branch name">
                        @error('bank_branch') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="bank_account_number" class="form-label">Account Number</label>
                        <input type="text" class="form-control @error('bank_account_number') is-invalid @enderror"
                               id="bank_account_number" name="bank_account_number"
                               value="{{ old('bank_account_number') }}"
                               placeholder="Enter account number">
                        @error('bank_account_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="ifsc_code" class="form-label">IFSC Code</label>
                        <input type="text" class="form-control @error('ifsc_code') is-invalid @enderror"
                               id="ifsc_code" name="ifsc_code" value="{{ old('ifsc_code') }}"
                               placeholder="Enter IFSC code" maxlength="11">
                        @error('ifsc_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="mt-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary" onclick="showStep(1)">
                        <i class="fas fa-arrow-left me-1"></i> Previous
                    </button>
                    <button type="button" class="btn btn-theme" onclick="showStep(3)">
                        Next <i class="fas fa-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>

            {{-- Step 3: ZRTH Assignment (cascading dropdowns from zrth_hierarchies) --}}
            <div class="wizard-panel" id="step-3">
                <h6 class="fw-semibold text-muted mb-3">Step 3: ZRTH Assignment</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="zone_id" class="form-label">Zone</label>
                        <select class="form-select @error('zone_id') is-invalid @enderror"
                                id="zone_id" name="zone_id">
                            <option value="">-- Select Zone --</option>
                            @foreach($zones ?? [] as $zone)
                                <option value="{{ $zone->id }}" {{ old('zone_id') == $zone->id ? 'selected' : '' }}>
                                    [{{ $zone->code }}] {{ $zone->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('zone_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="region_id" class="form-label">Region</label>
                        <select class="form-select @error('region_id') is-invalid @enderror"
                                id="region_id" name="region_id">
                            <option value="">-- Select Region --</option>
                            @foreach($regions ?? [] as $region)
                                <option value="{{ $region->id }}" {{ old('region_id') == $region->id ? 'selected' : '' }}>
                                    [{{ $region->code }}] {{ $region->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('region_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="territory_id" class="form-label">Territory</label>
                        <select class="form-select @error('territory_id') is-invalid @enderror"
                                id="territory_id" name="territory_id">
                            <option value="">-- Select Territory --</option>
                            @foreach($territories ?? [] as $territory)
                                <option value="{{ $territory->id }}" {{ old('territory_id') == $territory->id ? 'selected' : '' }}>
                                    [{{ $territory->code }}] {{ $territory->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('territory_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="hq_id" class="form-label">Headquarters</label>
                        <select class="form-select @error('hq_id') is-invalid @enderror"
                                id="hq_id" name="hq_id">
                            <option value="">-- Select HQ --</option>
                            @foreach($headquarters ?? [] as $hq)
                                <option value="{{ $hq->id }}" {{ old('hq_id') == $hq->id ? 'selected' : '' }}>
                                    [{{ $hq->code }}] {{ $hq->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('hq_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="reporting_manager_id" class="form-label">Reporting Manager</label>
                        <select class="form-select @error('reporting_manager_id') is-invalid @enderror"
                                id="reporting_manager_id" name="reporting_manager_id">
                            <option value="">-- Select Manager --</option>
                            @foreach($managers ?? [] as $manager)
                                <option value="{{ $manager->id }}" {{ old('reporting_manager_id') == $manager->id ? 'selected' : '' }}>
                                    {{ $manager->name }} ({{ strtoupper($manager->role ?? '') }})
                                </option>
                            @endforeach
                        </select>
                        @error('reporting_manager_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="proposed_salary" class="form-label">Proposed Salary</label>
                        <input type="number" step="0.01" class="form-control @error('proposed_salary') is-invalid @enderror"
                               id="proposed_salary" name="proposed_salary" value="{{ old('proposed_salary') }}"
                               placeholder="Enter proposed salary">
                        @error('proposed_salary') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="mt-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary" onclick="showStep(2)">
                        <i class="fas fa-arrow-left me-1"></i> Previous
                    </button>
                    <div>
                        <button type="submit" class="btn btn-theme">
                            <i class="fas fa-save me-1"></i> Submit Application
                        </button>
                        <a href="{{ route('onboarding.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function showStep(step) {
    document.querySelectorAll('.wizard-panel').forEach(function(p) { p.classList.remove('active'); });
    document.getElementById('step-' + step).classList.add('active');

    document.querySelectorAll('.wizard-step').forEach(function(s) {
        var sStep = parseInt(s.getAttribute('data-step'));
        s.classList.remove('active', 'completed');
        if (sStep === step) s.classList.add('active');
        else if (sStep < step) s.classList.add('completed');
    });
}

// Cascading ZRTH dropdowns
document.addEventListener('DOMContentLoaded', function() {
    var zoneSelect = document.getElementById('zone_id');
    var regionSelect = document.getElementById('region_id');
    var territorySelect = document.getElementById('territory_id');
    var hqSelect = document.getElementById('hq_id');

    function loadChildren(parentId, targetSelect, level) {
        targetSelect.innerHTML = '<option value="">-- Loading... --</option>';
        if (!parentId) {
            targetSelect.innerHTML = '<option value="">-- Select ' + level.charAt(0).toUpperCase() + level.slice(1) + ' --</option>';
            return;
        }
        fetch('/api/v1/zrth-hierarchies?parent_id=' + parentId + '&level=' + level)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                var items = data.data || data || [];
                var html = '<option value="">-- Select ' + level.charAt(0).toUpperCase() + level.slice(1) + ' --</option>';
                items.forEach(function(item) {
                    html += '<option value="' + item.id + '">[' + (item.code || '') + '] ' + item.name + '</option>';
                });
                targetSelect.innerHTML = html;
            })
            .catch(function() {
                targetSelect.innerHTML = '<option value="">-- Select ' + level.charAt(0).toUpperCase() + level.slice(1) + ' --</option>';
            });
    }

    if (zoneSelect) {
        zoneSelect.addEventListener('change', function() {
            loadChildren(this.value, regionSelect, 'region');
            territorySelect.innerHTML = '<option value="">-- Select Territory --</option>';
            hqSelect.innerHTML = '<option value="">-- Select HQ --</option>';
        });
    }
    if (regionSelect) {
        regionSelect.addEventListener('change', function() {
            loadChildren(this.value, territorySelect, 'territory');
            hqSelect.innerHTML = '<option value="">-- Select HQ --</option>';
        });
    }
    if (territorySelect) {
        territorySelect.addEventListener('change', function() {
            loadChildren(this.value, hqSelect, 'hq');
        });
    }
});
</script>
@endsection
