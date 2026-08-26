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
                    <button type="button" class="btn btn-primary rounded-pill px-4 w-100 w-sm-auto"
                        data-bs-toggle="modal" data-bs-target="#addStaffPermissionModal">
                        <i data-feather="plus" class="feather-icon me-1"></i> Add Staff Permission
                    </button>
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
                        <h4 class="card-title">Staff Permissions List</h4>
                        <h6 class="card-subtitle mb-4 text-muted">Manage all staff permissions</h6>
                        <div class="table-responsive">
                            <table id="staff-permissions-table"
                                class="table border table-striped table-bordered text-nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Staff</th>
                                        <th>Permission Key</th>
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

    <!-- Add Staff Permission Modal -->
    <div class="modal fade" id="addStaffPermissionModal" tabindex="-1" aria-labelledby="addStaffPermissionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('staff-permissions.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addStaffPermissionModalLabel">Add New Staff Permission</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="staff_id" class="form-label font-weight-medium">Staff</label>
                            <select class="form-select" id="staff_id" name="staff_id" required>
                                <option value="" selected disabled>Select Staff</option>
                                @foreach ($staff as $member)
                                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="permission_key" class="form-label font-weight-medium">Permission Key</label>
                            <input type="text" class="form-control" id="permission_key" name="permission_key"
                                placeholder="e.g. industries.create" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Permission</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Staff Permission Modal -->
    <div class="modal fade" id="editStaffPermissionModal" tabindex="-1" aria-labelledby="editStaffPermissionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="editStaffPermissionForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editStaffPermissionModalLabel">Edit Staff Permission</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_staff_id" class="form-label font-weight-medium">Staff</label>
                            <select class="form-select" id="edit_staff_id" name="staff_id" required>
                                <option value="" disabled>Select Staff</option>
                                @foreach ($staff as $member)
                                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_permission_key" class="form-label font-weight-medium">Permission
                                Key</label>
                            <input type="text" class="form-control" id="edit_permission_key" name="permission_key"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Permission</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- DataTables Scripts -->
    @push('scripts')
        <script>
            $(document).ready(function() {
                var table = $('#staff-permissions-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('staff-permissions.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'staff_id',
                            name: 'staff_id'
                        },
                        {
                            data: 'permission_key',
                            name: 'permission_key'
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

                // Trigger Edit Modal dynamically
                $(document).on('click', '.edit-staff-permission-btn', function() {
                    var id = $(this).data('id');
                    var staffId = $(this).data('staff-id');
                    var permissionKey = $(this).data('permission-key');

                    var updateUrl = "{{ route('staff-permissions.update', ':id') }}".replace(':id', id);
                    $('#editStaffPermissionForm').attr('action', updateUrl);
                    $('#edit_staff_id').val(staffId);
                    $('#edit_permission_key').val(permissionKey);
                    $('#editStaffPermissionModal').modal('show');
                });

                // Handle AJAX Delete
                $(document).on('click', '.delete-staff-permission-btn', function() {
                    if (confirm('Are you sure you want to delete this staff permission?')) {
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
                                alert('Failed to delete staff permission.');
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>