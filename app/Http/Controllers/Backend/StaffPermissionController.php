<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionAssignRequest;
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
    public function __construct(
        protected StaffPermissionService $staffPermissionService
    ) {}

    /**
     * Staff & Permissions listing page.
     * Columns: S.No, Staff details (name + email + phone), Permission name (count), Status, Actions.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $query = $this->staffPermissionService->getStaffListQuery();

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('staff_details', function ($row) {
                    return '<div class="fw-medium">' . e($row->name) . '</div>
                            <div class="text-muted small">' . e($row->email) . '</div>
                            <div class="text-muted small">' . e($row->phone) . '</div>';
                })
                ->addColumn('permission_name', function ($row) {
                    return $row->staff_permissions_count > 0
                        ? $row->staff_permissions_count . ' Permission' . ($row->staff_permissions_count > 1 ? 's' : '')
                        : '<span class="text-muted">No permissions</span>';
                })
                ->addColumn('status', function ($row) {
                    return $row->status
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-secondary">Inactive</span>';
                })
                ->addColumn('actions', function ($row) {
                    $editBtn = '<a href="' . route('staff.edit', $row->id) . '" class="btn btn-sm btn-info me-1" title="Edit">
                                    <i data-feather="edit-2" class="feather-icon"></i>
                                </a>';
                    $permissionBtn = '<a href="' . route('staff-permissions.permissions', $row->id) . '" class="btn btn-sm btn-primary" title="Permissions">
                                    <i data-feather="shield" class="feather-icon"></i>
                                </a>';

                    return $editBtn . $permissionBtn;
                })
                ->rawColumns(['staff_details', 'permission_name', 'status', 'actions'])
                ->make(true);
        }

        return view('backend.staffPermissions.index');
    }

    /**
     * Show the "Assign Permissions" matrix page for a staff member.
     */
    public function permissions(User $staff): View
    {
        return view('backend.staffPermissions.permissions', [
            'staff'  => $staff,
            'matrix' => $this->staffPermissionService->getPermissionMatrix($staff),
        ]);
    }

    /**
     * Save the checked permissions for a staff member.
     */
    public function savePermissions(PermissionAssignRequest $request, User $staff): RedirectResponse|JsonResponse
    {
        try {
            $this->staffPermissionService->syncPermissions($staff, $request->validated('permissions', []));

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Permissions updated successfully.']);
            }

            return redirect()
                ->route('staff-permissions.index')
                ->with('success', 'Permissions updated successfully.');
        } catch (Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Failed to update permissions.'], 500);
            }

            return back()->with('error', 'Failed to update permissions. Please try again.');
        }
    }
}
