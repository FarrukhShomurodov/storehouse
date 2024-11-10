<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;

class AuthController
{
    public function showLoginForm(): View
    {
        return view('admin.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('login', 'password'))) {
            
            if(Session::has('confirm-redirect')){
                $url = Session::get('confirm-redirect');
                Session::forget('confirm-redirect');
                return redirect($url);
            }

            return redirect()->route('dashboard');
        }

        return back()->withErrors(['login' => 'Неверные данные для входа в систему']);
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();
        return redirect()->route('login');
    }

}
