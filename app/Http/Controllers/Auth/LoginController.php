<?php
// app/Http/Controllers/Auth/LoginController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function index()
    {
        return view('login.index');
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
    
        $credentials = $request->only('email', 'password');
        // dd($credentials);
    
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
    
            // Redirect based on role
            if ($user->hasRole('admin')) {
                return redirect()->intended('/admin');
            } else if ($user->hasRole('customer')) {
                return redirect()->intended('/');
            }
    
            return redirect()->intended('/home');
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

        // Assign role to user
        if (!$user->hasAnyRole(['admin', 'customer'])) {
            $user->assignRole('customer'); // Default role
        }

        Auth::login($user, true);

        // Redirect based on role
        if ($user->hasRole('admin')) {
            return redirect()->intended('/admin');
        } else if ($user->hasRole('customer')) {
            return redirect()->intended('/customer');
        }

        return redirect()->intended('/home');
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
            'password' => encrypt('123456dummy')
        ]);
    }
}
