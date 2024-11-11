<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // ログインフォームのビューを返す
    }

    public function login(Request $request)
    {
        // バリデーション
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 認証
        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->intended('dashboard'); // 認証成功後のリダイレクト
        }

        return back()->withErrors([
            'email' => '認証に失敗しました。',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login'); // ログアウト後のリダイレクト
    }
} 