<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class SiteInventory
{
    public function minimumAvailable(int $project, int $material, int $unit, string $from): float
    {
        $received = DB::table('assign_materials')->where('amaterial_project', $project)
            ->where('amaterial_material', $material)->where('amaterial_unit', $unit)->where('amaterial_status', 1)
            ->selectRaw('amaterial_date AS movement_date, CAST(amaterial_qty AS DECIMAL(16,4)) AS delta');
        $returned = DB::table('return_materials')->where('rmaterial_project', $project)
            ->where('rmaterial_material', $material)->where('rmaterial_unit', $unit)->where('rmaterial_status', 1)
            ->selectRaw('rmaterial_date AS movement_date, -CAST(rmaterial_qty AS DECIMAL(16,4)) AS delta');
        $used = DB::table('site_material_usage')->where('project_id', $project)
            ->where('material_id', $material)->where('unit_id', $unit)
            ->selectRaw('used_on AS movement_date, -quantity AS delta');
        $movements = DB::query()->fromSub($received->unionAll($returned)->unionAll($used), 'movements')
            ->selectRaw('movement_date, SUM(delta) AS delta')->groupBy('movement_date')->orderBy('movement_date')->get();
        $balance = 0.0; $minimum = null;
        foreach ($movements as $movement) {
            if ($movement->movement_date >= $from && $minimum === null) { $minimum = $balance; }
            $balance += (float) $movement->delta;
            if ($movement->movement_date >= $from) {
                // Same-day receipts can be consumed on that date; order is tracked at date precision.
                $minimum = $movement->movement_date === $from ? $balance : min($minimum ?? $balance, $balance);
            }
        }
        return round($minimum ?? $balance, 4);
    }
}
