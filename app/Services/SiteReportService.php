<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class SiteReportService
{
    public function materials(array $projectIds, string $start, string $end, ?int $materialId = null): array
    {
        $rows = [];
        $sources = [
            ['assign_materials', 'amaterial_project', 'amaterial_material', 'amaterial_unit', 'amaterial_date', 'amaterial_qty', 'received', 'amaterial_status'],
            ['return_materials', 'rmaterial_project', 'rmaterial_material', 'rmaterial_unit', 'rmaterial_date', 'rmaterial_qty', 'returned', 'rmaterial_status'],
            ['site_material_usage', 'project_id', 'material_id', 'unit_id', 'used_on', 'quantity', 'used', null],
        ];
        foreach ($sources as [$table, $project, $material, $unit, $date, $qty, $kind, $status]) {
            $query = DB::table($table)->whereIn($project, $projectIds)->where($date, '<=', $end);
            if ($materialId) { $query->where($material, $materialId); }
            if ($status) { $query->where($status, 1); }
            foreach ($query->get() as $entry) {
                $key = $entry->$project.':'.$entry->$material.':'.$entry->$unit;
                $rows[$key] ??= ['project_id' => $entry->$project, 'material_id' => $entry->$material,
                    'unit_id' => $entry->$unit, 'opening' => 0.0, 'received' => 0.0,
                    'returned' => 0.0, 'used' => 0.0, 'closing' => 0.0, 'cost_cents' => 0,
                    'unpriced' => false];
                $amount = (float) $entry->$qty;
                if ($entry->$date < $start) {
                    $rows[$key]['opening'] += $kind === 'received' ? $amount : -$amount;
                } else {
                    $rows[$key][$kind] += $amount;
                    if ($kind === 'used') {
                        if ($entry->unit_rate === null) {
                            $rows[$key]['unpriced'] = true;
                        } else {
                            $rows[$key]['cost_cents'] += (int) round($amount * (float) $entry->unit_rate * 100);
                        }
                    }
                }
            }
        }
        $projects = DB::table('projects')->pluck('project_name', 'project_id');
        $materials = DB::table('materials')->pluck('material_name', 'material_id');
        $units = DB::table('units')->pluck('unit_name', 'unit_id');
        foreach ($rows as &$row) {
            $row['project'] = $projects[$row['project_id']] ?? 'Archived project';
            $row['material'] = $materials[$row['material_id']] ?? 'Archived material';
            $row['unit'] = $units[$row['unit_id']] ?? 'Unknown unit';
            $row['opening'] = round($row['opening'], 4);
            $row['closing'] = round($row['opening'] + $row['received'] - $row['returned'] - $row['used'], 4);
            $row['cost'] = $row['unpriced'] ? null : $row['cost_cents'] / 100;
        }
        unset($row);
        usort($rows, fn ($a, $b) => [$a['project'], $a['material'], $a['unit']] <=> [$b['project'], $b['material'], $b['unit']]);

        return array_values($rows);
    }

    public function labour(array $projectIds, string $start, string $end, ?int $categoryId = null, ?int $subcontractorId = null): array
    {
        $rows = [];
        $query = DB::table('labour_workers as w')
            ->join('labour_trackers as t', 't.ltracker_id', '=', 'w.lworker_tracker')
            ->whereIn('t.ltracker_project', $projectIds)->where('t.ltracker_trash', 1)
            ->where('w.lworker_status', 1)->where('t.ltracker_date', '<=', $end);
        if ($categoryId) { $query->where('w.lworker_category', $categoryId); }
        if ($subcontractorId) { $query->where('t.ltracker_subcontractor', $subcontractorId); }
        foreach ($query->get() as $entry) {
            $key = $entry->ltracker_project.':'.$entry->ltracker_subcontractor.':'.$entry->lworker_category;
            $rows[$key] ??= $this->labourRow($entry->ltracker_project, $entry->ltracker_subcontractor, $entry->lworker_category);
            $totalCents = (int) round((float) $entry->lworker_total * 100);
            $people = (int) $entry->lworker_male + (int) $entry->lworker_female;
            $overtimeCents = (int) round($people * (float) $entry->lworker_overtime_hours * round((float) $entry->lworker_overtime_rate * 100));
            if ($entry->ltracker_date < $start) {
                $rows[$key]['opening_cents'] += $totalCents;
            } else {
                $rows[$key]['attendance'] += $people;
                $rows[$key]['base_cents'] += $totalCents - $overtimeCents;
                $rows[$key]['overtime_cents'] += $overtimeCents;
            }
        }
        $payments = DB::table('labour_payments')->whereIn('project_id', $projectIds)->where('paid_on', '<=', $end);
        if ($categoryId) { $payments->where('category_id', $categoryId); }
        if ($subcontractorId) { $payments->where('subcontractor_id', $subcontractorId); }
        foreach ($payments->get() as $payment) {
            $key = $payment->project_id.':'.$payment->subcontractor_id.':'.$payment->category_id;
            $rows[$key] ??= $this->labourRow($payment->project_id, $payment->subcontractor_id, $payment->category_id);
            $cents = (int) round((float) $payment->amount * 100);
            if ($payment->paid_on < $start) {
                $rows[$key]['opening_cents'] -= $cents;
            } else {
                $rows[$key][$payment->type === 'advance' ? 'advance_cents' : 'paid_cents'] += $cents;
            }
        }
        $projects = DB::table('projects')->pluck('project_name', 'project_id');
        $categories = DB::table('category')->pluck('category_name', 'category_id');
        $subcontractors = DB::table('subcontractors')->pluck('subcontractor_name', 'subcontractor_id');
        foreach ($rows as &$row) {
            $row['project'] = $projects[$row['project_id']] ?? 'Archived project';
            $row['category'] = $categories[$row['category_id']] ?? 'Archived category';
            $row['subcontractor'] = $subcontractors[$row['subcontractor_id']] ?? 'Archived subcontractor';
            $row['balance_cents'] = $row['opening_cents'] + $row['base_cents'] + $row['overtime_cents'] - $row['paid_cents'] - $row['advance_cents'];
            foreach (['opening', 'base', 'overtime', 'paid', 'advance', 'balance'] as $field) {
                $row[$field] = $row[$field.'_cents'] / 100;
            }
        }
        unset($row);
        usort($rows, fn ($a, $b) => [$a['project'], $a['subcontractor'], $a['category']] <=> [$b['project'], $b['subcontractor'], $b['category']]);

        return array_values($rows);
    }

    private function labourRow($project, $subcontractor, $category): array
    {
        return ['project_id' => $project, 'subcontractor_id' => $subcontractor, 'category_id' => $category,
            'attendance' => 0, 'opening_cents' => 0, 'base_cents' => 0, 'overtime_cents' => 0,
            'paid_cents' => 0, 'advance_cents' => 0];
    }
}
