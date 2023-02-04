<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function authenticate(Request $request): JsonResponse|RedirectResponse|Redirector
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect('/dashboard');
        }

        return response()->json([
           'message' => 'The provided credentials do not match our records.'
        ], 422);
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();

        return redirect('/login');
    }
}
