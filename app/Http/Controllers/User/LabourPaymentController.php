<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcontractor;
use App\Models\Project;
use App\Services\ProjectContext;
use App\Services\SiteReportService;
use App\Services\WorkflowAccess;
use App\Services\WorkflowSchema;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LabourPaymentController extends Controller
{
    public function index(Request $request, ProjectContext $context, WorkflowSchema $schema, SiteReportService $reports)
    {
        abort_unless(WorkflowAccess::can('user.labour_payments'), 403);
        if (!$schema->ready()) { return response()->view('user.workspace.setup', ['set' => 'labour_payments'], 503); }
        $request->validate(['week' => 'nullable|date_format:Y-m-d', 'trade' => 'nullable|integer|exists:category,category_id',
            'subcontractor' => 'nullable|integer|exists:subcontractors,subcontractor_id']);
        $start = CarbonImmutable::parse($request->input('week', now()->toDateString()))->startOfWeek();
        $end = $start->endOfWeek();
        $rows = $reports->labour([$context->id()], $start->toDateString(), $end->toDateString(),
            $request->filled('trade') ? (int) $request->trade : null,
            $request->filled('subcontractor') ? (int) $request->subcontractor : null);
        $payments = DB::table('labour_payments as p')
            ->leftJoin('subcontractors as s', 's.subcontractor_id', '=', 'p.subcontractor_id')
            ->leftJoin('category as c', 'c.category_id', '=', 'p.category_id')
            ->where('p.project_id', $context->id())->whereBetween('p.paid_on', [$start->toDateString(), $end->toDateString()]);
        if ($request->filled('trade')) { $payments->where('p.category_id', $request->trade); }
        if ($request->filled('subcontractor')) { $payments->where('p.subcontractor_id', $request->subcontractor); }
        return view('user.labour_tracker.payments', compact('rows', 'start', 'end') + [
            'payments' => $payments->select('p.*', 's.subcontractor_name', 'c.category_name')->orderByDesc('p.id')->paginate(30)->withQueryString(),
            'categories' => Category::where('category_status', 1)->orderBy('category_name')->get(),
            'subcontractors' => Subcontractor::where('subcontractor_status', 1)->orderBy('subcontractor_name')->get(),
            'set' => 'labour_payments',
        ]);
    }

    public function store(Request $request, ProjectContext $context, WorkflowSchema $schema)
    {
        abort_unless(WorkflowAccess::can('user.record_labour_payment'), 403);
        abort_unless($schema->ready(), 503);
        $data = $request->validate(['subcontractor_id' => 'required|integer|exists:subcontractors,subcontractor_id',
            'category_id' => 'required|integer|exists:category,category_id',
            'paid_on' => 'required|date_format:Y-m-d|before_or_equal:today', 'type' => 'required|in:payment,advance',
            'amount' => 'required|numeric|min:0.01|max:999999999999.99|decimal:0,2',
            'reference' => 'required|string|max:120', 'notes' => 'nullable|string|max:2000', 'submission_key' => 'required|uuid']);
        DB::transaction(function () use ($data, $context, $request) {
            Project::whereKey($context->id())->lockForUpdate()->firstOrFail();
            $existing = DB::table('labour_payments')->where('submission_key', $data['submission_key'])->first();
            if ($existing) {
                abort_unless((int) $existing->created_by === (int) $request->user()->user_id
                    && (int) $existing->project_id === $context->id()
                    && (int) $existing->subcontractor_id === (int) $data['subcontractor_id']
                    && (int) $existing->category_id === (int) $data['category_id']
                    && $existing->paid_on === $data['paid_on'] && $existing->type === $data['type']
                    && (int) round($existing->amount * 100) === (int) round($data['amount'] * 100)
                    && $existing->reference === $data['reference']
                    && ($existing->notes ?? '') === ($data['notes'] ?? ''), 409,
                    'This submission was already recorded with different details. Reload before recording another payment.');
                return;
            }
            DB::table('labour_payments')->insert($data + ['project_id' => $context->id(),
                'created_by' => $request->user()->user_id, 'created_at' => now()]);
        });
        return redirect()->route('user.labour_payments', ['week' => $data['paid_on']])->with('success', 'Payment recorded once and included in the crew balance.');
    }
}
