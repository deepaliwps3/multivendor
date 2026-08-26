<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkflowTemplateRequest;
use App\Http\Resources\WorkflowTemplateResource;
use App\Models\Industry;
use App\Models\Service;
use App\Models\WorkflowTemplate;
use App\Models\WorkflowTemplateStage;
use App\Services\Backend\WorkflowTemplateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                    // $stages = $row->stages->map(function ($stage) {
                    //     return [
                    //         'id' => $stage->id,
                    //         'service_id' => $stage->service_id,
                    //         'sequence_no' => $stage->sequence_no,
                    //         'is_mandatory' => (bool) $stage->is_mandatory,
                    //     ];
                    // });

                    // $editBtn = '<button class="btn btn-sm btn-info me-1 edit-workflow-template-btn"
                    //                 data-id="' . $row->id . '"
                    //                 data-name="' . e($row->name) . '"
                    //                 data-industry-id="' . $row->industry_id . '"
                    //                 data-stages="' . e($stages->toJson()) . '">
                    //                 <i data-feather="edit-2" class="feather-icon"></i> Edit
                    //             </button>';
                    $editUrl = route('workflow-templates.edit', $row->id);
                    $editBtn = '<a href="' . $editUrl . '" class="btn btn-sm btn-info me-1">
                                    <i data-feather="edit-2" class="feather-icon"></i> Edit
                                </a>';

                    $deleteUrl = route('workflow-templates.destroy', $row->id);
                    $deleteBtn = '<button class="btn btn-sm btn-danger delete-workflow-template-btn" data-url="' . $deleteUrl . '">
                                    <i data-feather="trash-2" class="feather-icon"></i> Delete
                                </button>';

                    return $editBtn . $deleteBtn;
                })
                ->rawColumns(['stages_count', 'actions'])
                ->make(true);
        }

        return view('backend.workflowTemplates.index');

        // return view('backend.workflowTemplates.index', [
        //     'industries' => Industry::select('id', 'name')->orderBy('name')->get(),
        //     'services' => Service::select('id', 'name')->orderBy('name')->get(),
        // ]);
    }

    /**
     * Show the create page.
     */
    // public function create(): View
    // {
    //     return view('backend.workflowTemplates.create', [
    //         'industries' => Industry::select('id', 'name')->orderBy('name')->get(),
    //     ]);
    // }

    public function create(): View
    {
        return view('backend.workflowTemplates.create', [
            'industries' => $this->getIndustriesWithAvailability(),
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
     * Show the edit page.
     */
    // public function edit(WorkflowTemplate $workflowTemplate): View
    // {
    //     $workflowTemplate = $this->workflowTemplateService->getTemplateWithStages($workflowTemplate);

    //     return view('backend.workflowTemplates.edit', [
    //         'industries' => Industry::select('id', 'name')->orderBy('name')->get(),
    //         'workflowTemplate' => $workflowTemplate,
    //     ]);
    // }

    public function edit(WorkflowTemplate $workflowTemplate): View
    {
        $workflowTemplate = $this->workflowTemplateService->getTemplateWithStages($workflowTemplate);

        $stagesForJs = $workflowTemplate->stages->map(function ($stage) {
            return [
                'id' => $stage->id,
                'service_id' => $stage->service_id,
                'sequence_no' => $stage->sequence_no,
                'is_mandatory' => (bool) $stage->is_mandatory,
            ];
        })->values();

        return view('backend.workflowTemplates.edit', [
            // 'industries' => Industry::select('id', 'name')->orderBy('name')->get(),
            'industries' => $this->getIndustriesWithAvailability($workflowTemplate->id),
            'workflowTemplate' => $workflowTemplate,
            'stagesForJs' => $stagesForJs,
        ]);
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

    /**
     * Return services for a given industry, flagged with whether each is
     * already used in a saved workflow template stage (so the frontend can
     * disable it and force selection of a different, unused service).
     * Pass ?exclude_template_id=X on the edit page so the template's own
     * already-assigned services aren't disabled.
     */
    public function servicesByIndustry(Request $request, Industry $industry): JsonResponse
    {
        $usedServiceIds = WorkflowTemplateStage::whereHas('template', function ($q) use ($industry, $request) {
            $q->where('industry_id', $industry->id);

            if ($request->filled('exclude_template_id')) {
                $q->where('id', '!=', $request->integer('exclude_template_id'));
            }
        })
            ->pluck('service_id')
            ->unique()
            ->values();

        $services = Service::where('industry_id', $industry->id)
            ->select('id', 'name')
            ->orderBy('name')
            ->get()
            ->map(fn($service) => [
                'id' => $service->id,
                'name' => $service->name,
                'used' => $usedServiceIds->contains($service->id),
            ]);

        return response()->json($services);
    }

    protected function getIndustriesWithAvailability(?int $excludeTemplateId = null)
    {
        $industries = Industry::select('id', 'name')->orderBy('name')->get();

        $serviceCounts = Service::select('industry_id', DB::raw('count(*) as total'))
            ->groupBy('industry_id')
            ->pluck('total', 'industry_id');

        $usedCounts = WorkflowTemplateStage::query()
            ->join('workflow_templates', 'workflow_templates.id', '=', 'workflow_template_stages.template_id')
            ->when($excludeTemplateId, fn($q) => $q->where('workflow_templates.id', '!=', $excludeTemplateId))
            ->select('workflow_templates.industry_id', DB::raw('count(distinct workflow_template_stages.service_id) as used'))
            ->groupBy('workflow_templates.industry_id')
            ->pluck('used', 'industry_id');

        return $industries->map(function ($industry) use ($serviceCounts, $usedCounts) {
            $total = (int) ($serviceCounts[$industry->id] ?? 0);
            $used = (int) ($usedCounts[$industry->id] ?? 0);

            $industry->has_services = $total > 0;
            $industry->all_services_used = $total > 0 && $used >= $total;

            return $industry;
        });
    }
}
