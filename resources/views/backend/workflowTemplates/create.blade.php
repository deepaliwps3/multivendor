<x-app-layout>
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-12">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Add Workflow Template</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('workflow-templates.index') }}">Workflow Templates</a></li>
                        <li class="breadcrumb-item text-muted active" aria-current="page">Add</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

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
                <form id="workflowTemplateForm" action="{{ route('workflow-templates.store') }}" method="POST">
                    @csrf
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="industry_id" class="form-label font-weight-medium">Industry</label>
                                    <span class="text-danger">*</span></label>
                                    <select class="form-select" id="industry_id" name="industry_id" required>
                                        <option value="" selected disabled>Select Industry</option>
                                        @foreach ($industries as $industry)
                                            <option value="{{ $industry->id }}"
                                                {{ old('industry_id') == $industry->id ? 'selected' : '' }}
                                                @if(($industry->all_services_used || !$industry->has_services) && old('industry_id') != $industry->id) disabled @endif>
                                                {{ $industry->name }}
                                                @if(!$industry->has_services)
                                                    (no services available)
                                                @elseif($industry->all_services_used)
                                                    (all services already used)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label for="name" class="form-label font-weight-medium">Template Name</label>
                                    <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name') }}" placeholder="e.g. Standard Onboarding Flow" required>
                                </div>

                                <hr>

                                <div class="col-12 col-md-6 mb-3 d-flex justify-content-between align-items-center">
                                    <label class="form-label font-weight-medium mb-0">Stages</label>
                                    <span class="text-danger">*</span></label>
                                    <button type="button" id="addStageBtn" class="btn btn-sm btn-outline-primary">
                                        <i data-feather="plus" class="feather-icon me-1"></i> Add Stage
                                    </button>
                                </div>
                                <div id="industryError" class="text-danger small mb-2 d-none">
                                    Please select an industry first.
                                </div>
                                <div id="stagesContainer"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('workflow-templates.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" id="saveWorkflowTemplateBtn" class="btn btn-primary" disabled>
                            Save Workflow Template
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            window.workflowTemplateFormConfig = {
                servicesUrlTemplate: "{{ route('industries.services', ['industry' => '__INDUSTRY_ID__']) }}",
                excludeTemplateId: null,
                initialStages: []
            };
        </script>
        <script src="{{ asset('js/workflow-template-form.js') }}"></script>
    @endpush
</x-app-layout>