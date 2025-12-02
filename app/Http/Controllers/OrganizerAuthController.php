<?php

namespace App\Http\Controllers;

use App\Models\Organizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OrganizerAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.organizer-login');
    }

    public function showRegister()
    {
        return view('auth.organizer-register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:organizers',
            'password' => 'required|min:6|confirmed'
        ]);

        Organizer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return redirect('/organizer/login')->with('success', 'Pendaftaran berhasil, silakan login.');
    }

    // app/Http/Controllers/OrganizerAuthController.php

public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $organizer = Organizer::where('email', 'LIKE', $request->email)->first(); // Gunakan LIKE untuk case-insensitive

    if ($organizer && Hash::check($request->password, $organizer->password)) {
        session(['organizer' => $organizer]);

    return redirect('/')
       ->with('show_success_popup', true);
    }

    return back()->withErrors(['email' => 'Email atau password salah.']);
}
public function logout()
    {
        session()->forget('organizer');
        // Arahkan ke rute 'home' setelah logout
        return redirect()->route('home');
    }
}
