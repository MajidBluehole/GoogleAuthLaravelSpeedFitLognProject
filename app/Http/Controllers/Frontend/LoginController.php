<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function index()
    {
        return view('frontend.pages-sign-in');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
        public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();

            if ($user->hasRole('admin')) {
                return redirect()->intended('/admin');
            } else if ($user->hasRole('customer')) {
                return redirect()->intended('/');
            }

            return redirect()->intended('/login');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login');
        }

        $user = $this->findOrCreateUser($googleUser);

        if (!$user->hasAnyRole(['admin', 'customer'])) {
            $user->assignRole('customer'); // Default role
        }

        Auth::login($user, true);

        if ($user->hasRole('admin')) {
            return redirect()->intended('/admin');
        } else if ($user->hasRole('customer')) {
            return redirect()->intended('/');
        }

        return redirect()->intended('/login');
    }

    public function findOrCreateUser($googleUser)
    {
        $authUser = User::where('google_id', $googleUser->id)->first();
        if ($authUser) {
            return $authUser;
        }

        return User::create([
            'name' => $googleUser->name,
            'email' => $googleUser->email,
            'google_id' => $googleUser->id,
            'avatar' => $googleUser->avatar,
            'password' => encrypt('123456dummy') // This should be handled differently in production
        ]);
    }
}
