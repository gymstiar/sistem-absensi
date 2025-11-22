<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Mentor;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);

            switch ($user->role) {
                case 'admin':
                    return redirect('/admin/dashboard');
                case 'mentor':
                    return redirect('/mentor/dashboard');
                case 'mahasiswa':
                    return redirect('/mahasiswa/dashboard');
                default:
                    return redirect('/');
            }
        }

        return back()->with('error', 'Username atau password salah.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}