<x-app-layout>
    <!-- Breadcrumb -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-12 col-sm-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Edit Staff</h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('staff-permissions.index') }}">Staff & Permissions</a></li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">Edit Staff</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-lg-8 col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Staff Details</h4>
                        <h6 class="card-subtitle mb-4 text-muted">Leave password blank to keep it unchanged</h6>

                        @if (isset($errors) && $errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('staff.update', $staff->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="name" class="form-label font-weight-medium">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name', $staff->name) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label font-weight-medium">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email', $staff->email) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label font-weight-medium">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    value="{{ old('phone', $staff->phone) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label font-weight-medium">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Leave blank to keep current password">
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label font-weight-medium">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3"
                                    required>{{ old('address', $staff->address) }}</textarea>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="{{ route('staff-permissions.index') }}" class="btn btn-secondary me-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>