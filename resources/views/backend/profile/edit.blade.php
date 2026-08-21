<x-app-layout>
    <!-- Breadcrumb -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-12 col-sm-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">My Profile</h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">Profile Settings</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Container fluid -->
    <div class="container-fluid">
        <div class="row">
            <!-- Left User Overview Card -->
            <div class="col-12 col-lg-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="position-relative d-inline-block mb-3">
                            <img src="{{ asset('assets/extra-assets/images/users/profile-pic.jpg') }}" alt="user"
                                class="rounded-circle shadow-sm" width="100">
                        </div>
                        <h4 class="card-title text-dark font-weight-medium mb-1">{{ Auth::user()->name }}</h4>
                        <p class="text-muted font-14 mb-2">{{ Auth::user()->email }}</p>

                        <div class="mb-3">
                            <span class="badge bg-primary rounded-pill px-3 py-2 text-white font-weight-medium">
                                {{ ucfirst(Auth::user()->role?->name ?? 'Super Admin') }}
                            </span>
                        </div>

                        <hr class="my-3">

                        <div class="row text-center">
                            <div class="col-12">
                                <h6 class="text-muted font-weight-normal mb-1">Account Created</h6>
                                <p class="text-dark font-weight-medium mb-0">
                                    {{ Auth::user()->created_at?->format('M d, Y') ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Profile Action Cards -->
            <div class="col-12 col-lg-8">
                <!-- 1. Profile Information -->
                <div class="card mb-4">
                    <div class="card-body">
                        @include('backend.profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- 2. Update Password -->
                <div class="card mb-4">
                    <div class="card-body">
                        @include('backend.profile.partials.update-password-form')
                    </div>
                </div>

                <!-- 3. Delete Account -->
                {{-- <div class="card mb-4 border-danger">
                    <div class="card-body">
                        @include('backend.profile.partials.delete-user-form')
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</x-app-layout>
