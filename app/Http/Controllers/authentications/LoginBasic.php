<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginBasic extends Controller
{
  // Menampilkan halaman login
  public function index()
  {
    return view('content.authentications.auth-login-basic');
  }

  // Proses login
  public function authenticate(Request $request)
  {
    $credentials = $request->validate([
      'email-username' => ['required', 'string'],
      'password' => ['required', 'string'],
    ]);

    // Cek login berdasarkan email
    $login_type = filter_var($credentials['email-username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

    if (Auth::attempt([
      $login_type => $credentials['email-username'],
      'password' => $credentials['password']
    ], $request->has('remember'))) {
      $request->session()->regenerate();
      return redirect()->intended('/dashboard');
    }

    return back()->withErrors([
      'email-username' => 'Login gagal. Email atau password salah.',
    ])->onlyInput('email-username');
  }

  // Proses logout
  public function logout(Request $request)
  {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
  }
}
