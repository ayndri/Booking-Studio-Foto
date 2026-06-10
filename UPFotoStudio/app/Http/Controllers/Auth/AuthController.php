<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login gabungan untuk admin/owner.
     */
    public function showLoginForm(): View
    {
        return view('auth.login', [
            'title' => 'Login Dashboard',
            'subtitle' => 'Masuk dengan akun Admin atau Owner',
            'submitRoute' => route('login.attempt'),
        ]);
    }

    /**
     * Proses login gabungan berdasarkan role akun.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        if (Auth::guard('admin')->attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'role' => User::ROLE_ADMIN,
        ])) {
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        if (Auth::guard('owner')->attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'role' => User::ROLE_OWNER,
        ])) {
            $request->session()->regenerate();

            return redirect()->route('owner.dashboard');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Email atau password tidak valid.']);
    }

    /**
     * Logout khusus session admin.
     */
    public function logoutAdmin(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();
        $this->cleanupSessionAfterScopedLogout($request);

        return redirect()->route('login');
    }

    /**
     * Logout khusus session owner.
     */
    public function logoutOwner(Request $request): RedirectResponse
    {
        Auth::guard('owner')->logout();
        $this->cleanupSessionAfterScopedLogout($request);

        return redirect()->route('login');
    }

    private function cleanupSessionAfterScopedLogout(Request $request): void
    {
        if (!Auth::guard('admin')->check() && !Auth::guard('owner')->check()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }
    }
}
