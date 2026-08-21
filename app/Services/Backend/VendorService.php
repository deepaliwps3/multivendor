<?php

namespace App\Services\Backend;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
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
            $vendor = Vendor::create([
                'user_id' => $data['user_id'],
                'business_name' => $data['business_name'],
                'contact_person' => $data['contact_person'] ?? null,
                'address' => $data['address'] ?? null,
                'gst_number' => $data['gst_number'] ?? null,
                'vendor_type' => $data['vendor_type'],
                'kyc_status' => $data['kyc_status'],
                'approval_status' => $data['approval_status'],
            ]);

            if (isset($data['industries']) && is_array($data['industries'])) {
                $vendor->industries()->sync($data['industries']);
            }

            DB::commit();

            return $vendor->load(['user', 'industries']);
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
            $vendor->update([
                'user_id' => $data['user_id'],
                'business_name' => $data['business_name'],
                'contact_person' => $data['contact_person'] ?? null,
                'address' => $data['address'] ?? null,
                'gst_number' => $data['gst_number'] ?? null,
                'vendor_type' => $data['vendor_type'],
                'kyc_status' => $data['kyc_status'],
                'approval_status' => $data['approval_status'],
            ]);

            if (isset($data['industries']) && is_array($data['industries'])) {
                $vendor->industries()->sync($data['industries']);
            } else {
                $vendor->industries()->detach();
            }

            DB::commit();

            return $vendor->load(['user', 'industries']);
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
