<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->name('user.')
                ->group(base_path('routes/user.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        $response = function (Request $request, array $headers) {
            $message = 'Too many attempts. Please try again later.';
            return $request->expectsJson()
                ? response()->json(['message' => $message], 429, $headers)
                : response()->view('errors.429', ['message' => $message], 429, $headers);
        };
        RateLimiter::for('login', function (Request $request) use ($response) {
            $name = $request->input('user_lname', '');
            $account = hash_hmac('sha256', mb_strtolower(is_string($name) ? mb_substr($name, 0, 255) : 'invalid'), config('app.key'));
            return [
                Limit::perMinutes(15, 5)->by('login-pair:'.$account.':'.hash_hmac('sha256', (string) $request->ip(), config('app.key')))->response($response),
                Limit::perMinute(20)->by('login-ip:'.hash_hmac('sha256', (string) $request->ip(), config('app.key')))->response($response),
                Limit::perMinutes(15, 25)->by('login-account:'.$account)->response($response),
            ];
        });
        RateLimiter::for('recovery', function (Request $request) use ($response) {
            $email = $request->input('user_email', '');
            $account = hash_hmac('sha256', mb_strtolower(is_string($email) ? mb_substr($email, 0, 255) : 'invalid'), config('app.key'));
            return [
                Limit::perMinute(5)->by('recovery-ip:'.hash_hmac('sha256', (string) $request->ip(), config('app.key')))->response($response),
                Limit::perHour(5)->by('recovery-account:'.$account)->response($response),
            ];
        });
        RateLimiter::for('password-change', fn (Request $request) => $request->isMethod('post')
            ? Limit::perMinute(5)->by('password-change:'.$request->user()->user_id)->response($response)
            : Limit::none());
        RateLimiter::for('password-check', fn (Request $request) => Limit::perMinute(5)->by('password-check:'.$request->user()->user_id)->response($response));
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: hash_hmac('sha256', (string) $request->ip(), config('app.key')));
        });
    }
}
