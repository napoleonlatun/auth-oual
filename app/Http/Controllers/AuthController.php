<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;               // <-- Needed to touch the Database
use Illuminate\Support\Facades\Hash; // <-- Needed to encrypt passwords
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 5. Log out
    public function logout(Request $request) {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
    // 3. Show Login Form
    public function login() {
        return view('auth.login');
    }

    // 4. Check Credentials
    public function authenticate(Request $request) {
        // Validate input
        $fields = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Try to log in
        if (Auth::attempt($fields)) {
            $request->session()->regenerate(); // specific security step
            return redirect('/'); // Success! Go home.
        }

        // Failed? Go back with error.
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
    // 1. Show the Form
    public function register()
    {
        return view('auth.register');
    }

    // 2. Process the Form
    public function store(Request $request)
    {
        // A. Validate the data (Check if it's good)
        $fields = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:3|confirmed'
        ]);

        // B. Create the User in the Database
        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']), // Encrypt the password!
        ]);

        // C. Redirect somewhere (we'll just go home for now)
        return redirect('/');
    }
}