<?php

namespace App\Logging;

use Monolog\LogRecord;

/** Only structural diagnostics may leave the logging boundary. Never format arbitrary values. */
class PrivacyProcessor
{
    public function __invoke(LogRecord $record): LogRecord
    {
        $safe = [];
        $events = ['app.error', 'mail.delivery_failed', 'privacy.upgraded', 'privacy.cleanup_failed'];
        if (in_array($record->context['event'] ?? null, $events, true)) {
            $safe['event'] = $record->context['event'];
        }
        $class = $record->context['exception_class'] ?? null;
        if (is_string($class) && class_exists($class) && is_a($class, \Throwable::class, true)) {
            $safe['exception_class'] = $class;
        }
        $correlation = $record->context['correlation_id'] ?? null;
        if (is_string($correlation) && preg_match('/\A[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}\z/i', $correlation)) {
            $safe['correlation_id'] = $correlation;
        }

        if (isset($record->context['diagnostics']) && is_array($record->context['diagnostics'])) {
            $safe['diagnostics'] = [];
            foreach (array_slice($record->context['diagnostics'], 0, 16) as $frame) {
                if (! is_array($frame)) {
                    continue;
                }
                $item = ['line' => max(0, (int) ($frame['line'] ?? 0))];
                $file = $frame['file'] ?? '';
                if (is_string($file) && preg_match('~\A(?:app|routes|config|bootstrap|vendor|resources/views|storage/framework/views)/[A-Za-z0-9_./-]+\z~', $file)) {
                    $item['file'] = $file;
                }
                foreach (['class', 'function'] as $key) {
                    $value = $frame[$key] ?? null;
                    if (is_string($value) && preg_match('/\A[A-Za-z_\\\\][A-Za-z0-9_\\\\]*\z/', $value)) {
                        $item[$key] = $value;
                    }
                }
                $safe['diagnostics'][] = $item;
            }
        }
        if (isset($record->context['configuration_fields']) && is_array($record->context['configuration_fields'])) {
            $safe['configuration_fields'] = array_values(array_filter($record->context['configuration_fields'], fn ($field) => is_string($field) && preg_match('/\A[A-Z0-9_ \/]+\z/', $field)));
        }

        return $record->with(
            message: in_array($record->message, $events, true) ? $record->message : '[REDACTED]',
            context: $safe,
            extra: [],
        );
    }
}
