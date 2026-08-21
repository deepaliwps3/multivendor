<x-app-layout>
    <!-- Breadcrumb -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-12 col-sm-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Vendors</h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">Vendors</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-12 col-sm-5 align-self-center mt-3 mt-sm-0">
                <div class="customize-input float-sm-end">
                    <a href="{{ route('vendors.create') }}" class="btn btn-primary rounded-pill px-4 w-100 w-sm-auto">
                        <i data-feather="plus" class="feather-icon me-1"></i> Add Vendor
                    </a>
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
                        <h4 class="card-title">Vendors List</h4>
                        <h6 class="card-subtitle mb-4 text-muted">Manage all registered vendors, KYC statuses, and
                            approval statuses.</h6>
                        <div class="table-responsive">
                            <table id="vendors-table"
                                class="table border table-striped table-bordered text-nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>User Details</th>
                                        <th>Business Name</th>
                                        <th>Contact Person</th>
                                        <th>GST Number</th>
                                        <th>Type</th>
                                        <th>KYC Status</th>
                                        <th>Approval Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTables Scripts -->
    @push('scripts')
        <script>
            $(document).ready(function() {
                var table = $('#vendors-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('vendors.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'user_info',
                            name: 'user.name'
                        },
                        {
                            data: 'business_name',
                            name: 'business_name'
                        },
                        {
                            data: 'contact_person',
                            name: 'contact_person'
                        },
                        {
                            data: 'gst_number',
                            name: 'gst_number'
                        },
                        {
                            data: 'vendor_type',
                            name: 'vendor_type'
                        },
                        {
                            data: 'kyc_status',
                            name: 'kyc_status'
                        },
                        {
                            data: 'approval_status',
                            name: 'approval_status'
                        },
                        {
                            data: 'actions',
                            name: 'actions',
                            orderable: false,
                            searchable: false
                        }
                    ],
                    drawCallback: function() {
                        if (typeof feather !== 'undefined') {
                            feather.replace();
                        }
                    }
                });

                // Handle AJAX Delete
                $(document).on('click', '.delete-vendor-btn', function() {
                    if (confirm('Are you sure you want to delete this vendor?')) {
                        var url = $(this).data('url');
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                _method: "DELETE"
                            },
                            success: function(response) {
                                table.ajax.reload(null, false);
                            },
                            error: function(xhr) {
                                alert('Failed to delete vendor.');
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
