<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StaffRequest;
use App\Http\Requests\StaffStoreRequest;
use App\Http\Requests\StaffUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Throwable;

class StaffController extends Controller
{
    /**
     * Role id used for every user created from this screen.
     * Adjust if your `roles` table uses a different id for "staff".
     */
    protected const STAFF_ROLE_ID = 2;

    /**
     * Show the "Add Staff" page.
     */
    public function create(): View
    {
        return view('backend.staff.create');
    }

    /**
     * Store a new staff user. Always saved with role_id = 2 (staff).
     */
    public function store(StaffRequest $request): RedirectResponse
    {
        try {
            User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'phone'    => $request->phone,
                'password' => Hash::make($request->password),
                'address'  => $request->address,
                'role_id'  => self::STAFF_ROLE_ID,
                'status'   => 1,
            ]);

            return redirect()
                ->route('staff-permissions.index')
                ->with('success', 'Staff created successfully.');
        } catch (Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Failed to create staff. Please try again.');
        }
    }

    /**
     * Show the "Edit Staff" page.
     */
    public function edit(User $staff): View
    {
        return view('backend.staff.edit', ['staff' => $staff]);
    }

    /**
     * Update an existing staff user. Password is optional on edit.
     */
    public function update(StaffRequest $request, User $staff): RedirectResponse
    {
        try {
            $data = $request->only(['name', 'email', 'phone', 'address']);

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $staff->update($data);

            return redirect()
                ->route('staff-permissions.index')
                ->with('success', 'Staff updated successfully.');
        } catch (Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Failed to update staff. Please try again.');
        }
    }
}
