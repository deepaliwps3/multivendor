<x-app-layout>
    <!-- Breadcrumb -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-12 col-sm-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Workflow Templates</h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">Workflow Templates</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-12 col-sm-5 align-self-center mt-3 mt-sm-0">
                <div class="customize-input float-sm-end">
                    <button type="button" class="btn btn-primary rounded-pill px-4 w-100 w-sm-auto"
                        data-bs-toggle="modal" data-bs-target="#addWorkflowTemplateModal">
                        <i data-feather="plus" class="feather-icon me-1"></i> Add Workflow Template
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

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
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
                        <h4 class="card-title">Workflow Templates List</h4>
                        <h6 class="card-subtitle mb-4 text-muted">Manage workflow templates and their stages with
                            server-side DataTables.</h6>
                        <div class="table-responsive">
                            <table id="workflow-templates-table"
                                class="table border table-striped table-bordered text-nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Name</th>
                                        <th>Industry</th>
                                        <th>Stages</th>
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

    <!-- Add Workflow Template Modal -->
    <div class="modal fade" id="addWorkflowTemplateModal" tabindex="-1" aria-labelledby="addWorkflowTemplateModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form action="{{ route('workflow-templates.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addWorkflowTemplateModalLabel">Add New Workflow Template</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="industry_id" class="form-label font-weight-medium">Industry</label>
                            <select class="form-select" id="industry_id" name="industry_id" required>
                                <option value="" selected disabled>Select Industry</option>
                                @foreach ($industries as $industry)
                                    <option value="{{ $industry->id }}">{{ $industry->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label font-weight-medium">Template Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="e.g. Standard Onboarding Flow" required>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label font-weight-medium mb-0">Stages</label>
                            <button type="button" class="btn btn-sm btn-outline-primary add-stage-btn"
                                data-container="#addStagesContainer">
                                <i data-feather="plus" class="feather-icon me-1"></i> Add Stage
                            </button>
                        </div>
                        <div id="addStagesContainer"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Workflow Template</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Workflow Template Modal -->
    <div class="modal fade" id="editWorkflowTemplateModal" tabindex="-1" aria-labelledby="editWorkflowTemplateModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="editWorkflowTemplateForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editWorkflowTemplateModalLabel">Edit Workflow Template</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_industry_id" class="form-label font-weight-medium">Industry</label>
                            <select class="form-select" id="edit_industry_id" name="industry_id" required>
                                <option value="" disabled>Select Industry</option>
                                @foreach ($industries as $industry)
                                    <option value="{{ $industry->id }}">{{ $industry->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_name" class="form-label font-weight-medium">Template Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label font-weight-medium mb-0">Stages</label>
                            <button type="button" class="btn btn-sm btn-outline-primary add-stage-btn"
                                data-container="#editStagesContainer">
                                <i data-feather="plus" class="feather-icon me-1"></i> Add Stage
                            </button>
                        </div>
                        <div id="editStagesContainer"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Workflow Template</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Stage row template (JS se dono modals ke liye clone hoga) -->
    <template id="stageRowTemplate">
        <div class="row g-2 align-items-center stage-row mb-2">
            <input type="hidden" class="stage-id-input">
            <div class="col-5">
                <select class="form-select stage-service-select" required>
                    <option value="" selected disabled>Select Service</option>
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-3">
                <input type="number" min="1" class="form-control stage-sequence-input"
                    placeholder="Sequence No." required>
            </div>
            <div class="col-3">
                <div class="form-check form-switch mt-2">
                    <input type="checkbox" class="form-check-input stage-mandatory-input" checked>
                    <label class="form-check-label">Mandatory</label>
                </div>
            </div>
            <div class="col-1 text-end">
                <button type="button" class="btn btn-sm btn-outline-danger remove-stage-btn">
                    <i data-feather="trash-2" class="feather-icon"></i>
                </button>
            </div>
        </div>
    </template>

    <!-- DataTables Scripts -->
    @push('scripts')
        <script>
            $(document).ready(function() {
                var table = $('#workflow-templates-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('workflow-templates.index') }}",
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
                            data: 'industry_name',
                            name: 'industry.name'
                        },
                        {
                            data: 'stages_count',
                            name: 'stages_count',
                            orderable: false,
                            searchable: false
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

                var addStageIndex = 0;
                var editStageIndex = 0;

                // Add a stage row into the given container, wired to the given field-name prefix
                function addStageRow(containerSelector, namePrefix, index, stage) {
                    var tpl = document.getElementById('stageRowTemplate');
                    var clone = document.importNode(tpl.content, true);
                    var $row = $(clone).find('.stage-row').addBack('.stage-row');

                    $row.find('.stage-id-input')
                        .attr('name', namePrefix + '[' + index + '][id]')
                        .val(stage && stage.id ? stage.id : '');

                    $row.find('.stage-service-select')
                        .attr('name', namePrefix + '[' + index + '][service_id]')
                        .val(stage ? stage.service_id : '');

                    $row.find('.stage-sequence-input')
                        .attr('name', namePrefix + '[' + index + '][sequence_no]')
                        .val(stage ? stage.sequence_no : '');

                    $row.find('.stage-mandatory-input')
                        .attr('name', namePrefix + '[' + index + '][is_mandatory]')
                        .prop('checked', stage ? !!stage.is_mandatory : true);

                    $(containerSelector).append($row);
                    if (typeof feather !== 'undefined') feather.replace();
                }

                // Add Stage buttons (both modals)
                $(document).on('click', '.add-stage-btn', function() {
                    var container = $(this).data('container');

                    if (container === '#addStagesContainer') {
                        addStageRow(container, 'stages', addStageIndex++, null);
                    } else {
                        addStageRow(container, 'stages', editStageIndex++, null);
                    }
                });

                // Remove Stage row
                $(document).on('click', '.remove-stage-btn', function() {
                    $(this).closest('.stage-row').remove();
                });

                // Reset the Add modal every time it opens, with one blank stage row
                $('#addWorkflowTemplateModal').on('show.bs.modal', function() {
                    $(this).find('form')[0].reset();
                    $('#addStagesContainer').empty();
                    addStageIndex = 0;
                    addStageRow('#addStagesContainer', 'stages', addStageIndex++, null);
                });

                // Trigger Edit Modal dynamically
                $(document).on('click', '.edit-workflow-template-btn', function() {
                    var id = $(this).data('id');
                    var name = $(this).data('name');
                    var industryId = $(this).data('industry-id');
                    var stages = $(this).data('stages'); // jQuery auto-parses JSON from data-attribute

                    var updateUrl = "{{ route('workflow-templates.update', ':id') }}".replace(':id', id);
                    $('#editWorkflowTemplateForm').attr('action', updateUrl);
                    $('#edit_name').val(name);
                    $('#edit_industry_id').val(industryId);

                    $('#editStagesContainer').empty();
                    editStageIndex = 0;

                    if (Array.isArray(stages) && stages.length) {
                        stages.forEach(function(stage) {
                            addStageRow('#editStagesContainer', 'stages', editStageIndex++, stage);
                        });
                    } else {
                        addStageRow('#editStagesContainer', 'stages', editStageIndex++, null);
                    }

                    $('#editWorkflowTemplateModal').modal('show');
                });

                // Handle AJAX Delete
                $(document).on('click', '.delete-workflow-template-btn', function() {
                    if (confirm('Are you sure you want to delete this workflow template?')) {
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
                                alert('Failed to delete workflow template.');
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>