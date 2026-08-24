<?php

namespace App\Services\Backend;

use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ServiceService
{
    /**
     * Get all services ordered by latest.
     */
    public function getAllServices(): Collection
    {
        return Service::latest()->get();
    }

    /**
     * Get services query for Yajra DataTables.
     */
    public function getServicesQuery()
    {
        return Service::query()->with('industry')->latest();
    }

    /**
     * Get all services belonging to a specific industry.
     */
    public function getServicesByIndustry(int $industryId): Collection
    {
        return Service::query()
            ->where('industry_id', $industryId)
            ->latest()
            ->get();
    }

    /**
     * Create a new service using DB transactions.
     *
     * @throws Throwable
     */
    public function createService(array $data): Service
    {
        DB::beginTransaction();

        try {
            $service = Service::create($data);
            DB::commit();

            return $service;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Failed to create service: ' . $e->getMessage(), [
                'data' => $data,
                'exception' => $e,
            ]);
            throw $e;
        }
    }

    /**
     * Update an existing service using DB transactions.
     *
     * @throws Throwable
     */
    public function updateService(Service $service, array $data): Service
    {
        DB::beginTransaction();

        try {
            $service->update($data);
            DB::commit();

            return $service;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Failed to update service ID ' . $service->id . ': ' . $e->getMessage(), [
                'data' => $data,
                'exception' => $e,
            ]);
            throw $e;
        }
    }

    /**
     * Delete a service using DB transactions.
     *
     * @throws Throwable
     */
    public function deleteService(Service $service): bool
    {
        DB::beginTransaction();

        try {
            $deleted = (bool) $service->delete();
            DB::commit();

            return $deleted;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Failed to delete service ID ' . $service->id . ': ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            throw $e;
        }
    }
}
