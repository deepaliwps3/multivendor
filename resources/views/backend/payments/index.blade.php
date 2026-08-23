<x-app-layout>
    <!-- Breadcrumb -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-12 col-sm-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Payments</h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">Payments</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-12 col-sm-5 align-self-center mt-3 mt-sm-0">
                <div class="customize-input float-sm-end">
                    <button type="button" class="btn btn-primary rounded-pill px-4 w-100 w-sm-auto"
                        data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                        <i data-feather="plus" class="feather-icon me-1"></i> Add Payment
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
                        <h4 class="card-title">Payments List</h4>
                        <h6 class="card-subtitle mb-4 text-muted">Manage all payments with server-side DataTables.</h6>
                        <div class="table-responsive">
                            <table id="payments-table"
                                class="table border table-striped table-bordered text-nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Payer</th>
                                        <th>Payee</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Released At</th>
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

    <!-- Add Payment Modal -->
    <div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('payments.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPaymentModalLabel">Add New Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="order_stage_id" class="form-label font-weight-medium">Order Stage ID</label>
                            <input type="number" class="form-control" id="order_stage_id" name="order_stage_id"
                                placeholder="Order Stage ID" required>
                        </div>
                        <div class="mb-3">
                            <label for="payer_id" class="form-label font-weight-medium">Payer ID</label>
                            <input type="number" class="form-control" id="payer_id" name="payer_id"
                                placeholder="Payer User ID" required>
                        </div>
                        <div class="mb-3">
                            <label for="payee_id" class="form-label font-weight-medium">Payee ID</label>
                            <input type="number" class="form-control" id="payee_id" name="payee_id"
                                placeholder="Payee User ID" required>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label font-weight-medium">Amount</label>
                            <input type="number" step="0.01" class="form-control" id="amount" name="amount"
                                placeholder="0.00" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label font-weight-medium">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="pending" selected>Pending</option>
                                <option value="released">Released</option>
                                <option value="failed">Failed</option>
                                <option value="refunded">Refunded</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="released_at" class="form-label font-weight-medium">Released At</label>
                            <input type="datetime-local" class="form-control" id="released_at" name="released_at">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Payment Modal -->
    <div class="modal fade" id="editPaymentModal" tabindex="-1" aria-labelledby="editPaymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="editPaymentForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_order_stage_id" class="form-label font-weight-medium">Order Stage ID</label>
                            <input type="number" class="form-control" id="edit_order_stage_id" name="order_stage_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_payer_id" class="form-label font-weight-medium">Payer ID</label>
                            <input type="number" class="form-control" id="edit_payer_id" name="payer_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_payee_id" class="form-label font-weight-medium">Payee ID</label>
                            <input type="number" class="form-control" id="edit_payee_id" name="payee_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_amount" class="form-label font-weight-medium">Amount</label>
                            <input type="number" step="0.01" class="form-control" id="edit_amount" name="amount" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_status" class="form-label font-weight-medium">Status</label>
                            <select class="form-select" id="edit_status" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="released">Released</option>
                                <option value="failed">Failed</option>
                                <option value="refunded">Refunded</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_released_at" class="form-label font-weight-medium">Released At</label>
                            <input type="datetime-local" class="form-control" id="edit_released_at" name="released_at">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- DataTables Scripts -->
    @push('scripts')
        <script>
            $(document).ready(function() {
                var table = $('#payments-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('payments.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'payer_name',
                            name: 'payer.name'
                        },
                        {
                            data: 'payee_name',
                            name: 'payee.name'
                        },
                        {
                            data: 'amount',
                            name: 'amount'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'released_at',
                            name: 'released_at'
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
                $(document).on('click', '.edit-payment-btn', function() {
                    var id = $(this).data('id');
                    var updateUrl = "{{ route('payments.update', ':id') }}".replace(':id', id);

                    $('#editPaymentForm').attr('action', updateUrl);
                    $('#edit_order_stage_id').val($(this).data('order_stage_id'));
                    $('#edit_payer_id').val($(this).data('payer_id'));
                    $('#edit_payee_id').val($(this).data('payee_id'));
                    $('#edit_amount').val($(this).data('amount'));
                    $('#edit_status').val($(this).data('status'));
                    $('#edit_released_at').val($(this).data('released_at'));
                    $('#editPaymentModal').modal('show');
                });

                // Handle AJAX Delete
                $(document).on('click', '.delete-payment-btn', function() {
                    if (confirm('Are you sure you want to delete this payment?')) {
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
                                alert('Failed to delete payment.');
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>