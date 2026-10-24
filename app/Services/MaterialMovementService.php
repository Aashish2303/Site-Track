<?php

namespace App\Services;

use App\Models\AssignMaterial;
use App\Models\ReturnMaterial;
use App\Models\Material;
use App\Models\Project;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MaterialMovementService
{
    public function save(Request $request, bool $returning): void
    {
        $prefix = $returning ? 'rmaterial_' : 'amaterial_';
        $project = app(ProjectContext::class)->id();
        $request->validate([$prefix.'date' => 'required|date_format:Y-m-d|before_or_equal:today',
            $prefix.'material' => 'required|integer|exists:materials,material_id',
            $prefix.'qty' => 'required|numeric|min:0.0001|max:999999999999|decimal:0,4']);
        DB::transaction(function () use ($request, $returning, $prefix, $project) {
            Project::whereKey($project)->lockForUpdate()->firstOrFail();
            $material = Material::findOrFail($request->input($prefix.'material'));
            $quantity = (float) $request->input($prefix.'qty');
            $stock = Stock::where('stock_material', $material->material_id)->lockForUpdate()->first();
            if (!$stock) {
                throw ValidationException::withMessages([$prefix.'qty' => 'This material has no central stock record. Receive it into stock first.']);
            }
            if ($returning) {
                $available = app(SiteInventory::class)->minimumAvailable($project, $material->material_id,
                    $material->material_unit, $request->input($prefix.'date'));
                if ($quantity > $available + 0.00001) {
                    throw ValidationException::withMessages([$prefix.'qty' => 'The return exceeds available site stock for this date or a later recorded movement.']);
                }
            } elseif ($quantity > (float) $stock->stock_qty) {
                throw ValidationException::withMessages([$prefix.'qty' => 'The assignment exceeds available central stock.']);
            }
            $data = [$prefix.'date' => $request->input($prefix.'date'), $prefix.'project' => $project,
                $prefix.'category' => $material->material_category, $prefix.'material' => $material->material_id,
                $prefix.'unit' => $material->material_unit, $prefix.'qty' => $quantity,
                $prefix.'added_on' => now(), $prefix.'added_by' => Auth::id(),
                $prefix.'updated_on' => now(), $prefix.'updated_by' => Auth::id(), $prefix.'status' => 1];
            $returning ? ReturnMaterial::create($data) : AssignMaterial::create($data);
            $stock->stock_qty = (float) $stock->stock_qty + ($returning ? $quantity : -$quantity);
            $stock->stock_updated_on = now(); $stock->stock_updated_by = Auth::id(); $stock->save();
        });
    }
}
