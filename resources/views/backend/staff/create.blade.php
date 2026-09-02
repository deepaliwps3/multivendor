<x-app-layout>
    <!-- Breadcrumb -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-12 col-sm-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Add Staff</h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('staff-permissions.index') }}">Staff & Permissions</a></li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">Add Staff</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        @if (isset($errors) && $errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="row">
            <div class="col-12 col-xl-10">
                <form id="addStaffForm" action="{{ route('staff.store') }}" method="POST">
                    @csrf

                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="name" class="form-label font-weight-medium">Name</label>
                                    <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control staff-required-field" id="name" name="name"
                                        value="{{ old('name') }}" placeholder="Enter full name" required>
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label for="email" class="form-label font-weight-medium">Email</label>
                                    <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control staff-required-field" id="email" name="email"
                                        value="{{ old('email') }}" placeholder="Enter email address" required>
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label for="phone" class="form-label font-weight-medium">Phone</label>
                                    <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control staff-required-field" id="phone" name="phone"
                                        value="{{ old('phone') }}" placeholder="Enter phone number" required>
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label for="password" class="form-label font-weight-medium">Password</label>
                                    <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control staff-required-field" id="password"
                                        name="password" placeholder="Enter password" required>
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label for="address" class="form-label font-weight-medium">Address</label>
                                    <span class="text-danger">*</span></label>
                                    <textarea class="form-control staff-required-field" id="address" name="address" rows="3"
                                        placeholder="Enter address" required>{{ old('address') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('staff-permissions.index') }}" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" id="saveStaffBtn" class="btn btn-primary" disabled>Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function() {
                var fields = document.querySelectorAll('.staff-required-field');
                var saveBtn = document.getElementById('saveStaffBtn');

                function toggleSaveButton() {
                    var allFilled = Array.prototype.every.call(fields, function(field) {
                        return field.value.trim().length > 0;
                    });
                    saveBtn.disabled = !allFilled;
                }

                fields.forEach(function(field) {
                    field.addEventListener('input', toggleSaveButton);
                });

                // In case the browser restores values on back/refresh.
                toggleSaveButton();
            })();
        </script>
    @endpush
</x-app-layout>