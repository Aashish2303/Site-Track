<?php

namespace App\Http\Middleware;

use App\Security\SecurityPolicy;
use Closure;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Request;

class SecureResponses
{
    public function handle(Request $request, Closure $next)
    {
        $policy = app(SecurityPolicy::class);
        $policy->correlationId($request);
        if ($request->is('_ignition', '_ignition/*', '_debugbar', '_debugbar/*', 'debug', 'debug/*', 'test', 'test/*', 'admin-backdoor', 'seed-data', 'phpinfo')) {
            return $policy->apply($request, response('', 404));
        }
        try {
            $response = $next($request);
        } catch (\Throwable $e) {
            $handler = app(ExceptionHandler::class);
            $handler->report($e);
            $response = $handler->render($request, $e);
        }

        return $policy->apply($request, $response);
    }
}
