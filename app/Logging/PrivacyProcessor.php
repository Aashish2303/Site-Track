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

        return $record->with(
            message: in_array($record->message, $events, true) ? $record->message : '[REDACTED]',
            context: $safe,
            extra: [],
        );
    }
}
