<x-app-layout>
    <!-- Breadcrumb -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-12 col-sm-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Industries</h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">Industries</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-12 col-sm-5 align-self-center mt-3 mt-sm-0">
                <div class="customize-input float-sm-end">
                    <a href="{{ route('industries.create') }}" class="btn btn-primary rounded-pill px-4 w-100 w-sm-auto">
                        <i data-feather="plus" class="feather-icon me-1"></i> Add Industry
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
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Industries List</h4>
                        <h6 class="card-subtitle mb-4 text-muted">Manage all industry categories</h6>
                        <div class="table-responsive">
                            <table id="industries-table"
                                class="table border table-striped table-bordered text-nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Name</th>
                                        <th>Status</th>
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

    <!-- Status Change Confirmation Modal -->
    <div class="modal fade" id="statusConfirmModal" tabindex="-1" aria-labelledby="statusConfirmModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusConfirmModalLabel">Confirm Status Change</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to change the status?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="statusConfirmNo"
                        data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" id="statusConfirmYes">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTables Scripts -->
    @push('scripts')
        <script>
            $(document).ready(function() {
                var table = $('#industries-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('industries.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'status',
                            name: 'status'
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
                $(document).on('click', '.delete-industry-btn', function() {
                    if (confirm('Are you sure you want to delete this industry?')) {
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
                                alert('Failed to delete industry.');
                            }
                        });
                    }
                });

                // Handle Status Toggle
                var pendingToggle = null; // holds the checkbox element awaiting confirmation

                // Intercept toggle click, show confirmation modal instead of firing AJAX immediately
                $(document).on('change', '.status-toggle-btn', function() {
                    var checkbox = $(this);

                    // Prevent this change from being treated as final until confirmed
                    checkbox.prop('checked', !checkbox.is(':checked')); // revert visually first

                    pendingToggle = checkbox;
                    $('#statusConfirmModal').modal('show');
                });

                // On "No" or modal dismiss (X / backdrop) — just reset pending reference
                $('#statusConfirmModal').on('hidden.bs.modal', function() {
                    pendingToggle = null;
                });

                // On "Yes" — apply the toggle visually and send AJAX request
                $('#statusConfirmYes').on('click', function() {
                    if (!pendingToggle) {
                        return;
                    }

                    var checkbox = pendingToggle;
                    var url = checkbox.data('url');

                    // Now actually flip the checkbox to reflect the confirmed change
                    checkbox.prop('checked', !checkbox.is(':checked'));

                    $.ajax({
                        url: url,
                        type: 'PATCH',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            // UI already updated
                        },
                        error: function(xhr) {
                            checkbox.prop('checked', !checkbox.is(':checked')); // revert on failure
                            alert('Failed to update status.');
                        }
                    });

                    pendingToggle = null;
                    $('#statusConfirmModal').modal('hide');
                });
            });
        </script>
    @endpush
</x-app-layout>
