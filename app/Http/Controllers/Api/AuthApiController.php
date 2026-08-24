<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthApiController extends Controller
{
    /**
     * Frontend User / Vendor Registration.
     */
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['nullable', 'string', 'in:vendor'],
            'address' => ['nullable', 'string'],
            'gst_number' => ['nullable', 'string', 'max:50'],
            'business_name' => ['nullable', 'string', 'max:255'],
            'industry_ids' => ['nullable', 'array'],
            'industry_ids.*' => ['integer', 'exists:industries,id'],
            'service_ids' => ['nullable', 'array'],
            'service_ids.*' => ['integer', 'exists:services,id'],
        ]);

        // Resolve requested role (e.g., 'vendor' or 'staff'), defaulting to 'vendor'
        $roleName = $data['role'] ?? 'vendor';
        $userRole = Role::where('name', strtolower($roleName))->first();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'role_id' => $userRole?->id,
        ]);

        // Create associated Vendor profile if business_name is provided or registering as vendor
        if (!empty($data['business_name']) || $roleName === 'vendor') {
            $vendor = Vendor::create([
                'user_id' => $user->id,
                'business_name' => $data['business_name'] ?? $data['name'],
                'contact_person' => $data['name'],
                'address' => $data['address'] ?? null,
                'gst_number' => $data['gst_number'] ?? null,
            ]);

            if (!empty($data['industry_ids'])) {
                $vendor->industries()->sync($data['industry_ids']);
            }

            if (!empty($data['service_ids'])) {
                $vendor->services()->sync($data['service_ids']);
            }
        }

        $token = $user->createToken('frontend-mobile-app')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful',
            'user' => $user->load('role'),
            'token' => $token,
        ], 201);
    }

    /**
     * Frontend Login Endpoint for Mobile & React SPA.
     */
    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid email or password.'],
            ]);
        }

        $deviceName = $data['device_name'] ?? 'frontend-mobile-app';
        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user->load('role'),
            'token' => $token,
        ]);
    }

    /**
     * Logout and revoke current device token.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out.',
        ]);
    }

    /**
     * Get authenticated user profile.
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user()->load('role'));
    }
}
