<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Models\Industry;
use App\Services\Backend\ServiceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class ServiceController extends Controller
{
    /**
     * Inject ServiceService dependency.
     */
    public function __construct(
        protected ServiceService $serviceService
    ) {}

    /**
     * Display a listing of the services using Yajra DataTables server-side handling.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $query = $this->serviceService->getServicesQuery();

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('industry', function ($row) {
                    return $row->industry->name ?? '-';
                })
                ->editColumn('description', function ($row) {
                    return Str::limit($row->description, 60);
                })
                ->filterColumn('industry', function ($query, $keyword) {
                    $query->whereHas('industry', function ($q) use ($keyword) {
                        $q->where('name', 'LIKE', "%{$keyword}%");
                    });
                })
                ->orderColumn('industry', function ($query, $order) {
                    $query->join('industries', 'services.industry_id', '=', 'industries.id')
                        ->orderBy('industries.name', $order)
                        ->select('services.*');
                })
                ->addColumn('actions', function ($row) {
                    $editBtn = '<button class="btn btn-sm btn-info me-1 edit-service-btn" data-id="' . $row->id . '" data-industry_id="' . $row->industry_id . '" data-name="' . e($row->name) . '" data-description="' . e($row->description) . '">
                                    <i data-feather="edit-2" class="feather-icon"></i> Edit
                                </button>';
                    $deleteUrl = route('services.destroy', $row->id);
                    $deleteBtn = '<button class="btn btn-sm btn-danger delete-service-btn" data-url="' . $deleteUrl . '">
                                    <i data-feather="trash-2" class="feather-icon"></i> Delete
                                </button>';

                    return $editBtn . $deleteBtn;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('backend.services.index', [
            'industries' => Industry::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created service in storage using DB transaction service.
     */
    public function store(ServiceRequest $request): RedirectResponse|ServiceResource|JsonResponse
    {
        try {
            $service = $this->serviceService->createService($request->validated());

            if ($request->wantsJson()) {
                return new ServiceResource($service);
            }

            return redirect()->route('services.index')->with('success', 'Service created successfully.');
        } catch (Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Failed to create service.'], 500);
            }

            return back()->withInput()->with('error', 'Failed to create service. Please try again.');
        }
    }

    /**
     * Display the specified service.
     */
    public function show(Service $service): ServiceResource
    {
        return new ServiceResource($service->load('industry'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified service in storage using DB transaction service.
     */
    public function update(ServiceRequest $request, Service $service): RedirectResponse|ServiceResource|JsonResponse
    {
        try {
            $updatedService = $this->serviceService->updateService($service, $request->validated());

            if ($request->wantsJson()) {
                return new ServiceResource($updatedService);
            }

            return redirect()->route('services.index')->with('success', 'Service updated successfully.');
        } catch (Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Failed to update service.'], 500);
            }

            return back()->withInput()->with('error', 'Failed to update service. Please try again.');
        }
    }

    /**
     * Remove the specified service from storage using DB transaction service.
     */
    public function destroy(Request $request, Service $service): RedirectResponse|JsonResponse
    {
        try {
            $this->serviceService->deleteService($service);

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Service deleted successfully.']);
            }

            return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
        } catch (Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Failed to delete service.'], 500);
            }

            return back()->with('error', 'Failed to delete service. Please try again.');
        }
    }
}
