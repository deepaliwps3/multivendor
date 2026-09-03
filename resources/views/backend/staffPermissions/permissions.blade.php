<x-app-layout>
    <!-- Breadcrumb -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-12 col-sm-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">
                    Assign Permissions &mdash; {{ $staff->name }}
                </h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('staff-permissions.index') }}">Staff & Permissions</a></li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">Assign Permissions</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Container fluid -->
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{-- <h4 class="card-title">Assign Permissions &mdash; {{ $staff->name }}</h4>
                        <h6 class="card-subtitle mb-4 text-muted">Select the permissions this staff member should have</h6> --}}

                        <form action="{{ route('staff-permissions.permissions.save', $staff->id) }}" method="POST">
                            @csrf

                            @foreach ($matrix as $module => $permissions)
                                <div class="row align-items-center py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                    <div class="col-12 col-md-3 fw-medium mb-2 mb-md-0">
                                        {{ $module }}
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <div class="d-flex flex-wrap gap-4">
                                            @foreach ($permissions as $permission)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="permissions[]" value="{{ $permission['id'] }}"
                                                        id="permission-{{ $permission['id'] }}"
                                                        @checked($permission['checked'])>
                                                    <label class="form-check-label" for="permission-{{ $permission['id'] }}">
                                                        {{ $permission['label'] }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="d-flex justify-content-end mt-4">
                                <a href="{{ route('staff-permissions.index') }}" class="btn btn-secondary rounded-pill px-4 me-2">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary rounded-pill px-4">
                                    <i data-feather="save" class="feather-icon me-1"></i> Save Permissions
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            });
        </script>
    @endpush
</x-app-layout>