<?php

namespace App\Http\Middleware;

use App\Services\PublicReference;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PrivacyBoundary
{
    public function handle(Request $request, Closure $next)
    {
        $refs = app(PublicReference::class);
        $resource = $refs->controllerResource(class_basename($request->route()->getControllerClass()));
        foreach (['id' => $resource, 'photo' => 'progress_photos'] as $key => $type) {
            $value = $request->route($key);
            if ($type && is_string($value) && str_starts_with($value, 'pr_')) {
                $request->route()->setParameter($key, $refs->decode($value, $type));
            }
        }
        $input = $request->input();
        foreach (Arr::dot($input) as $key => $value) {
            if (is_string($value) && str_starts_with($value, 'pr_') && $type = $refs->field($key)) {
                Arr::set($input, $key, $refs->decode($value, $type));
            }
        }
        $request->merge($input);
        $response = $next($request);
        $oldInput = $request->session()->get('_old_input', []);
        foreach (array_keys($oldInput) as $key) {
            if (preg_match('/password|pwd|secret|token|authorization|cookie|cvv|card_number/i', (string) $key)) {
                unset($oldInput[$key]);
            }
        }
        if ($request->session()->has('_old_input')) {
            $request->session()->put('_old_input', $oldInput);
        }
        if ($response->headers->has('Location')) {
            $response->headers->set('Location', $refs->html($response->headers->get('Location')));
        }
        if ($response instanceof BinaryFileResponse || $response instanceof StreamedResponse) {
            return $response;
        }
        if ($response instanceof JsonResponse) {
            $data = $response->getData(true);
            if (is_array($data) && array_key_exists('data', $data)) {
                $allowed = config('response-fields.'.$request->route()->getName(), []);
                $data['data'] = array_map(fn ($row) => array_intersect_key($row, array_flip($allowed)), $data['data']);
                $data = Arr::only($data, ['data', 'draw', 'recordsTotal', 'recordsFiltered', 'error']);
            } elseif (is_array($data)) {
                $data = Arr::only($data, ['message', 'errors', 'redirect', 'unit_id', 'unit_name', 'available_qty']);
                if (isset($data['unit_id'])) {
                    $data['unit_id'] = $refs->encode('units', $data['unit_id']);
                }
            }
            $transform = function ($value) use (&$transform, $refs) {
                if (is_array($value)) {
                    return array_map($transform, $value);
                }

                return is_string($value) ? $refs->html($value) : $value;
            };
            $response->setData($transform($data));
        } elseif (str_contains($response->headers->get('Content-Type', ''), 'text/html')) {
            $response->setContent($refs->html($response->getContent()));
        }
        $response->headers->set('Cache-Control', 'private, no-store');
        $response->headers->set('Referrer-Policy', 'no-referrer');
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        return $response;
    }
}
