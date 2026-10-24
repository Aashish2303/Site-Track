<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;

// This marker ensures Laravel runs authentication before password-session verification.
class CheckUser implements \Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(!Auth::check())
        {
            return $request->expectsJson() ? response()->json(['message' => 'Please sign in again.'], 401) : redirect()->route('user.login');
        }

        $role = Role::where('role_id',Auth::user()->user_role)->where('role_status', 1)->first();
        if ((int) Auth::user()->user_status !== 1 || !$role || !$request->session()->has('password_hash_web')) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return $request->expectsJson()
                ? response()->json(['message' => 'Please sign in again.'], 401)
                : redirect()->route('user.login')->with('error', 'Please sign in again.');
        }

        // Existing deployments are upgraded using their existing APP_KEY, with no SQL/key transfer.
        if ((int) Auth::user()->user_role === 1) {
            app(\App\Services\PasswordVault::class)->upgradeLegacyBatch();
        }

        $request->route()->setParameter('role', $role);

        $response = $next($request);
        $response->headers->set('Cache-Control', 'no-store, private');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        return $response;
    }
}
