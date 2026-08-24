<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StaffPermissionRequest;
use App\Http\Resources\StaffPermissionResource;
use App\Models\StaffPermission;
use App\Models\User;
use App\Services\Backend\StaffPermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class StaffPermissionController extends Controller
{
    /**
     * Inject StaffPermissionService dependency.
     */
    public function __construct(
        protected StaffPermissionService $staffPermissionService
    ) {}

    /**
     * Display a listing of the staff permissions using Yajra DataTables server-side handling.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $query = $this->staffPermissionService->getStaffPermissionsQuery();

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('staff_id', function ($row) {
                    return $row->staff?->name ?? 'N/A';
                })
                ->addColumn('actions', function ($row) {
                    $editBtn = '<button class="btn btn-sm btn-info me-1 edit-staff-permission-btn" data-id="' . $row->id . '" data-staff-id="' . $row->staff_id . '" data-permission-key="' . e($row->permission_key) . '">
                                    <i data-feather="edit-2" class="feather-icon"></i> Edit
                                </button>';
                    $deleteUrl = route('staff-permissions.destroy', $row->id);
                    $deleteBtn = '<button class="btn btn-sm btn-danger delete-staff-permission-btn" data-url="' . $deleteUrl . '">
                                    <i data-feather="trash-2" class="feather-icon"></i> Delete
                                </button>';

                    return $editBtn . $deleteBtn;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('backend.staffPermissions.index', [
            'staff' => User::select('id', 'name')->orderBy('name')->get(),
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
     * Store a newly created staff permission in storage using DB transaction service.
     */
    public function store(StaffPermissionRequest $request): RedirectResponse|StaffPermissionResource|JsonResponse
    {
        try {
            $staffPermission = $this->staffPermissionService->createStaffPermission($request->validated());

            if ($request->wantsJson()) {
                return new StaffPermissionResource($staffPermission);
            }

            return redirect()->route('staff-permissions.index')->with('success', 'Staff permission created successfully.');
        } catch (Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Failed to create staff permission.'], 500);
            }

            return back()->withInput()->with('error', 'Failed to create staff permission. Please try again.');
        }
    }

    /**
     * Display the specified staff permission.
     */
    public function show(StaffPermission $staffPermission): StaffPermissionResource
    {
        return new StaffPermissionResource($staffPermission->load('staff'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified staff permission in storage using DB transaction service.
     */
    public function update(StaffPermissionRequest $request, StaffPermission $staffPermission): RedirectResponse|StaffPermissionResource|JsonResponse
    {
        try {
            $updatedStaffPermission = $this->staffPermissionService->updateStaffPermission($staffPermission, $request->validated());

            if ($request->wantsJson()) {
                return new StaffPermissionResource($updatedStaffPermission);
            }

            return redirect()->route('staff-permissions.index')->with('success', 'Staff permission updated successfully.');
        } catch (Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Failed to update staff permission.'], 500);
            }

            return back()->withInput()->with('error', 'Failed to update staff permission. Please try again.');
        }
    }

    /**
     * Remove the specified staff permission from storage using DB transaction service.
     */
    public function destroy(Request $request, StaffPermission $staffPermission): RedirectResponse|JsonResponse
    {
        try {
            $this->staffPermissionService->deleteStaffPermission($staffPermission);

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Staff permission deleted successfully.']);
            }

            return redirect()->route('staff-permissions.index')->with('success', 'Staff permission deleted successfully.');
        } catch (Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Failed to delete staff permission.'], 500);
            }

            return back()->with('error', 'Failed to delete staff permission. Please try again.');
        }
    }
}
