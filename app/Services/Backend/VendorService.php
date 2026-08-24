<?php

namespace App\Services\Backend;

use App\Models\Role;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Throwable;

class VendorService
{
    /**
     * Get query for Yajra DataTables.
     */
    public function getVendorsQuery(): Builder
    {
        return Vendor::with(['user', 'industries'])->latest();
    }

    /**
     * Get all vendors with relations.
     */
    public function getAllVendors(): Collection
    {
        return Vendor::with(['user', 'industries'])->latest()->get();
    }

    /**
     * Create a new vendor inside DB transaction.
     *
     * @throws Throwable
     */
    public function createVendor(array $data): Vendor
    {
        DB::beginTransaction();

        try {
            $vendorRole = Role::where('name', 'vendor')->first();

            $user = User::create([
                'name' => $data['contact_person'] ?? $data['business_name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make($data['password'] ?? 'password'),
                'role_id' => $vendorRole?->id,
            ]);

            $vendor = Vendor::create([
                'user_id' => $user->id,
                'business_name' => $data['business_name'],
                'contact_person' => $data['contact_person'] ?? null,
                'address' => $data['address'] ?? null,
                'gst_number' => $data['gst_number'] ?? null,
                // 'vendor_type' => $data['vendor_type'],
                'kyc_status' => $data['kyc_status'],
                'approval_status' => $data['approval_status'],
                'rejection_reason' => $data['approval_status'] === 'rejected' ? ($data['rejection_reason'] ?? null) : null,
            ]);

            if (isset($data['industries']) && is_array($data['industries'])) {
                $vendor->industries()->sync($data['industries']);
            }

            if (isset($data['services']) && is_array($data['services'])) {
                $vendor->services()->sync($data['services']);
            }

            DB::commit();

            return $vendor->load(['user', 'industries', 'services']);
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Failed to create vendor: ' . $e->getMessage(), [
                'data' => $data,
                'exception' => $e,
            ]);
            throw $e;
        }
    }

    /**
     * Update an existing vendor inside DB transaction.
     *
     * @throws Throwable
     */
    public function updateVendor(Vendor $vendor, array $data): Vendor
    {
        DB::beginTransaction();

        try {
            if ($vendor->user) {
                $userData = [
                    'name' => $data['contact_person'] ?? $data['business_name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'] ?? null,
                ];
                if (!empty($data['password'])) {
                    $userData['password'] = Hash::make($data['password']);
                }
                $vendor->user->update($userData);
            }

            $vendor->update([
                'business_name' => $data['business_name'],
                'contact_person' => $data['contact_person'] ?? null,
                'address' => $data['address'] ?? null,
                'gst_number' => $data['gst_number'] ?? null,
                // 'vendor_type' => $data['vendor_type'],
                'kyc_status' => $data['kyc_status'],
                'approval_status' => $data['approval_status'],
                'rejection_reason' => $data['approval_status'] === 'rejected' ? ($data['rejection_reason'] ?? $vendor->rejection_reason) : null,
            ]);

            if (isset($data['industries']) && is_array($data['industries'])) {
                $vendor->industries()->sync($data['industries']);
            } else {
                $vendor->industries()->detach();
            }

            if (isset($data['services']) && is_array($data['services'])) {
                $vendor->services()->sync($data['services']);
            } else {
                $vendor->services()->detach();
            }

            DB::commit();

            return $vendor->load(['user', 'industries', 'services']);
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Failed to update vendor ID ' . $vendor->id . ': ' . $e->getMessage(), [
                'data' => $data,
                'exception' => $e,
            ]);
            throw $e;
        }
    }

    /**
     * Delete a vendor inside DB transaction.
     *
     * @throws Throwable
     */
    public function deleteVendor(Vendor $vendor): bool
    {
        DB::beginTransaction();

        try {
            $vendor->industries()->detach();
            $deleted = (bool) $vendor->delete();
            DB::commit();

            return $deleted;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Failed to delete vendor ID ' . $vendor->id . ': ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            throw $e;
        }
    }
}
