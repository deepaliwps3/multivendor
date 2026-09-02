<x-app-layout>
    <!-- Breadcrumb -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-12 col-sm-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">
                    {{ $industry->exists ? 'Edit Industry' : 'Add Industry' }}
                </h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('industries.index') }}">Industries</a></li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">
                                {{ $industry->exists ? 'Edit' : 'Add' }}
                            </li>
                        </ol>
                    </nav>
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

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            {{-- <div class="col-12 col-lg-8 col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ $industry->exists ? 'Update Industry' : 'New Industry' }}</h4>
                        <h6 class="card-subtitle mb-4 text-muted">Fill in the details below</h6>

                        <form action="{{ $industry->exists ? route('industries.update', $industry->id) : route('industries.store') }}"
                            method="POST">
                            @csrf
                            @if ($industry->exists)
                                @method('PUT')
                            @endif

                            <div class="mb-3">
                                <label for="name" class="form-label font-weight-medium">Industry Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $industry->name) }}"
                                    placeholder="e.g. Technology, Healthcare" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="status" class="form-label font-weight-medium">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status"
                                    name="status" required>
                                    <option value="1"
                                        {{ old('status', $industry->status ?? '1') == '1' ? 'selected' : '' }}>
                                        Active
                                    </option>
                                    <option value="0"
                                        {{ old('status', $industry->status ?? '1') == '0' ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary rounded-pill px-4">
                                    {{ $industry->exists ? 'Update Industry' : 'Save Industry' }}
                                </button>
                                <a href="{{ route('industries.index') }}"
                                    class="btn btn-secondary rounded-pill px-4">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div> --}}
            <div class="col-12 col-xl-10">
                <form action="{{ $industry->exists ? route('industries.update', $industry->id) : route('industries.store') }}"
                    method="POST">
                    @csrf
                    @if ($industry->exists)
                        @method('PUT')
                    @endif

                    <div class="card mb-4">
                        <div class="card-body">
                            
                            <!-- 2 Fields Per Row Grid -->
                            <div class="row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="name" class="form-label font-weight-medium">Industry Name</label>
                                    <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $industry->name) }}"
                                        placeholder="e.g. Technology, Healthcare" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="status" class="form-label font-weight-medium">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status"
                                        name="status" required>
                                        <option value="1"
                                            {{ old('status', $industry->status ?? '1') == '1' ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="0"
                                            {{ old('status', $industry->status ?? '1') == '0' ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            {{ $industry->exists ? 'Update Industry' : 'Save Industry' }}
                        </button>
                        <a href="{{ route('industries.index') }}"
                            class="btn btn-secondary rounded-pill px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>