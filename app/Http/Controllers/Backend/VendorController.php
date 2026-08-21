<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\VendorRequest;
use App\Http\Resources\VendorResource;
use App\Models\Industry;
use App\Models\User;
use App\Models\Vendor;
use App\Services\Backend\VendorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class VendorController extends Controller
{
    /**
     * Inject VendorService dependency.
     */
    public function __construct(
        protected VendorService $vendorService
    ) {}

    /**
     * Display a listing of vendors using Yajra DataTables server-side rendering.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $query = $this->vendorService->getVendorsQuery();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('user_info', function ($row) {
                    $name = e($row->user?->name ?? 'N/A');
                    $email = e($row->user?->email ?? 'N/A');

                    return '<div><strong>' . $name . '</strong><br><small class="text-muted">' . $email . '</small></div>';
                })
                ->addColumn('contact_person', function ($row) {
                    return e($row->contact_person ?? '-');
                })
                ->addColumn('gst_number', function ($row) {
                    return e($row->gst_number ?? '-');
                })
                ->editColumn('vendor_type', function ($row) {
                    return '<span class="badge bg-info text-capitalize">' . e($row->vendor_type) . '</span>';
                })
                ->editColumn('kyc_status', function ($row) {
                    $badgeClass = match ($row->kyc_status) {
                        'verified' => 'bg-success',
                        'rejected' => 'bg-danger',
                        default => 'bg-warning text-dark',
                    };

                    return '<span class="badge ' . $badgeClass . ' text-capitalize">' . e($row->kyc_status) . '</span>';
                })
                ->editColumn('approval_status', function ($row) {
                    $badgeClass = match ($row->approval_status) {
                        'approved' => 'bg-success',
                        'rejected' => 'bg-danger',
                        default => 'bg-warning text-dark',
                    };

                    return '<span class="badge ' . $badgeClass . ' text-capitalize">' . e($row->approval_status) . '</span>';
                })
                ->addColumn('actions', function ($row) {
                    $editUrl = route('vendors.edit', $row->id);
                    $editBtn = '<a href="' . $editUrl . '" class="btn btn-sm btn-info me-1">
                                    <i data-feather="edit-2" class="feather-icon"></i> Edit
                                </a>';
                    $deleteUrl = route('vendors.destroy', $row->id);
                    $deleteBtn = '<button class="btn btn-sm btn-danger delete-vendor-btn" data-url="' . $deleteUrl . '">
                                    <i data-feather="trash-2" class="feather-icon"></i> Delete
                                </button>';

                    return $editBtn . $deleteBtn;
                })
                ->rawColumns(['user_info', 'vendor_type', 'kyc_status', 'approval_status', 'actions'])
                ->make(true);
        }

        return view('backend.vendors.index');
    }

    /**
     * Show the single unified form for creating a new vendor.
     */
    public function create(): View
    {
        $vendor = new Vendor();
        $users = User::orderBy('name')->get();
        $industries = Industry::where('status', true)->orderBy('name')->get();

        return view('backend.vendors.create', compact('vendor', 'users', 'industries'));
    }

    /**
     * Store a newly created vendor in storage using DB transaction service.
     */
    public function store(VendorRequest $request): RedirectResponse|VendorResource|JsonResponse
    {
        try {
            $vendor = $this->vendorService->createVendor($request->validated());

            if ($request->wantsJson()) {
                return new VendorResource($vendor);
            }

            return redirect()->route('vendors.index')->with('success', 'Vendor created successfully.');
        } catch (Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Failed to create vendor.'], 500);
            }

            return back()->withInput()->with('error', 'Failed to create vendor. Please try again.');
        }
    }

    /**
     * Display the specified vendor.
     */
    public function show(Vendor $vendor): VendorResource
    {
        return new VendorResource($vendor->load(['user', 'industries']));
    }

    /**
     * Show the single unified form for editing an existing vendor.
     */
    public function edit(Vendor $vendor): View
    {
        $vendor->load('industries');
        $users = User::orderBy('name')->get();
        $industries = Industry::where('status', true)->orderBy('name')->get();
        $selectedIndustries = $vendor->industries->pluck('id')->toArray();

        return view('backend.vendors.create', compact('vendor', 'users', 'industries', 'selectedIndustries'));
    }

    /**
     * Update the specified vendor in storage using DB transaction service.
     */
    public function update(VendorRequest $request, Vendor $vendor): RedirectResponse|VendorResource|JsonResponse
    {
        try {
            $updatedVendor = $this->vendorService->updateVendor($vendor, $request->validated());

            if ($request->wantsJson()) {
                return new VendorResource($updatedVendor);
            }

            return redirect()->route('vendors.index')->with('success', 'Vendor updated successfully.');
        } catch (Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Failed to update vendor.'], 500);
            }

            return back()->withInput()->with('error', 'Failed to update vendor. Please try again.');
        }
    }

    /**
     * Remove the specified vendor from storage using DB transaction service.
     */
    public function destroy(Request $request, Vendor $vendor): RedirectResponse|JsonResponse
    {
        try {
            $this->vendorService->deleteVendor($vendor);

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Vendor deleted successfully.']);
            }

            return redirect()->route('vendors.index')->with('success', 'Vendor deleted successfully.');
        } catch (Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Failed to delete vendor.'], 500);
            }

            return back()->with('error', 'Failed to delete vendor. Please try again.');
        }
    }
}
