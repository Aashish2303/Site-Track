<?php

namespace App\Http\Controllers\User;

use App\Exports\SiteReportExport;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Material;
use App\Models\Category;
use App\Services\ProjectContext;
use App\Services\SiteReportService;
use App\Services\WorkflowAccess;
use App\Services\WorkflowSchema;
use Carbon\CarbonImmutable;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    public function index(Request $request, SiteReportService $reports, ProjectContext $context, WorkflowSchema $schema)
    {
        abort_unless(WorkflowAccess::can('user.reports'), 403);
        if (!$schema->ready()) { return response()->view('user.workspace.setup', ['set' => 'reports'], 503); }
        $filters = $request->validate([
            'project' => 'nullable|integer|exists:projects,project_id',
            'period' => 'nullable|in:day,week,month,custom', 'date' => 'nullable|date_format:Y-m-d',
            'start' => 'required_if:period,custom|nullable|date_format:Y-m-d',
            'end' => 'required_if:period,custom|nullable|date_format:Y-m-d|after_or_equal:start',
            'category' => 'nullable|in:materials,labour',
            'material' => 'nullable|integer|exists:materials,material_id',
            'trade' => 'nullable|integer|exists:category,category_id', 'format' => 'nullable|in:xlsx,pdf',
        ]);
        $date = CarbonImmutable::parse($filters['date'] ?? now()->toDateString());
        $period = $filters['period'] ?? 'week';
        [$start, $end] = match ($period) {
            'day' => [$date, $date], 'month' => [$date->startOfMonth(), $date->endOfMonth()],
            'custom' => [CarbonImmutable::parse($filters['start']), CarbonImmutable::parse($filters['end'])],
            default => [$date->startOfWeek(), $date->endOfWeek()],
        };
        $projects = Project::where('project_trash', 1)->orderBy('project_name')->get();
        // Company-wide reports start with all projects. Project links carry an explicit filter.
        $projectId = $filters['project'] ?? null;
        if ($projectId) { abort_unless($projects->contains('project_id', $projectId), 404); }
        $ids = $projectId ? [(int) $projectId] : $projects->pluck('project_id')->all();
        $category = $filters['category'] ?? 'materials';
        $rows = $category === 'materials'
            ? $reports->materials($ids, $start->toDateString(), $end->toDateString(), $filters['material'] ?? null)
            : $reports->labour($ids, $start->toDateString(), $end->toDateString(), $filters['trade'] ?? null);
        $data = compact('projects', 'projectId', 'period', 'date', 'start', 'end', 'category', 'rows') + [
            'set' => 'reports', 'projectLabel' => $projectId ? $projects->firstWhere('project_id', $projectId)->project_name : 'All projects',
            'materials' => Material::orderBy('material_name')->get(), 'trades' => Category::orderBy('category_name')->get(),
            'materialId' => $filters['material'] ?? null, 'tradeId' => $filters['trade'] ?? null,
        ];
        if ($request->filled('format')) {
            abort_unless(WorkflowAccess::can('user.reports_export'), 403);
            $filename = 'sitetrack-'.$category.'-'.$start->format('Y-m-d').'-'.$end->format('Y-m-d');
            if ($request->format === 'xlsx') {
                return Excel::download(new SiteReportExport($data), $filename.'.xlsx');
            }
            $options = new Options();
            $options->set('isRemoteEnabled', false);
            $options->set('isPhpEnabled', false);
            $options->set('defaultFont', 'DejaVu Sans');
            $pdf = new Dompdf($options);
            $pdf->setPaper('A4', 'landscape');
            $pdf->loadHtml(view('user.reports.pdf', $data)->render());
            $pdf->render();
            return response($pdf->output(), 200, ['Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$filename.'.pdf"']);
        }
        return view('user.reports.index', $data);
    }
}
