<x-app-layout>
    @php
        $isEdit = $service->exists;
    @endphp

    <!-- Breadcrumb -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-12 col-sm-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">
                    {{ $isEdit ? 'Edit Service' : 'Add Service' }}
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('services.index') }}">Services</a></li>
                        <li class="breadcrumb-item text-muted active" aria-current="page">
                            {{ $isEdit ? 'Edit' : 'Add' }}
                        </li>
                    </ol>
                </nav>
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
            <div class="col-12 col-xl-10">
                <form action="{{ $isEdit ? route('services.update', $service->id) : route('services.store') }}"
                    method="POST">
                    @csrf
                    @if ($isEdit)
                        @method('PUT')
                    @endif

                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="industry_id" class="form-label font-weight-medium">Industry</label>
                                    <span class="text-danger">*</span></label>
                                    <select class="form-select" id="industry_id" name="industry_id" required>
                                        <option value="" disabled {{ old('industry_id', $service->industry_id) ? '' : 'selected' }}>
                                            Select Industry
                                        </option>
                                        @foreach ($industries as $industry)
                                            <option value="{{ $industry->id }}"
                                                @selected(old('industry_id', $service->industry_id) == $industry->id)>
                                                {{ $industry->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('industry_id')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="name" class="form-label font-weight-medium">Service Name</label>
                                    <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name', $service->name) }}"
                                        placeholder="e.g. Web Development, SEO" required>
                                    @error('name')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="description" class="form-label font-weight-medium">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"
                                        placeholder="Brief description of the service">{{ old('description', $service->description) }}</textarea>
                                    @error('description')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            {{ $isEdit ? 'Update Service' : 'Save Service' }}
                        </button>
                        <a href="{{ route('services.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const industrySelect = document.getElementById('industry_id');
                const nameInput = document.getElementById('name');
                const descriptionInput = document.getElementById('description');

                // Store the initial industry value (useful on edit page load)
                let previousIndustryValue = industrySelect.value;

                industrySelect.addEventListener('change', function () {
                    // Sirf tab clear karo jab industry pehle se selected thi aur ab change hui
                    if (previousIndustryValue !== '') {
                        nameInput.value = '';
                        descriptionInput.value = '';
                    }
                    previousIndustryValue = industrySelect.value;
                });
            });
        </script>
    @endpush
</x-app-layout>