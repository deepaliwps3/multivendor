<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
      /**
     * Only these two roles can ever come through this controller.
     * Vendor auth is a separate concern, owned by the frontend/its own API.
     */
    private const ALLOWED_ROLES = ['superadmin', 'staff'];

    /**
     * Create a new superadmin or staff account.
     * Requires a valid token from an EXISTING superadmin — this is not a
     * public sign-up endpoint. Letting anyone hit this and pick
     * role=superadmin would be a serious hole, so it sits behind
     * auth:sanctum in routes/api.php AND double-checks the role here.
     */
    public function register(Request $request): JsonResponse
    {
        abort_unless($request->user()?->isSuperadmin(), 403, 'Only a superadmin can create admin accounts.');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(self::ALLOWED_ROLES)],
        ]);

        $role = Role::where('name', $data['role'])->firstOrFail();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $role->id,
        ]);

        return response()->json([
            'user' => $user->load('role'),
        ], 201);
    }

    /**
     * Login: issues a Sanctum personal access token.
     * Client stores it (e.g. secure storage on native) and sends it as
     * `Authorization: Bearer {token}` on every subsequent request —
     * no session cookie involved, so it holds up fine for a long-lived
     * React/NativePHP client.
     */
    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['required', 'string'], // e.g. "web", "desktop-app"
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        if (! in_array($user->role?->name, self::ALLOWED_ROLES)) {
            throw ValidationException::withMessages([
                'email' => ['This login is for admin panel accounts only.'],
            ]);
        }

        $token = $user->createToken($data['device_name'])->plainTextToken;

        return response()->json([
            'user' => $user->load('role'),
            'token' => $token,
        ]);
    }

    /**
     * Revokes only the token used for THIS request — other devices/sessions
     * stay logged in. Matches how a native app should behave.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out.']);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user()->load('role'));
    }
}
