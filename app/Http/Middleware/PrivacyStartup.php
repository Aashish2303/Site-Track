<?php

namespace App\Http\Middleware;

use App\Services\PrivacyUpgrade;
use Closure;
use Illuminate\Http\Request;

class PrivacyStartup
{
    public function handle(Request $request, Closure $next)
    {
        if (! app()->runningInConsole()) {
            try {
                app(PrivacyUpgrade::class)->run();
            } catch (\Throwable $e) {
                logger()->error('privacy.cleanup_failed', ['event' => 'privacy.cleanup_failed', 'exception_class' => get_class($e)]);

                return response('Privacy maintenance is in progress. Please try again shortly.', 503)->header('Retry-After', '5');
            }
            if (! app(PrivacyUpgrade::class)->complete()) {
                return response('Privacy maintenance is in progress. Please try again shortly.', 503)->header('Retry-After', '5');
            }
        }
        $response = $next($request);
        $response->headers->set('X-SiteTrack-Privacy', 'v3');
        $marker = storage_path('app/private/privacy-v3.complete');
        if (is_file($marker)) {
            $data = json_decode(file_get_contents($marker), true);
            if (is_array($data) && isset($data['run_id'])) {
                $response->headers->set('X-SiteTrack-Privacy-Run', $data['run_id']);
            }
        }

        return $response;
    }
}
