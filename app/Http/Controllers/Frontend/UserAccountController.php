<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserAccountController extends Controller
{
    public function updateProfile(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $request->user()->update([
            'name' => $data['name'],
        ]);

        return redirect()->route('dashboard', ['tab' => 'profile'])
            ->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $request->user()->update([
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('dashboard', ['tab' => 'password'])
            ->with('success', 'Password updated successfully.');
    }

    public function storeAddress(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->addresses()->count() >= 3) {
            return redirect()->route('dashboard', ['tab' => 'addresses'])
                ->with('error', 'You can only save up to 3 delivery addresses.');
        }

        $data = $request->validate([
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string'],
            'label' => ['nullable', 'string', 'max:100'],
        ]);

        $user->addresses()->create($data);

        return redirect()->route('dashboard', ['tab' => 'addresses'])
            ->with('success', 'Delivery address saved successfully.');
    }

    public function updateAddress(Request $request, UserAddress $address): RedirectResponse
    {
        $user = $request->user();
        abort_unless($address->user_id === $user->id, 403);

        $data = $request->validate([
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string'],
            'label' => ['nullable', 'string', 'max:100'],
        ]);

        $address->update($data);

        return redirect()->route('dashboard', ['tab' => 'addresses'])
            ->with('success', 'Delivery address updated successfully.');
    }

    public function destroyAddress(Request $request, UserAddress $address): RedirectResponse
    {
        $user = $request->user();
        abort_unless($address->user_id === $user->id, 403);

        $address->delete();

        return redirect()->route('dashboard', ['tab' => 'addresses'])
            ->with('success', 'Delivery address removed successfully.');
    }
}
