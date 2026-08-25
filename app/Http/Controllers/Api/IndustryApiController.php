<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Industry;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IndustryApiController extends Controller
{
    /**
     * Get list of all available industries for frontend & mobile registration.
     */
    public function index(): JsonResponse
    {
        $industries = Industry::where('status', true)->get(['id', 'name']);

        // Fallback if no status column filter returns empty
        if ($industries->isEmpty()) {
            $industries = Industry::all(['id', 'name']);
        }

        return response()->json($industries);
    }

    /**
     * Get list of services filtered by industry_ids.
     */
    public function services(Request $request): JsonResponse
    {
        $query = Service::query();

        $industryIds = $request->query('industry_ids');

        if (!empty($industryIds)) {
            $ids = is_array($industryIds) ? $industryIds : explode(',', $industryIds);
            $query->whereIn('industry_id', array_filter($ids));
        }

        $services = $query->get(['id', 'name', 'industry_id']);

        return response()->json($services);
    }
}
