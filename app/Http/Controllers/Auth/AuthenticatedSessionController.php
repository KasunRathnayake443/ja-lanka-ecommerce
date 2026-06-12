<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();
    
        $request->session()->regenerate();
    
        // Check if mobile and redirect accordingly
        if (isMobile()) {
            return redirect()->intended(route('mobile.account.dashboard'));
        }
    
        return redirect()->intended(route('account.dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
{
    Auth::guard('web')->logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    // Check if mobile and redirect accordingly
    if (isMobile()) {
        return redirect('/mobile');
    }

    return redirect('/');
}
}
