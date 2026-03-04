<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Override to check if user is admin.
     */
    protected function authenticated(Request $request, $user)
    {
        if (!$user->is_admin) {
            $this->guard()->logout();
            $request->session()->invalidate();
            return redirect()->route('login')->withErrors([
                'email' => 'Acesso restrito. Apenas administradores podem fazer login.',
            ]);
        }
    }

    /**
     * Get the failed login response instance.
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors([
                $this->username() => 'E-mail ou senha incorretos.',
            ]);
    }
}
