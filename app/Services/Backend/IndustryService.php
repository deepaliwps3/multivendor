<?php

namespace App\Services\Backend;

use App\Models\Industry;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class IndustryService
{
    /**
     * Get all industries ordered by latest.
     */
    public function getAllIndustries(): Collection
    {
        return Industry::latest()->get();
    }

    /**
     * Get industries query for Yajra DataTables.
     */
    public function getIndustriesQuery()
    {
        return Industry::query()->latest();
    }

    /**
     * Create a new industry using DB transactions.
     *
     * @throws Throwable
     */
    public function createIndustry(array $data): Industry
    {
        DB::beginTransaction();

        try {
            $industry = Industry::create($data);
            DB::commit();

            return $industry;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Failed to create industry: ' . $e->getMessage(), [
                'data' => $data,
                'exception' => $e,
            ]);
            throw $e;
        }
    }

    /**
     * Update an existing industry using DB transactions.
     *
     * @throws Throwable
     */
    public function updateIndustry(Industry $industry, array $data): Industry
    {
        DB::beginTransaction();

        try {
            $industry->update($data);
            DB::commit();

            return $industry;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Failed to update industry ID ' . $industry->id . ': ' . $e->getMessage(), [
                'data' => $data,
                'exception' => $e,
            ]);
            throw $e;
        }
    }

    /**
     * Delete an industry using DB transactions.
     *
     * @throws Throwable
     */
    public function deleteIndustry(Industry $industry): bool
    {
        DB::beginTransaction();

        try {
            $deleted = (bool) $industry->delete();
            DB::commit();

            return $deleted;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Failed to delete industry ID ' . $industry->id . ': ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            throw $e;
        }
    }
}
