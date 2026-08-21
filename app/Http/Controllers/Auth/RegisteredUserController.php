<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    private const ALLOWED_ROLES = ['superadmin', 'staff'];
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        abort_unless(Auth::user()?->isSuperadmin(), 403);

        return view('backend.auth.register', [
            'roles' => Role::whereIn('name', self::ALLOWED_ROLES)->get(),
        ]);
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        abort_unless(Auth::user()?->isSuperadmin(), 403);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', Rule::in(self::ALLOWED_ROLES)],
        ]);

        $role = Role::where('name', $request->role)->firstOrFail();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $role->id,
        ]);

        event(new Registered($user));

        // Deliberately NOT Auth::login($user) — the acting superadmin stays
        // logged in as themselves, the new account just gets created.
        return redirect()->route('dashboard')->with('status', 'Account created.');
    }
}
