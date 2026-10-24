<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\ResetAccountPassword;
use App\Models\User;
use App\Rules\AccountPassword;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->validate(['user_lname' => 'required|string|max:255', 'user_password' => 'required|string|max:1024']);
            $user = User::where('user_lname', $data['user_lname'])->first();
            // Always do a bcrypt check, including unknown accounts, to reduce timing disclosure.
            $hash = $user?->user_password ?? '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi';
            $valid = Hash::check($data['user_password'], $hash);
            if (! $valid || ! $user || (int) $user->user_status !== 1 || ! $user->role || (int) $user->role->role_status !== 1) {
                return redirect()->route('user.login')->with('error', 'Unable to sign in with those details.')->withInput($request->only('user_lname'));
            }
            Auth::login($user);
            $request->session()->regenerate();
            $request->session()->forget('workspace_project_id');
            $request->session()->put('password_hash_web', $user->getAuthPassword());

            return redirect()->intended(route('user.projects'));
        }

        return view('user.login.login', ['set' => 'login']);
    }

    public function forgot_password(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->validate(['user_email' => 'required|string|email|max:255']);
            try {
                Password::sendResetLink(['user_email' => $data['user_email'], 'user_status' => 1], function (User $user, string $token) {
                    \Helper::mailConfig();
                    $path = route('user.reset_password', [], false).'#token='.$token;
                    Mail::to($user->user_email)->send(new ResetAccountPassword(rtrim(config('app.url'), '/').$path));
                });
            } catch (\Throwable $e) {
                // Do not log reset tokens, SMTP credentials or account identifiers.
                logger()->warning('mail.delivery_failed', ['event' => 'mail.delivery_failed']);
            }

            return redirect()->route('user.login')->with('success', 'If the account is eligible, a password reset link will be sent.');
        }

        return view('user.login.forgot_password');
    }

    public function reset_password(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'user_email' => 'required|string|email|max:255', 'token' => 'required|string|size:64',
                'password' => ['required', 'string', 'confirmed', new AccountPassword],
                'password_confirmation' => 'required|string',
            ]);
            $status = Password::reset([
                'user_email' => $data['user_email'], 'user_status' => 1, 'token' => $data['token'],
                'password' => $data['password'], 'password_confirmation' => $data['password_confirmation'],
            ], function (User $user, string $password) {
                $user->user_password = Hash::make($password);
                $user->user_vpassword = '';
                $user->user_updated_on = now();
                $user->save();
                event(new PasswordReset($user));
            });
            if ($status === Password::PASSWORD_RESET) {
                return redirect()->route('user.login')->with('success', 'Password updated. Please sign in.');
            }

            return back()->with('error', 'Unable to reset the password. Request a new link.');
        }

        return response()->view('user.login.reset_password', ['token' => '', 'email' => ''])
            ->header('Cache-Control', 'no-store, private')->header('Referrer-Policy', 'no-referrer');
    }
}
