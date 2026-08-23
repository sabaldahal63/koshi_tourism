<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    // Default fallback admin credentials
    private $defaultEmail = 'admin@koshi.gov.np';
    private $defaultPassword = 'admin123';

    public function showLogin()
    {
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $email    = trim($request->email);
        $password = $request->password;

        // 1. Check default credentials
        if ($email === $this->defaultEmail && $password === $this->defaultPassword) {
            session([
                'admin_logged_in' => true,
                'admin_user'      => [
                    'name'  => 'Admin Manager',
                    'email' => $this->defaultEmail,
                    'role'  => 'Super Administrator'
                ]
            ]);
            return redirect()->route('admin.dashboard')->with('success', 'Welcome back, Administrator!');
        }

        // 2. Check database users if configured
        try {
            $user = User::where('email', $email)->first();
            if ($user && Hash::check($password, $user->password)) {
                session([
                    'admin_logged_in' => true,
                    'admin_user'      => [
                        'name'  => $user->name,
                        'email' => $user->email,
                        'role'  => 'Administrator'
                    ]
                ]);
                return redirect()->route('admin.dashboard')->with('success', "Welcome back, {$user->name}!");
            }
        } catch (\Exception $e) {
            // database table check fallback
        }

        return back()->withInput($request->only('email'))->withErrors([
            'error' => 'Invalid email or password. Please check your credentials.'
        ]);
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['admin_logged_in', 'admin_user']);
        $request->session()->regenerate();
        return redirect()->route('admin.login')->with('info', 'You have been logged out successfully.');
    }
}
