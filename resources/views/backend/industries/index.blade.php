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
                    <button type="button" class="btn btn-primary rounded-pill px-4 w-100 w-sm-auto"
                        data-bs-toggle="modal" data-bs-target="#addIndustryModal">
                        <i data-feather="plus" class="feather-icon me-1"></i> Add Industry
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
                        <h4 class="card-title">Industries List</h4>
                        <h6 class="card-subtitle mb-4 text-muted">Manage all industry categories with server-side
                            DataTables.</h6>
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

    <!-- Add Industry Modal -->
    <div class="modal fade" id="addIndustryModal" tabindex="-1" aria-labelledby="addIndustryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('industries.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addIndustryModalLabel">Add New Industry</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label font-weight-medium">Industry Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="e.g. Technology, Healthcare" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label font-weight-medium">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Industry</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Industry Modal -->
    <div class="modal fade" id="editIndustryModal" tabindex="-1" aria-labelledby="editIndustryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="editIndustryForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editIndustryModalLabel">Edit Industry</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label font-weight-medium">Industry Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_status" class="form-label font-weight-medium">Status</label>
                            <select class="form-select" id="edit_status" name="status" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Industry</button>
                    </div>
                </form>
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

                // Trigger Edit Modal dynamically
                $(document).on('click', '.edit-industry-btn', function() {
                    var id = $(this).data('id');
                    var name = $(this).data('name');
                    var status = $(this).data('status');

                    var updateUrl = "{{ route('industries.update', ':id') }}".replace(':id', id);
                    $('#editIndustryForm').attr('action', updateUrl);
                    $('#edit_name').val(name);
                    $('#edit_status').val(status);
                    $('#editIndustryModal').modal('show');
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
            });
        </script>
    @endpush
</x-app-layout>
