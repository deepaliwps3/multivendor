<?php

namespace App\Services\Backend;

use App\Models\StaffPermission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class StaffPermissionService
{
    /**
     * Get all staff permissions ordered by latest.
     */
    public function getAllStaffPermissions(): Collection
    {
        return StaffPermission::with('staff')->latest()->get();
    }

    /**
     * Get staff permissions query for Yajra DataTables.
     */
    public function getStaffPermissionsQuery()
    {
        return StaffPermission::query()->with('staff')->latest();
    }

    /**
     * Create a new staff permission using DB transactions.
     *
     * @throws Throwable
     */
    public function createStaffPermission(array $data): StaffPermission
    {
        DB::beginTransaction();

        try {
            $staffPermission = StaffPermission::create($data);
            DB::commit();

            return $staffPermission;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Failed to create staff permission: ' . $e->getMessage(), [
                'data' => $data,
                'exception' => $e,
            ]);
            throw $e;
        }
    }

    /**
     * Update an existing staff permission using DB transactions.
     *
     * @throws Throwable
     */
    public function updateStaffPermission(StaffPermission $staffPermission, array $data): StaffPermission
    {
        DB::beginTransaction();

        try {
            $staffPermission->update($data);
            DB::commit();

            return $staffPermission;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Failed to update staff permission ID ' . $staffPermission->id . ': ' . $e->getMessage(), [
                'data' => $data,
                'exception' => $e,
            ]);
            throw $e;
        }
    }

    /**
     * Delete a staff permission using DB transactions.
     *
     * @throws Throwable
     */
    public function deleteStaffPermission(StaffPermission $staffPermission): bool
    {
        DB::beginTransaction();

        try {
            $deleted = (bool) $staffPermission->delete();
            DB::commit();

            return $deleted;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Failed to delete staff permission ID ' . $staffPermission->id . ': ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            throw $e;
        }
    }
}
