<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $login = trim($data['login']);
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $identifier = $field === 'phone' ? PhoneNumber::normalizeAndDetect($login) : $login;

        if ($field === 'phone' && $identifier === null) {
            return back()
                ->withErrors(['login' => 'Please enter a valid BD/CN phone number or email.'])
                ->onlyInput('login');
        }

        $credentials = [
            $field => $field === 'phone' ? $identifier['e164'] : $identifier,
            'password' => $data['password'],
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user?->is_admin) {
                Auth::logout();

                return back()->withErrors(['login' => 'This account is not a user account.'])->onlyInput('login');
            }

            return redirect()->route('dashboard')->with('success', 'Welcome back!');
        }

        return back()->withErrors(['login' => 'Invalid email or phone number, or password.'])->onlyInput('login');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Logged out successfully.');
    }
}
