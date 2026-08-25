<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VendorDashboardController extends Controller
{
    /**
     * Get current authenticated user's Vendor Profile details.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        $vendor = Vendor::where('user_id', $user->id)
            ->with(['industries', 'services'])
            ->first();

        if (!$vendor) {
            return response()->json([
                'id' => null,
                'business_name' => $user->name,
                'approval_status' => 'pending',
                'kyc_status' => 'pending',
                'industries' => [],
                'services' => [],
            ]);
        }

        return response()->json([
            'id' => $vendor->id,
            'business_name' => $vendor->business_name,
            'contact_person' => $vendor->contact_person,
            'address' => $vendor->address,
            'gst_number' => $vendor->gst_number,
            'approval_status' => $vendor->approval_status ?? 'pending',
            'rejection_reason' => $vendor->rejection_reason ?? null,
            'kyc_status' => $vendor->kyc_status ?? 'pending',
            'vendor_type' => $vendor->vendor_type ?? 'both',
            'industries' => $vendor->industries->map(fn($i) => ['id' => $i->id, 'name' => $i->name]),
            'services' => $vendor->services->map(fn($s) => ['id' => $s->id, 'name' => $s->name]),
        ]);
    }

    /**
     * Update current authenticated vendor's profile.
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:1000',
            'gst_number' => 'nullable|string|max:50',
            'industry_ids' => 'nullable|array',
            'industry_ids.*' => 'exists:industries,id',
            'service_ids' => 'nullable|array',
            'service_ids.*' => 'exists:services,id',
        ]);

        $vendor = Vendor::firstOrCreate(
            ['user_id' => $user->id],
            ['approval_status' => 'pending', 'kyc_status' => 'pending']
        );

        $vendor->update([
            'business_name' => $validated['business_name'],
            'contact_person' => $validated['contact_person'] ?? $vendor->contact_person,
            'address' => $validated['address'] ?? $vendor->address,
            'gst_number' => $validated['gst_number'] ?? $vendor->gst_number,
            'approval_status' => 'pending', // Resubmits for admin approval
            'rejection_reason' => null, // Clears previous rejection reason upon resubmission
        ]);

        if (isset($validated['industry_ids'])) {
            $vendor->industries()->sync($validated['industry_ids']);
        }

        if (isset($validated['service_ids'])) {
            $vendor->services()->sync($validated['service_ids']);
        }

        $vendor->load(['industries', 'services']);

        return response()->json([
            'message' => 'Profile updated and resubmitted for approval successfully',
            'profile' => [
                'id' => $vendor->id,
                'business_name' => $vendor->business_name,
                'contact_person' => $vendor->contact_person,
                'address' => $vendor->address,
                'gst_number' => $vendor->gst_number,
                'approval_status' => $vendor->approval_status ?? 'pending',
                'rejection_reason' => null,
                'kyc_status' => $vendor->kyc_status ?? 'pending',
                'vendor_type' => $vendor->vendor_type ?? 'both',
                'industries' => $vendor->industries->map(fn($i) => ['id' => $i->id, 'name' => $i->name]),
                'services' => $vendor->services->map(fn($s) => ['id' => $s->id, 'name' => $s->name]),
            ],
        ]);
    }

    /**
     * Get urgent dashboard alerts ("Action Needed").
     */
    public function alerts(Request $request): JsonResponse
    {
        return response()->json([
            'new_assignments' => 1,
            'awaiting_my_assignment' => 2,
            'overdue_stages' => 1,
            'overdue_details' => 'Polishing, Order #1234',
        ]);
    }

    /**
     * Get 4-grid metric summary cards.
     */
    public function summary(Request $request): JsonResponse
    {
        return response()->json([
            'active_orders' => 12,
            'assigned_to_me' => 5,
            'awaiting_my_assignment' => 2,
            'monthly_earnings' => '₹42,500',
        ]);
    }

    /**
     * Get recent activity feed items.
     */
    public function activity(Request $request): JsonResponse
    {
        return response()->json([
            [
                'id' => 1,
                'type' => 'stage_completed',
                'title' => 'Suresh Polish Works completed Polishing on Order #1234',
                'timestamp' => '2 hours ago',
                'icon' => 'check_circle',
            ],
            [
                'id' => 2,
                'type' => 'payment_received',
                'title' => 'Payment ₹5,000 received · Order #1230',
                'timestamp' => 'Yesterday',
                'icon' => 'payment',
            ],
            [
                'id' => 3,
                'type' => 'new_order_assigned',
                'title' => 'New order assigned to you · Order #1245',
                'timestamp' => 'Yesterday',
                'icon' => 'assignment',
            ],
        ]);
    }
}
