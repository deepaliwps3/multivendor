<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndustryRequest;
use App\Http\Resources\IndustryResource;
use App\Models\Industry;
use App\Services\Backend\IndustryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class IndustryController extends Controller
{
    /**
     * Inject IndustryService dependency.
     */
    public function __construct(
        protected IndustryService $industryService
    ) {}

    /**
     * Display a listing of the industries using Yajra DataTables server-side handling.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $query = $this->industryService->getIndustriesQuery();

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('status', function ($row) {
                    if ($row->status) {
                        return '<span class="badge bg-success">Active</span>';
                    }
                    return '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('actions', function ($row) {
                    $editBtn = '<button class="btn btn-sm btn-info me-1 edit-industry-btn" data-id="' . $row->id . '" data-name="' . e($row->name) . '" data-status="' . ($row->status ? '1' : '0') . '">
                                    <i data-feather="edit-2" class="feather-icon"></i> Edit
                                </button>';
                    $deleteUrl = route('industries.destroy', $row->id);
                    $deleteBtn = '<button class="btn btn-sm btn-danger delete-industry-btn" data-url="' . $deleteUrl . '">
                                    <i data-feather="trash-2" class="feather-icon"></i> Delete
                                </button>';

                    return $editBtn . $deleteBtn;
                })
                ->rawColumns(['status', 'actions'])
                ->make(true);
        }

        return view('backend.industries.index');
    }

    /**
     * Store a newly created industry in storage using DB transaction service.
     */
    public function store(IndustryRequest $request): RedirectResponse|IndustryResource|JsonResponse
    {
        try {
            $industry = $this->industryService->createIndustry($request->validated());

            if ($request->wantsJson()) {
                return new IndustryResource($industry);
            }

            return redirect()->route('industries.index')->with('success', 'Industry created successfully.');
        } catch (Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Failed to create industry.'], 500);
            }

            return back()->withInput()->with('error', 'Failed to create industry. Please try again.');
        }
    }

    /**
     * Display the specified industry.
     */
    public function show(Industry $industry): IndustryResource
    {
        return new IndustryResource($industry);
    }

    /**
     * Update the specified industry in storage using DB transaction service.
     */
    public function update(IndustryRequest $request, Industry $industry): RedirectResponse|IndustryResource|JsonResponse
    {
        try {
            $updatedIndustry = $this->industryService->updateIndustry($industry, $request->validated());

            if ($request->wantsJson()) {
                return new IndustryResource($updatedIndustry);
            }

            return redirect()->route('industries.index')->with('success', 'Industry updated successfully.');
        } catch (Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Failed to update industry.'], 500);
            }

            return back()->withInput()->with('error', 'Failed to update industry. Please try again.');
        }
    }

    /**
     * Remove the specified industry from storage using DB transaction service.
     */
    public function destroy(Request $request, Industry $industry): RedirectResponse|JsonResponse
    {
        try {
            $this->industryService->deleteIndustry($industry);

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Industry deleted successfully.']);
            }

            return redirect()->route('industries.index')->with('success', 'Industry deleted successfully.');
        } catch (Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Failed to delete industry.'], 500);
            }

            return back()->with('error', 'Failed to delete industry. Please try again.');
        }
    }
}
