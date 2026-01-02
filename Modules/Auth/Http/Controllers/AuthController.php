<?php

namespace Modules\Auth\Http\Controllers;

use Modules\Core\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Modules\User\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Modules\Core\Enums\AccountStatusEnum;
use Modules\Core\Enums\PermissionEnum;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth::login');
    }

    /**
     * Login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->status === AccountStatusEnum::INACTIVE) {
                Auth::logout();
                return redirect()->back()->with('error', 'Tài khoản của bạn đã bị khóa');
            }

            return redirect()->intended('/')->with('success', __('auth.login.success'));
        }

        return back()->withErrors([
            'email' => __('auth.login.invalid_credentials'),
        ])->onlyInput('email');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->flush();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', __('auth.logout.success'));
    }

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('auth::forgot-password');
    }

    /**
     * Forgot password - send email
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Create token reset password
        $token = Str::random(60);

        // Save token to database
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        // Create reset password link (valid for 5 minutes)
        $resetUrl = URL::temporarySignedRoute(
            'password.reset.form',
            now()->addMinutes(5),
            ['token' => $token, 'email' => $request->email]
        );

        // Send email
        $user = User::where('email', $request->email)->first();
        Mail::send('auth::emails.reset-password', [
            'user' => $user,
            'resetUrl' => $resetUrl
        ], function ($message) use ($request) {
            $message->to($request->email)
                ->subject(__('auth.email.reset_password.subject', ['app_name' => config('app.name')]));
        });

        return back()->with('success', __('auth.forgot_password.success'));
    }

    /**
     * Show reset password form
     */
    public function showResetPasswordForm(Request $request)
    {
        // Check link is valid
        if (!$request->hasValidSignature()) {
            return redirect('/login')->withErrors(['error' => __('auth.reset_password.expired_link')]);
        }

        return view('auth::reset-password', [
            'token' => $request->token,
            'email' => $request->email
        ]);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        // Check token
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
            return back()->withErrors(['error' => __('auth.reset_password.invalid_token')]);
        }

        // Check token expired (5 minutes)
        if (now()->diffInMinutes($resetRecord->created_at) > 5) {
            return back()->withErrors(['error' => __('auth.reset_password.expired_token')]);
        }

        // Update password
        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Delete token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect('/login')->with('success', __('auth.reset_password.success'));
    }

    /**
     * Dashboard
     */
    public function dashboard()
    {
        can(PermissionEnum::DASHBOARD_VIEW);

        return view('core::dashboard');
    }
}
