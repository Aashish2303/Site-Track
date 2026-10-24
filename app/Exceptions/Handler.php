<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
        'user_password',
        'new_password',
        'confirm_password',
        'settings_pwd',
        'token',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function render($request, Throwable $e)
    {
        $response = parent::render($request, $e);
        if ($response->getStatusCode() >= 500) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Unable to complete this request. Please try again.'], $response->getStatusCode())
                : response()->view('errors.500', [], $response->getStatusCode());
        }
        return $response;
    }

    public function report(Throwable $e)
    {
        if ($this->shouldntReport($e)) { return; }
        // No exception object, SQL, bindings, request, headers, user or stack arguments.
        logger()->error('app.error', [
            'event' => 'app.error', 'exception_class' => get_class($e),
            'correlation_id' => (string) \Illuminate\Support\Str::uuid(),
        ]);
    }

    protected function unauthenticated($request, \Illuminate\Auth\AuthenticationException $exception)
    {
        return $request->expectsJson()
            ? response()->json(['message' => 'Please sign in again.'], 401)
            : redirect()->guest(route('user.login'));
    }

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
