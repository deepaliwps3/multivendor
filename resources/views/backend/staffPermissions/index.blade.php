<x-app-layout>
    <!-- Breadcrumb -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-12 col-sm-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Staff Permissions</h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">Staff Permissions</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-12 col-sm-5 align-self-center mt-3 mt-sm-0">
                <div class="customize-input float-sm-end">
                    <a href="{{ route('staff.create') }}" class="btn btn-primary rounded-pill px-4 w-100 w-sm-auto">
                        <i data-feather="plus" class="feather-icon me-1"></i> Add Staff
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
                        <h4 class="card-title">Staff Permissions List</h4>
                        <h6 class="card-subtitle mb-4 text-muted">Manage all staff and their permissions</h6>
                        <div class="table-responsive">
                            <table id="staff-permissions-table"
                                class="table border table-striped table-bordered text-nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Staff Details</th>
                                        <th>Permission Name</th>
                                        <th>Status</th>
                                        <th>Action</th>
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

        <!-- Status Change Confirmation Modal -->
        <div class="modal fade" id="statusConfirmModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Status Change</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to change the status?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <button type="button" class="btn btn-primary" id="confirmStatusChangeBtn">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                var table = $('#staff-permissions-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('staff-permissions.index') }}",
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'staff_details', name: 'name' },
                        { data: 'permission_name', name: 'staff_permissions_count', orderable: false, searchable: false },
                        { data: 'status', name: 'status' },
                        { data: 'actions', name: 'actions', orderable: false, searchable: false }
                    ],
                    drawCallback: function() {
                        if (typeof feather !== 'undefined') {
                            feather.replace();
                        }
                    }
                });

                var pendingToggle = null; // holds the checkbox element awaiting confirmation
                var statusConfirmModal = new bootstrap.Modal(document.getElementById('statusConfirmModal'));

                // Jab toggle click ho, modal khol do aur checkbox ko purani state pe wapas laa do
                // jab tak confirm na ho jaye
                $('#staff-permissions-table').on('change', '.status-toggle', function() {
                    pendingToggle = $(this);

                    // Revert visually until confirmed
                    pendingToggle.prop('checked', !pendingToggle.prop('checked'));

                    statusConfirmModal.show();
                });

                // Modal band ho (No / cross) to kuch mat karo, checkbox already reverted hai
                $('#statusConfirmModal').on('hidden.bs.modal', function() {
                    pendingToggle = null;
                });

                // Yes click -> AJAX call
                $('#confirmStatusChangeBtn').on('click', function() {
                    if (!pendingToggle) return;

                    var id = pendingToggle.data('id');
                    var url = "{{ route('staff-permissions.toggle-status', ':id') }}".replace(':id', id);

                    $.ajax({
                        url: url,
                        type: 'PATCH',
                        data: { _token: "{{ csrf_token() }}" },
                        success: function(response) {
                            statusConfirmModal.hide();
                            table.ajax.reload(null, false); // reload without resetting pagination
                        },
                        error: function() {
                            statusConfirmModal.hide();
                            alert('Failed to update status. Please try again.');
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>