<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class AuthController extends Controller
{
    private $attempts = [];

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cek login
        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect('/home');
        }

        // Handle salah password
        $email = $request->email;
        if (!isset($this->attempts[$email])) {
            $this->attempts[$email] = 0;
        }
        $this->attempts[$email]++;

        if ($this->attempts[$email] >= 3) {
            $this->resetPassword($email);
            return back()->with('error', 'Password telah direset, silakan cek email Anda.');
        }

        return back()->with('error', 'Email atau password Anda salah.');
    }

    private function resetPassword($email)
    {
        $user = User::where('email', $email)->first();

        if ($user) {
            $newPassword = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 6);
            $user->password = Hash::make($newPassword);
            $user->save();

            // Kirim email
            Mail::raw("Password baru Anda: $newPassword", function ($message) use ($email) {
                $message->to($email)
                        ->subject('Reset Password');
            });
        }
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function home()
    {
        return view('home');
    }
    public function logout()
{
    Auth::logout(); // Logout pengguna
    return redirect('/login')->with('success', 'Anda telah logout.'); // Redirect ke login
}

}

