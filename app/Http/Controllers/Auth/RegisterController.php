<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone_country' => ['required', Rule::in(PhoneNumber::supportedCountries())],
            'phone' => ['required', 'string', 'max:25'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $normalizedPhone = PhoneNumber::normalizeForCountry($data['phone_country'], $data['phone']);

        if ($normalizedPhone === null) {
            return back()
                ->withErrors(['phone' => 'Invalid phone number for selected country. BD: 01XXXXXXXXX, CN: 1XXXXXXXXXX'])
                ->withInput();
        }

        if (User::where('phone', $normalizedPhone)->exists()) {
            return back()->withErrors(['phone' => 'The phone number has already been taken.'])->withInput();
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_country' => $data['phone_country'],
            'phone' => $normalizedPhone,
            'password' => $data['password'],
            'is_admin' => false,
        ]);

        $role = Role::firstOrCreate(['name' => 'User']);
        $user->assignRole($role);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Registration completed successfully.');
    }
}
