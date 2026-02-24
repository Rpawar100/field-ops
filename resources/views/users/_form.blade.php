{{-- User Form Partial - Shared between Create and Edit --}}
<div class="row g-3">
    <div class="col-md-6">
        <label for="user_id_field" class="form-label">Employee ID <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('user_id') is-invalid @enderror"
               id="user_id_field" name="user_id"
               value="{{ old('user_id', $user->user_id ?? '') }}"
               placeholder="Enter employee ID (e.g. EMP001)" required
               {{ isset($user) && $user->exists ? 'readonly' : '' }}>
        @error('user_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror"
               id="name" name="name"
               value="{{ old('name', $user->name ?? '') }}"
               placeholder="Enter full name" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="mobile" class="form-label">Mobile Number <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('mobile') is-invalid @enderror"
               id="mobile" name="mobile"
               value="{{ old('mobile', $user->mobile ?? '') }}"
               placeholder="Enter mobile number" required maxlength="15">
        @error('mobile')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
        <input type="email" class="form-control @error('email') is-invalid @enderror"
               id="email" name="email"
               value="{{ old('email', $user->email ?? '') }}"
               placeholder="Enter email address" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    @if(!isset($user) || !$user->exists)
    <div class="col-md-6">
        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
        <input type="password" class="form-control @error('password') is-invalid @enderror"
               id="password" name="password"
               placeholder="Enter password" {{ !isset($user) ? 'required' : '' }}>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
        <input type="password" class="form-control"
               id="password_confirmation" name="password_confirmation"
               placeholder="Confirm password" {{ !isset($user) ? 'required' : '' }}>
    </div>
    @endif

    <div class="col-md-6">
        <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
        <select class="form-select @error('role') is-invalid @enderror"
                id="role" name="role" required>
            <option value="">-- Select Role --</option>
            @php
                $roles = ['nsm' => 'NSM', 'zm' => 'ZM', 'rm' => 'RM', 'tsl' => 'TSL', 'fa' => 'FA'];
            @endphp
            @foreach($roles as $value => $label)
                <option value="{{ $value }}" {{ old('role', $user->role ?? '') === $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('role')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6" id="fa_type_wrapper" style="{{ old('role', $user->role ?? '') === 'fa' ? '' : 'display:none;' }}">
        <label for="fa_type" class="form-label">FA Type</label>
        <select class="form-select @error('fa_type') is-invalid @enderror"
                id="fa_type" name="fa_type">
            <option value="">-- Select FA Type --</option>
            <option value="permanent" {{ old('fa_type', $user->fa_type ?? '') === 'permanent' ? 'selected' : '' }}>Permanent</option>
            <option value="temporary" {{ old('fa_type', $user->fa_type ?? '') === 'temporary' ? 'selected' : '' }}>Temporary</option>
        </select>
        @error('fa_type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="designation" class="form-label">Designation</label>
        <input type="text" class="form-control @error('designation') is-invalid @enderror"
               id="designation" name="designation"
               value="{{ old('designation', $user->designation ?? '') }}"
               placeholder="Enter designation (e.g. Field Assistant)" maxlength="100">
        @error('designation')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="reporting_manager_id" class="form-label">Reporting Manager</label>
        <select class="form-select @error('reporting_manager_id') is-invalid @enderror"
                id="reporting_manager_id" name="reporting_manager_id">
            <option value="">-- Select Reporting Manager --</option>
            @foreach($managers ?? [] as $manager)
                <option value="{{ $manager->id }}" {{ old('reporting_manager_id', $user->reporting_manager_id ?? '') == $manager->id ? 'selected' : '' }}>
                    {{ $manager->name }} ({{ strtoupper($manager->role ?? '') }})
                </option>
            @endforeach
        </select>
        @error('reporting_manager_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="date_of_joining" class="form-label">Date of Joining</label>
        <input type="date" class="form-control @error('date_of_joining') is-invalid @enderror"
               id="date_of_joining" name="date_of_joining"
               value="{{ old('date_of_joining', $user->date_of_joining ?? '') }}">
        @error('date_of_joining')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
        <select class="form-select @error('status') is-invalid @enderror"
                id="status" name="status" required>
            <option value="active" {{ old('status', $user->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $user->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            <option value="pending" {{ old('status', $user->status ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="suspended" {{ old('status', $user->status ?? '') === 'suspended' ? 'selected' : '' }}>Suspended</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="app_access_enabled" class="form-label">App Access</label>
        <div class="form-check form-switch mt-2">
            <input class="form-check-input @error('app_access_enabled') is-invalid @enderror"
                   type="checkbox" id="app_access_enabled" name="app_access_enabled" value="1"
                   {{ old('app_access_enabled', $user->app_access_enabled ?? 1) ? 'checked' : '' }}>
            <label class="form-check-label" for="app_access_enabled">Enable mobile app access</label>
        </div>
        @error('app_access_enabled')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12">
        <label for="address" class="form-label">Address</label>
        <textarea class="form-control @error('address') is-invalid @enderror"
                  id="address" name="address" rows="2"
                  placeholder="Enter address">{{ old('address', $user->address ?? '') }}</textarea>
        @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

@section('scripts')
@parent
<script>
document.getElementById('role').addEventListener('change', function() {
    var faWrapper = document.getElementById('fa_type_wrapper');
    if (this.value === 'fa') {
        faWrapper.style.display = '';
    } else {
        faWrapper.style.display = 'none';
        document.getElementById('fa_type').value = '';
    }
});
</script>
@endsection
