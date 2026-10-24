<?php

namespace App\Exceptions;

use App\Security\RuntimeConfigurationException;
use App\Security\SafeDiagnostics;
use App\Security\SecurityPolicy;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Psr\Log\LogLevel;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<Throwable>, LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
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

        return app(SecurityPolicy::class)->apply($request, $response);
    }

    public function report(Throwable $e)
    {
        if ($this->shouldntReport($e)) {
            return;
        }
        // Structural server diagnostics only: no SQL, bindings, arguments, request or personal data.
        logger()->error('app.error', [
            'event' => 'app.error', 'exception_class' => get_class($e),
            'correlation_id' => app(SecurityPolicy::class)->correlationId(request()),
            'diagnostics' => SafeDiagnostics::frames($e),
            'configuration_fields' => $e instanceof RuntimeConfigurationException ? $e->fields : [],
        ]);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
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
