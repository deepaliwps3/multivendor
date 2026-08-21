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
                                <!-- Field 1: Associated User Account -->
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="user_id" class="form-label font-weight-medium">Associated User Account <span class="text-danger">*</span></label>
                                    <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                                        <option value="" disabled {{ old('user_id', $vendor->user_id ?? '') == '' ? 'selected' : '' }}>Select User Account</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id', $vendor->user_id ?? '') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Field 2: Business Name -->
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="business_name" class="form-label font-weight-medium">Business / Company Name <span class="text-danger">*</span></label>
                                    <input type="text" name="business_name" id="business_name" class="form-control @error('business_name') is-invalid @enderror" value="{{ old('business_name', $vendor->business_name ?? '') }}" placeholder="e.g. Acme Tech Solutions" required>
                                    @error('business_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Field 3: Contact Person -->
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="contact_person" class="form-label font-weight-medium">Contact Person Name</label>
                                    <input type="text" name="contact_person" id="contact_person" class="form-control @error('contact_person') is-invalid @enderror" value="{{ old('contact_person', $vendor->contact_person ?? '') }}" placeholder="e.g. John Doe">
                                    @error('contact_person')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Field 4: GST Number -->
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
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="vendor_type" class="form-label font-weight-medium">Vendor Type <span class="text-danger">*</span></label>
                                    <select name="vendor_type" id="vendor_type" class="form-select @error('vendor_type') is-invalid @enderror" required>
                                        <option value="both" {{ old('vendor_type', $vendor->vendor_type ?? 'both') == 'both' ? 'selected' : '' }}>Both (Originator & Executor)</option>
                                        <option value="originator" {{ old('vendor_type', $vendor->vendor_type ?? '') == 'originator' ? 'selected' : '' }}>Originator Only</option>
                                        <option value="executor" {{ old('vendor_type', $vendor->vendor_type ?? '') == 'executor' ? 'selected' : '' }}>Executor Only</option>
                                    </select>
                                    @error('vendor_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

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
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Associated Industries Selection -->
                    

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
</x-app-layout>
