<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkflowTemplateRequest;
use App\Http\Resources\WorkflowTemplateResource;
use App\Models\Industry;
use App\Models\Service;
use App\Models\WorkflowTemplate;
use App\Services\Backend\WorkflowTemplateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class WorkflowTemplateController extends Controller
{
    /**
     * Inject WorkflowTemplateService dependency.
     */
    public function __construct(
        protected WorkflowTemplateService $workflowTemplateService
    ) {}

    /**
     * Display a listing of the workflow templates using Yajra DataTables server-side handling.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $query = $this->workflowTemplateService->getTemplatesQuery();

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('industry_name', function ($row) {
                    return $row->industry->name ?? '-';
                })
                ->editColumn('stages_count', function ($row) {
                    return '<span class="badge bg-secondary">' . $row->stages_count . '</span>';
                })
                ->addColumn('actions', function ($row) {
                    $stages = $row->stages->map(function ($stage) {
                        return [
                            'id' => $stage->id,
                            'service_id' => $stage->service_id,
                            'sequence_no' => $stage->sequence_no,
                            'is_mandatory' => (bool) $stage->is_mandatory,
                        ];
                    });

                    $editBtn = '<button class="btn btn-sm btn-info me-1 edit-workflow-template-btn"
                                    data-id="' . $row->id . '"
                                    data-name="' . e($row->name) . '"
                                    data-industry-id="' . $row->industry_id . '"
                                    data-stages="' . e($stages->toJson()) . '">
                                    <i data-feather="edit-2" class="feather-icon"></i> Edit
                                </button>';
                    $deleteUrl = route('workflow-templates.destroy', $row->id);
                    $deleteBtn = '<button class="btn btn-sm btn-danger delete-workflow-template-btn" data-url="' . $deleteUrl . '">
                                    <i data-feather="trash-2" class="feather-icon"></i> Delete
                                </button>';

                    return $editBtn . $deleteBtn;
                })
                ->rawColumns(['stages_count', 'actions'])
                ->make(true);
        }

        return view('backend.workflowTemplates.index', [
            'industries' => Industry::select('id', 'name')->orderBy('name')->get(),
            'services' => Service::select('id', 'name')->orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created workflow template in storage using DB transaction service.
     */
    public function store(WorkflowTemplateRequest $request): RedirectResponse|WorkflowTemplateResource|JsonResponse
    {
        try {
            $template = $this->workflowTemplateService->createTemplate($request->validated());

            if ($request->wantsJson()) {
                return new WorkflowTemplateResource($template);
            }

            return redirect()->route('workflow-templates.index')->with('success', 'Workflow template created successfully.');
        } catch (Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Failed to create workflow template.'], 500);
            }

            return back()->withInput()->with('error', 'Failed to create workflow template. Please try again.');
        }
    }

    /**
     * Update the specified workflow template in storage using DB transaction service.
     */
    public function update(WorkflowTemplateRequest $request, WorkflowTemplate $workflowTemplate): RedirectResponse|WorkflowTemplateResource|JsonResponse
    {
        try {
            $updatedTemplate = $this->workflowTemplateService->updateTemplate($workflowTemplate, $request->validated());

            if ($request->wantsJson()) {
                return new WorkflowTemplateResource($updatedTemplate);
            }

            return redirect()->route('workflow-templates.index')->with('success', 'Workflow template updated successfully.');
        } catch (Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Failed to update workflow template.'], 500);
            }

            return back()->withInput()->with('error', 'Failed to update workflow template. Please try again.');
        }
    }

    /**
     * Remove the specified workflow template from storage using DB transaction service.
     */
    public function destroy(Request $request, WorkflowTemplate $workflowTemplate): RedirectResponse|JsonResponse
    {
        try {
            $this->workflowTemplateService->deleteTemplate($workflowTemplate);

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Workflow template deleted successfully.']);
            }

            return redirect()->route('workflow-templates.index')->with('success', 'Workflow template deleted successfully.');
        } catch (Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Failed to delete workflow template.'], 500);
            }

            return back()->with('error', 'Failed to delete workflow template. Please try again.');
        }
    }
}
