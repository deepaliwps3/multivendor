<x-app-layout>
    <!-- Breadcrumb -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-12 col-sm-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">
                    Vendor Details
                </h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('vendors.index') }}">Vendors</a></li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">
                                {{ $vendor->business_name }}
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-12 col-sm-5 align-self-center mt-3 mt-sm-0">
                <div class="customize-input float-sm-end d-flex gap-2">
                   
                    <a href="{{ route('vendors.index') }}" class="btn btn-secondary rounded-pill px-4">
                        <i data-feather="arrow-left" class="feather-icon me-1"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Container fluid -->
    <div class="container-fluid">
        <div class="row">
            <!-- Left Column: Business & User Info -->
            <div class="col-12 col-lg-8">
                <!-- Card 1: Vendor Header Overview -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <h4 class="card-title text-dark font-weight-bold mb-1">{{ $vendor->business_name }}</h4>
                                <p class="text-muted mb-0 small">
                                    UUID: <code class="text-primary">{{ $vendor->uuid ?? 'N/A' }}</code> |
                                    Registered: {{ $vendor->created_at ? $vendor->created_at->format('d M Y, h:i A') : 'N/A' }}
                                </p>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                
                                @php
                                    $approvalBadge = match ($vendor->approval_status) {
                                        'approved' => 'bg-success',
                                        'rejected' => 'bg-danger',
                                        default => 'bg-warning text-dark',
                                    };
                                    $kycBadge = match ($vendor->kyc_status) {
                                        'verified' => 'bg-success',
                                        'rejected' => 'bg-danger',
                                        default => 'bg-warning text-dark',
                                    };
                                @endphp
                                <span class="badge {{ $approvalBadge }} text-capitalize fs-6">Approval: {{ $vendor->approval_status }}</span>
                                <span class="badge {{ $kycBadge }} text-capitalize fs-6">KYC: {{ $vendor->kyc_status }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Account & Contact Information -->
                <div class="card mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title text-dark font-weight-medium mb-0">Account & Contact Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                                <label class="text-muted small text-uppercase font-weight-bold d-block">Associated User Account</label>
                                <span class="text-dark font-weight-medium fs-6">{{ $vendor->user?->name ?? 'N/A' }}</span>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="text-muted small text-uppercase font-weight-bold d-block">Email Address</label>
                                <span class="text-dark font-weight-medium fs-6">{{ $vendor->user?->email ?? 'N/A' }}</span>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="text-muted small text-uppercase font-weight-bold d-block">Phone Number</label>
                                <span class="text-dark font-weight-medium fs-6">{{ $vendor->user?->phone ?? 'Not provided' }}</span>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="text-muted small text-uppercase font-weight-bold d-block">Contact Person</label>
                                <span class="text-dark font-weight-medium fs-6">{{ $vendor->contact_person ?? 'Not provided' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Business & Legal Details -->
                <div class="card mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title text-dark font-weight-medium mb-0">Business & Legal Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                                <label class="text-muted small text-uppercase font-weight-bold d-block">GST Number</label>
                                <span class="text-dark font-weight-medium fs-6">{{ $vendor->gst_number ?? 'Not registered' }}</span>
                            </div>
                            
                            <div class="col-12 col-sm-6">
                                <label class="text-muted small text-uppercase font-weight-bold d-block">Business Address</label>
                                <span class="text-dark font-weight-medium fs-6">{{ $vendor->address ?? 'No address provided.' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Associated Industries & Services -->
                <div class="card mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title text-dark font-weight-medium mb-0">Associated Industries & Services</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="text-muted small text-uppercase font-weight-bold d-block mb-2">Industries</label>
                            @forelse ($vendor->industries as $industry)
                                <span class="badge bg-light text-dark border me-1 mb-1 p-2 fs-6">
                                    <i data-feather="layers" class="feather-icon me-1 text-primary"></i> {{ $industry->name }}
                                </span>
                            @empty
                                <span class="text-muted">No industries linked.</span>
                            @endforelse
                        </div>

                        <div>
                            <label class="text-muted small text-uppercase font-weight-bold d-block mb-2">Services Offered</label>
                            @forelse ($vendor->services as $service)
                                <span class="badge bg-light text-dark border me-1 mb-1 p-2 fs-6">
                                    <i data-feather="settings" class="feather-icon me-1 text-info"></i> {{ $service->name }}
                                </span>
                            @empty
                                <span class="text-muted">No services linked.</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Verification & Status Sidebar -->
            <div class="col-12 col-lg-4">
                <div class="card mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title text-dark font-weight-medium mb-0">Verification & Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="text-muted small text-uppercase font-weight-bold d-block mb-1">Approval Status</label>
                            <span class="badge {{ $approvalBadge }} text-capitalize px-3 py-2 fs-6">{{ $vendor->approval_status }}</span>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small text-uppercase font-weight-bold d-block mb-1">KYC Status</label>
                            <span class="badge {{ $kycBadge }} text-capitalize px-3 py-2 fs-6">{{ $vendor->kyc_status }}</span>
                        </div>

                        @if ($vendor->approval_status === 'rejected')
                            <div class="alert alert-danger mt-3" role="alert">
                                <h6 class="alert-heading font-weight-bold mb-1">Rejection Reason:</h6>
                                <p class="mb-0 small">{{ $vendor->rejection_reason ?? 'No explicit reason specified.' }}</p>
                            </div>
                        @endif

                        <hr>

                        <div class="d-grid gap-2">
                            <a href="{{ route('vendors.edit', $vendor) }}" class="btn btn-primary rounded-pill">
                                <i data-feather="edit" class="feather-icon me-1"></i> Edit Vendor Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
