<?php

namespace App\Services;

class WageCalculator
{
    public static function calculate(int $male, int $female, string $dailyRate, string $overtimeHours, string $overtimeRate): array
    {
        $people = $male + $female;
        $baseCents = $people * (int) round((float) $dailyRate * 100);
        $overtimeCents = (int) round($people * (float) $overtimeHours * round((float) $overtimeRate * 100));

        return ['base' => $baseCents / 100, 'overtime' => $overtimeCents / 100,
            'total' => ($baseCents + $overtimeCents) / 100];
    }
}
