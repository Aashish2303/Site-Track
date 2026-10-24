<?php

namespace App\Security;

class SafeDiagnostics
{
    public static function frames(\Throwable $exception): array
    {
        $frames = [['file' => $exception->getFile(), 'line' => $exception->getLine()]];
        foreach (array_slice($exception->getTrace(), 0, 15) as $frame) {
            $frames[] = $frame;
        }
        $out = [];
        foreach ($frames as $frame) {
            $file = $frame['file'] ?? '';
            $relative = str_starts_with($file, base_path().DIRECTORY_SEPARATOR) ? substr($file, strlen(base_path()) + 1) : '[external]';
            $out[] = ['file' => $relative, 'line' => (int) ($frame['line'] ?? 0),
                'class' => $frame['class'] ?? '', 'function' => $frame['function'] ?? ''];
        }

        return $out;
    }
}
