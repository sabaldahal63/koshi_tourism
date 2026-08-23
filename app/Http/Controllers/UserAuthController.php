<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('user.dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|string|email|max:255|unique:users',
            'phone'                 => 'nullable|string|max:25',
            'password'              => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => strtolower($validated['email']),
            'phone'    => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role'     => 'user',
        ]);

        Auth::login($user);

        // Automatically link any past guest bookings with the same email address
        try {
            Booking::whereNull('user_id')
                   ->where('email', $user->email)
                   ->update(['user_id' => $user->id]);
        } catch (\Exception $e) {}

        return redirect()->route('user.dashboard')->with('success', 'Registration successful! Welcome to Koshi Province Tourism.');
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('user.dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt(['email' => strtolower($credentials['email']), 'password' => $credentials['password']], $remember)) {
            $request->session()->regenerate();

            // Link any past guest bookings with the same email
            try {
                Booking::whereNull('user_id')
                       ->where('email', Auth::user()->email)
                       ->update(['user_id' => Auth::id()]);
            } catch (\Exception $e) {}

            return redirect()->intended(route('user.dashboard'))->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        return back()->withInput($request->only('email', 'remember'))->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('info', 'You have been logged out successfully.');
    }

    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['email' => 'Please sign in to access your traveler profile.']);
        }

        $user = Auth::user();
        
        try {
            $bookings = Booking::where('user_id', $user->id)
                               ->orWhere('email', $user->email)
                               ->orderBy('created_at', 'desc')
                               ->get();
        } catch (\Exception $e) {
            $bookings = collect();
        }

        $totalSpent   = $bookings->where('status', '!=', 'cancelled')->sum('total');
        $hotelStays   = $bookings->where('type', 'hotel')->count();
        $guideHires   = $bookings->where('type', 'guide')->count();
        $activeTrips  = $bookings->where('status', 'confirmed')->count();

        return view('user.dashboard', [
            'user'        => $user,
            'bookings'    => $bookings,
            'totalSpent'  => $totalSpent,
            'hotelStays'  => $hotelStays,
            'guideHires'  => $guideHires,
            'activeTrips' => $activeTrips,
        ]);
    }
}
