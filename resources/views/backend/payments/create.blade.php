<x-app-layout>
    <!-- Breadcrumb -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-12 col-sm-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Add Payment</h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">Payments</a></li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">Add Payment</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-12 col-sm-5 align-self-center mt-3 mt-sm-0">
                <div class="customize-input float-sm-end">
                    <a href="{{ route('payments.index') }}" class="btn btn-secondary rounded-pill px-4 w-100 w-sm-auto">
                        <i data-feather="arrow-left" class="feather-icon me-1"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Container fluid -->
    <div class="container-fluid">
        @if ($errors->any())
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
                        <h4 class="card-title">New Payment</h4>
                        <h6 class="card-subtitle mb-4 text-muted">Fill the details below to create a new payment</h6>

                        <form action="{{ route('payments.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="order_stage_id" class="form-label font-weight-medium">Order Stage ID</label>
                                    <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('order_stage_id') is-invalid @enderror"
                                        id="order_stage_id" name="order_stage_id"
                                        value="{{ old('order_stage_id') }}" placeholder="Order Stage ID" required>
                                    @error('order_stage_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="amount" class="form-label font-weight-medium">Amount</label>
                                    <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01"
                                        class="form-control @error('amount') is-invalid @enderror" id="amount"
                                        name="amount" value="{{ old('amount') }}" placeholder="0.00" required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="payer_id" class="form-label font-weight-medium">Payer ID</label>
                                    <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('payer_id') is-invalid @enderror"
                                        id="payer_id" name="payer_id" value="{{ old('payer_id') }}"
                                        placeholder="Payer User ID" required>
                                    @error('payer_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="payee_id" class="form-label font-weight-medium">Payee ID</label>
                                    <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('payee_id') is-invalid @enderror"
                                        id="payee_id" name="payee_id" value="{{ old('payee_id') }}"
                                        placeholder="Payee User ID" required>
                                    @error('payee_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label font-weight-medium">Status</label>
                                    <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status"
                                        name="status" required>
                                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="released" {{ old('status') == 'released' ? 'selected' : '' }}>Released</option>
                                        <option value="failed" {{ old('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                        <option value="refunded" {{ old('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="released_at" class="form-label font-weight-medium">Released At</label>
                                    <input type="datetime-local"
                                        class="form-control @error('released_at') is-invalid @enderror"
                                        id="released_at" name="released_at" value="{{ old('released_at') }}">
                                    @error('released_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i data-feather="save" class="feather-icon me-1"></i> Save Payment
                                </button>
                                <a href="{{ route('payments.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>