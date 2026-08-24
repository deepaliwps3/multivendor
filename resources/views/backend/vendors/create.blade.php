<x-app-layout>
    @php
        $isEdit = isset($vendor) && $vendor->exists;
    @endphp

    <!-- Breadcrumb -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-12 col-sm-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">
                    {{ $isEdit ? 'Edit Vendor' : 'Add New Vendor' }}
                </h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('vendors.index') }}">Vendors</a></li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">
                                {{ $isEdit ? 'Edit Vendor' : 'Create Vendor' }}
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-12 col-sm-5 align-self-center mt-3 mt-sm-0">
                <div class="customize-input float-sm-end">
                    <a href="{{ route('vendors.index') }}" class="btn btn-secondary rounded-pill px-4 w-100 w-sm-auto">
                        <i data-feather="arrow-left" class="feather-icon me-1"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Container fluid -->
    <div class="container-fluid">
        @if (isset($errors) && $errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12 col-xl-10">
                <form action="{{ $isEdit ? route('vendors.update', $vendor) : route('vendors.store') }}" method="POST">
                    @csrf
                    @if ($isEdit)
                        @method('PUT')
                    @endif

                    <!-- Card 1: General Business Information -->
                    <div class="card mb-4">
                        <div class="card-body">
                            
                            <!-- 2 Fields Per Row Grid -->
                            <div class="row">
                                <!-- Field 1: Business Name -->
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="business_name" class="form-label font-weight-medium">Business / Company Name <span class="text-danger">*</span></label>
                                    <input type="text" name="business_name" id="business_name" class="form-control @error('business_name') is-invalid @enderror" value="{{ old('business_name', $vendor->business_name ?? '') }}" placeholder="e.g. Acme Tech Solutions" required>
                                    @error('business_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Field 2: Contact Person Name -->
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="contact_person" class="form-label font-weight-medium">Contact Person Name</label>
                                    <input type="text" name="contact_person" id="contact_person" class="form-control @error('contact_person') is-invalid @enderror" value="{{ old('contact_person', $vendor->contact_person ?? '') }}" placeholder="e.g. John Doe">
                                    @error('contact_person')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Field 3: Email Address -->
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="email" class="form-label font-weight-medium">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $vendor->user?->email ?? '') }}" placeholder="e.g. vendor@example.com" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Field 4: Phone Number -->
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="phone" class="form-label font-weight-medium">Phone Number</label>
                                    <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $vendor->user?->phone ?? '') }}" placeholder="e.g. +91 9876543210">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Field 5: Password -->
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="password" class="form-label font-weight-medium">Password {{ $isEdit ? '(Optional)' : '*' }}</label>
                                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ $isEdit ? 'Leave blank to keep current password' : 'Set login password' }}" {{ $isEdit ? '' : 'required' }}>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Field 6: GST Number -->
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="gst_number" class="form-label font-weight-medium">GST Number</label>
                                    <input type="text" name="gst_number" id="gst_number" class="form-control @error('gst_number') is-invalid @enderror" value="{{ old('gst_number', $vendor->gst_number ?? '') }}" placeholder="e.g. 22AAAAA0000A1Z5">
                                    @error('gst_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Field 5: Address -->
                                <div class="col-12 mb-3">
                                    <label for="address" class="form-label font-weight-medium">Business Address</label>
                                    <textarea name="address" id="address" rows="2" class="form-control @error('address') is-invalid @enderror" placeholder="Enter complete business street address, city, state, pincode">{{ old('address', $vendor->address ?? '') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Field 6: Vendor Type -->
                               

                                <!-- Field 7: KYC Status -->
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="kyc_status" class="form-label font-weight-medium">KYC Status <span class="text-danger">*</span></label>
                                    <select name="kyc_status" id="kyc_status" class="form-select @error('kyc_status') is-invalid @enderror" required>
                                        <option value="pending" {{ old('kyc_status', $vendor->kyc_status ?? 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="verified" {{ old('kyc_status', $vendor->kyc_status ?? '') == 'verified' ? 'selected' : '' }}>Verified</option>
                                        <option value="rejected" {{ old('kyc_status', $vendor->kyc_status ?? '') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                    @error('kyc_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Field 8: Approval Status -->
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="approval_status" class="form-label font-weight-medium">Approval Status <span class="text-danger">*</span></label>
                                    <select name="approval_status" id="approval_status" class="form-select @error('approval_status') is-invalid @enderror" required>
                                        <option value="pending" {{ old('approval_status', $vendor->approval_status ?? 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ old('approval_status', $vendor->approval_status ?? '') == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ old('approval_status', $vendor->approval_status ?? '') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                    @error('approval_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Field 9: Rejection Reason (visible when Approval Status is 'rejected') -->
                                <div class="col-12 mb-3" id="rejection_reason_wrapper" style="{{ old('approval_status', $vendor->approval_status ?? '') === 'rejected' ? '' : 'display: none;' }}">
                                    <label for="rejection_reason" class="form-label font-weight-medium text-danger">Rejection Reason / Notes</label>
                                    <textarea name="rejection_reason" id="rejection_reason" rows="2" class="form-control @error('rejection_reason') is-invalid @enderror" placeholder="Explain clearly why this vendor registration request is being rejected...">{{ old('rejection_reason', $vendor->rejection_reason ?? '') }}</textarea>
                                    @error('rejection_reason')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Associated Industry Selection (Single Select) -->
                    <div class="card mb-4">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="card-title text-dark font-weight-medium mb-0">Associated Industry <span class="text-danger">*</span></h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-2">Select the primary industry for this vendor profile:</p>
                            @php
                                $selectedIndustryId = old('industries.0', isset($selectedIndustries[0]) ? $selectedIndustries[0] : '');
                            @endphp
                            <select name="industries[]" id="industry_select" class="form-select @error('industries') is-invalid @enderror" required>
                                <option value="" {{ empty($selectedIndustryId) ? 'selected' : '' }}>-- Select Industry --</option>
                                @foreach ($industries as $industry)
                                    <option value="{{ $industry->id }}" {{ (string)$selectedIndustryId === (string)$industry->id ? 'selected' : '' }}>
                                        {{ $industry->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('industries')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Card 3: Associated Services Selection (Filtered by Industry) -->
                    <div class="card mb-4">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="card-title text-dark font-weight-medium mb-0">Associated Services</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-3">Select the services offered by this vendor for the chosen industry:</p>
                            
                            <div id="no_industry_notice" class="alert alert-warning py-2" style="{{ !empty($selectedIndustryId) ? 'display: none;' : '' }}">
                                <i data-feather="alert-circle" class="feather-icon me-1"></i> Please select an industry above to view and select relevant services.
                            </div>

                            <div class="row" id="services_container">
                                @forelse ($services as $service)
                                    @php
                                        $isServiceSelected = in_array($service->id, old('services', $selectedServices ?? []));
                                        $isBelongsToSelectedIndustry = !empty($selectedIndustryId) && (string)$service->industry_id === (string)$selectedIndustryId;
                                    @endphp
                                    <div class="col-12 col-sm-6 col-md-4 mb-2 service-item" 
                                         data-industry-id="{{ $service->industry_id }}"
                                         style="{{ $isBelongsToSelectedIndustry ? '' : 'display: none;' }}">
                                        <div class="form-check">
                                            <input class="form-check-input service-checkbox" type="checkbox" name="services[]" value="{{ $service->id }}" id="service_{{ $service->id }}"
                                                {{ $isServiceSelected ? 'checked' : '' }}>
                                            <label class="form-check-label" for="service_{{ $service->id }}">
                                                {{ $service->name }}
                                            </label>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-muted">No services available.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Form Action Buttons -->
                    <div class="mb-4">
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i data-feather="{{ $isEdit ? 'save' : 'check' }}" class="feather-icon me-1"></i>
                            {{ $isEdit ? 'Update Vendor' : 'Create Vendor' }}
                        </button>
                        <a href="{{ route('vendors.index') }}" class="btn btn-secondary rounded-pill px-4 ms-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Approval Status Rejection Reason Toggle
            const approvalSelect = document.getElementById('approval_status');
            const rejectionWrapper = document.getElementById('rejection_reason_wrapper');

            if (approvalSelect && rejectionWrapper) {
                approvalSelect.addEventListener('change', function() {
                    if (this.value === 'rejected') {
                        rejectionWrapper.style.display = 'block';
                    } else {
                        rejectionWrapper.style.display = 'none';
                    }
                });
            }

            // 2. Dependent Services Filter based on Selected Industry
            const industrySelect = document.getElementById('industry_select');
            const notice = document.getElementById('no_industry_notice');
            const serviceItems = document.querySelectorAll('.service-item');

            function filterServices(isInitialLoad = false) {
                const selectedId = industrySelect ? industrySelect.value : '';

                if (!selectedId) {
                    if (notice) notice.style.display = 'block';
                    serviceItems.forEach(item => {
                        item.style.display = 'none';
                        if (!isInitialLoad) {
                            const checkbox = item.querySelector('.service-checkbox');
                            if (checkbox) checkbox.checked = false;
                        }
                    });
                    return;
                }

                if (notice) notice.style.display = 'none';

                serviceItems.forEach(item => {
                    const itemIndustryId = item.getAttribute('data-industry-id');
                    if (itemIndustryId === selectedId) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                        if (!isInitialLoad) {
                            const checkbox = item.querySelector('.service-checkbox');
                            if (checkbox) checkbox.checked = false;
                        }
                    }
                });
            }

            if (industrySelect) {
                industrySelect.addEventListener('change', function() {
                    filterServices(false);
                });
                
                // Run filter on initial page load preserving edit state
                filterServices(true);
            }
        });
    </script>
</x-app-layout>
