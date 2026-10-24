<?php

namespace App\Security;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class SecurityPolicy
{
    private ?Request $request = null;

    private string $nonce = '';

    public function nonce(): string
    {
        if ($this->request !== request()) {
            $this->request = request();
            $this->nonce = base64_encode(random_bytes(24));
        }

        return $this->nonce;
    }

    public function correlationId(Request $request): string
    {
        if (! $request->attributes->has('correlation_id')) {
            $request->attributes->set('correlation_id', (string) Str::uuid());
        }

        return $request->attributes->get('correlation_id');
    }

    public function apply(Request $request, Response $response): Response
    {
        $id = $this->correlationId($request);
        if ($response->getStatusCode() >= 400) {
            $message = match ($response->getStatusCode()) {
                401 => 'Please sign in again.', 403 => 'You do not have permission for this action.',
                404 => 'The requested page or record is unavailable.', 419 => 'Your session expired. Please refresh and try again.',
                422 => 'Please check the submitted information.', 429 => 'Too many attempts. Please try again later.',
                503 => 'The service is temporarily unavailable. Please try again later.',
                default => 'Unable to complete this request. Please try again.',
            };
            $existing = $response instanceof JsonResponse ? $response->getData(true) : [];
            if ($request->expectsJson() || $response instanceof JsonResponse) {
                $body = ['message' => $message, 'correlation_id' => $id];
                if ($response->getStatusCode() === 422 && is_array($existing['errors'] ?? null)) {
                    $body['errors'] = $existing['errors'];
                }
                if ($response instanceof JsonResponse) {
                    $response->setData($body);
                } else {
                    $response = response()->json($body, $response->getStatusCode(), $response->headers->all());
                }
            } else {
                $response->setContent(view('errors.request', ['message' => $message, 'correlationId' => $id])->render());
            }
        } elseif ($response instanceof JsonResponse && is_array($data = $response->getData(true)) && isset($data['error'])) {
            $data['error'] = 'Unable to load entries. Please try again. Reference: '.$id;
            $data['correlation_id'] = $id;
            $response->setData($data);
        }
        if (($origin = $request->headers->get('Origin')) && ! in_array($origin, config('cors.allowed_origins', []), true)) {
            foreach (['Access-Control-Allow-Origin', 'Access-Control-Allow-Credentials', 'Access-Control-Allow-Headers', 'Access-Control-Allow-Methods', 'Access-Control-Expose-Headers'] as $header) {
                $response->headers->remove($header);
            }
        }
        $nonce = $this->nonce();
        $response->headers->set('X-Correlation-ID', $id);
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000');
        $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self' 'nonce-{$nonce}'; script-src-attr 'none'; style-src 'self' 'unsafe-inline'; img-src 'self' data: blob:; font-src 'self' data:; connect-src 'self'; object-src 'none'; base-uri 'self'; form-action 'self'; frame-ancestors 'none'");
        $response->headers->set('Referrer-Policy', 'no-referrer');
        $response->headers->remove('X-Powered-By');
        if ($response->getStatusCode() >= 400) {
            $response->headers->set('Cache-Control', 'private, no-store');
        }

        return $response;
    }
}
