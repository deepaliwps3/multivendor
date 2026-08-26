<?php

namespace App\Services\Backend;

use App\Models\Permission;
use App\Models\StaffPermission;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class StaffPermissionService
{
    protected const STAFF_ROLE_ID = 2;

    /**
     * Query used by the Staff & Permissions index DataTable.
     * Lists every staff user (role_id = 2) with a count of assigned permissions.
     */
    public function getStaffListQuery()
    {
        return User::query()
            ->where('role_id', self::STAFF_ROLE_ID)
            ->withCount('staffPermissions')
            ->latest();
    }

    /**
     * All modules/actions grouped, with the ids of permissions already
     * assigned to the given staff member marked as checked.
     *
     * Returns a Collection keyed by module name, each value a Collection of
     * ['id' => 1, 'label' => 'View', 'checked' => true] arrays.
     */
    public function getPermissionMatrix(User $staff)
    {
        $assignedIds = StaffPermission::query()
            ->where('staff_id', $staff->id)
            ->pluck('permission_id')
            ->flip();

        return Permission::query()
            ->orderBy('module')
            ->orderBy('id')
            ->get()
            ->groupBy('module')
            ->map(function ($permissions) use ($assignedIds) {
                return $permissions->map(fn($permission) => [
                    'id'      => $permission->id,
                    'label'   => $permission->label,
                    'checked' => $assignedIds->has($permission->id),
                ]);
            });
    }

    /**
     * Replace a staff member's permissions with the given permission id list.
     *
     * @throws Throwable
     */
    public function syncPermissions(User $staff, array $permissionIds): void
    {
        DB::beginTransaction();

        try {
            StaffPermission::where('staff_id', $staff->id)->delete();

            foreach ($permissionIds as $permissionId) {
                StaffPermission::create([
                    'uuid'          => (string) Str::uuid(),
                    'staff_id'      => $staff->id,
                    'permission_id' => $permissionId,
                ]);
            }

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Failed to sync permissions for staff ID ' . $staff->id . ': ' . $e->getMessage(), [
                'permission_ids' => $permissionIds,
                'exception'      => $e,
            ]);
            throw $e;
        }
    }
}
