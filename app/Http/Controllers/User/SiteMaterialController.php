<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Project;
use App\Services\ProjectContext;
use App\Services\SiteInventory;
use App\Services\SiteReportService;
use App\Services\WorkflowAccess;
use App\Services\WorkflowSchema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Helper;

class SiteMaterialController extends Controller
{
    public function index(ProjectContext $context, WorkflowSchema $schema, SiteReportService $reports)
    {
        abort_unless(Helper::userHasPermission('user.assign_materials'), 403);
        if (!$schema->ready()) { return response()->view('user.workspace.setup', ['set' => 'site_materials'], 503); }
        $rows = $reports->materials([$context->id()], now()->toDateString(), now()->toDateString());
        $usage = DB::table('site_material_usage as u')->leftJoin('materials as m', 'm.material_id', '=', 'u.material_id')
            ->leftJoin('units as n', 'n.unit_id', '=', 'u.unit_id')->where('u.project_id', $context->id())
            ->select('u.*', 'm.material_name', 'n.unit_name')->orderByDesc('u.used_on')->orderByDesc('u.id')->paginate(25);
        return view('user.material.site', compact('rows', 'usage') + [
            'materials' => Material::with('unit')->where('material_status', 1)->orderBy('material_name')->get(), 'set' => 'site_materials']);
    }

    public function store(Request $request, ProjectContext $context, WorkflowSchema $schema, SiteInventory $inventory)
    {
        abort_unless(WorkflowAccess::can('user.site_material_usage'), 403);
        abort_unless($schema->ready(), 503);
        $data = $request->validate(['material_id' => 'required|integer|exists:materials,material_id',
            'used_on' => 'required|date_format:Y-m-d|before_or_equal:today',
            'quantity' => 'required|numeric|min:0.0001|max:999999999999|decimal:0,4',
            'unit_rate' => 'nullable|numeric|min:0|max:999999999999|decimal:0,4', 'notes' => 'nullable|string|max:2000',
            'submission_key' => 'required|uuid']);
        if (isset($data['unit_rate']) && (float) $data['quantity'] * (float) $data['unit_rate'] > 999999999999.99) {
            throw ValidationException::withMessages(['unit_rate' => 'The calculated usage cost exceeds the supported amount.']);
        }
        DB::transaction(function () use ($data, $context, $inventory, $request) {
            Project::whereKey($context->id())->lockForUpdate()->firstOrFail();
            $existing = DB::table('site_material_usage')->where('submission_key', $data['submission_key'])->first();
            if ($existing) {
                abort_unless((int) $existing->project_id === $context->id()
                    && (int) $existing->created_by === (int) $request->user()->user_id
                    && (int) $existing->material_id === (int) $data['material_id']
                    && $existing->used_on === $data['used_on']
                    && round((float) $existing->quantity, 4) === round((float) $data['quantity'], 4)
                    && (($existing->unit_rate === null && ($data['unit_rate'] ?? null) === null)
                        || ($existing->unit_rate !== null && ($data['unit_rate'] ?? null) !== null
                            && round((float) $existing->unit_rate, 4) === round((float) $data['unit_rate'], 4)))
                    && ($existing->notes ?? '') === ($data['notes'] ?? ''), 409,
                    'This submission was already recorded with different details. Reload before recording more usage.');
                return;
            }
            $material = Material::findOrFail($data['material_id']);
            $available = $inventory->minimumAvailable($context->id(), $material->material_id, $material->material_unit, $data['used_on']);
            if ((float) $data['quantity'] > $available + 0.00001) {
                throw ValidationException::withMessages(['quantity' => 'This usage would exceed available site stock on this date or a later recorded movement.']);
            }
            DB::table('site_material_usage')->insert($data + ['project_id' => $context->id(),
                'unit_id' => $material->material_unit, 'created_by' => $request->user()->user_id, 'created_at' => now()]);
        });
        return redirect()->route('user.site_materials')->with('success', 'Material usage recorded and site stock updated.');
    }
}
