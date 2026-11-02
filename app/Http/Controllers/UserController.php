<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display all users.
     */
    public function index(): View
    {
        $users = User::query()
            ->latest()
            ->paginate(15);

        return view('users.index', compact('users'));
    }


    /**
     * Show create user page.
     */
    public function create(): View
    {
        $roles = [
            'ed',
            'manager',
            'staff',
        ];

        return view('users.create', compact('roles'));
    }


    /**
     * Store a new user.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'role' => [
                'required',
                'in:admin,ed,manager,staff',
            ],

            'password' => [
                'required',
                'confirmed',
                Password::defaults(),
            ],
        ]);


        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);


        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }


    /**
     * Show edit user page.
     */
    public function edit(User $user): View
    {
        $roles = [
            'admin',
            'ed',
            'manager',
            'staff',
        ];

        return view('users.edit', compact(
            'user',
            'roles'
        ));
    }


    /**
     * Update existing user.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            'role' => [
                'required',
                'in:admin,ed,manager,staff',
            ],

            'password' => [
                'nullable',
                'confirmed',
                Password::defaults(),
            ],
        ]);


        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];


        /*
        |--------------------------------------------------------------------------
        | Only change password when entered
        |--------------------------------------------------------------------------
        */
        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }


        $user->save();


        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully.');
    }


    /**
     * Delete user.
     */
    public function destroy(User $user): RedirectResponse
    {
        /*
        |--------------------------------------------------------------------------
        | Prevent deleting your own account
        |--------------------------------------------------------------------------
        */
        if (auth()->id() === $user->id) {
            return redirect()
                ->route('users.index')
                ->with('error', 'You cannot delete your own account.');
        }


        $user->delete();


        return redirect()
            ->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}
