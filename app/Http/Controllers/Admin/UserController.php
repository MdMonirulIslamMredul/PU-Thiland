<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\PhoneNumber;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('roles')
            ->where('is_admin', false)
            ->where('email', '<>', 'supermredul@admin.com')
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = '%' . $request->input('q') . '%';

                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', $search)
                        ->orWhere('email', 'like', $search)
                        ->orWhere('phone', 'like', $search);
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'search' => $request->input('q'),
        ]);
    }

    public function admins(Request $request)
    {
        $users = User::with('roles')
            ->where('is_admin', true)
            ->where('email', '<>', 'supermredul@admin.com')
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = '%' . $request->input('q') . '%';

                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', $search)
                        ->orWhere('email', 'like', $search)
                        ->orWhere('phone', 'like', $search);
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'pageTitle' => 'Admins',
            'search' => $request->input('q'),
        ]);
    }

    public function create()
    {
        return view('admin.users.create', [
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        $normalizedPhone = $this->normalizePhone($data['phone'] ?? null);

        if ($normalizedPhone === false) {
            return back()
                ->withErrors(['phone' => 'Please enter a valid Bangladesh or China phone number.'])
                ->withInput();
        }

        if ($normalizedPhone !== null && User::where('phone', $normalizedPhone)->exists()) {
            return back()
                ->withErrors(['phone' => 'This phone number is already in use.'])
                ->withInput();
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $normalizedPhone,
            'password' => $data['password'],
            'is_admin' => ! empty($data['roles'] ?? []),
        ]);

        $user->syncRoles($data['roles'] ?? []);

        return redirect()->route('admin.users.index')->with('success', 'User registered successfully.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', [
            'user' => $user,
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:30'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        $normalizedPhone = $this->normalizePhone($data['phone'] ?? null);

        if ($normalizedPhone === false) {
            return back()
                ->withErrors(['phone' => 'Please enter a valid Bangladesh or China phone number.'])
                ->withInput();
        }

        if ($normalizedPhone !== null && User::where('phone', $normalizedPhone)->whereKeyNot($user->id)->exists()) {
            return back()
                ->withErrors(['phone' => 'This phone number is already in use.'])
                ->withInput();
        }


        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $normalizedPhone;
        $user->is_admin = ! empty($data['roles'] ?? []);
        $user->save();

        $user->syncRoles($data['roles'] ?? []);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    private function normalizePhone(?string $phone): string|false|null
    {
        if ($phone === null || trim($phone) === '') {
            return null;
        }

        $detected = PhoneNumber::normalizeAndDetect($phone);

        return $detected['e164'] ?? false;
    }

    public function destroy(User $user)
    {
        if ($user->email === 'admin@admin.com') {
            return back()->with('error', 'The default admin user cannot be deleted.');
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }
}
