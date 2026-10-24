<?php

namespace App\Http\Middleware;

use App\Models\Project;
use App\Services\ProjectContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class SelectedProject
{
    public function handle(Request $request, Closure $next)
    {
        $context = app(ProjectContext::class);
        $context->project = null;
        $context->scoped = false;
        $context->workspace = false;
        $id = $request->session()->get('workspace_project_id');
        if ($id) {
            $context->project = Project::withoutGlobalScope('workspace_project')
                ->where('project_trash', 1)->find($id);
            if (!$context->project) {
                $request->session()->forget('workspace_project_id');
            }
        }

        $controller = class_basename($request->route()->getControllerClass());
        $context->scoped = in_array($controller, [
            'DailyWorkController', 'DailyProgressController', 'BoqTrackerController',
            'AssignMaterialController', 'ReturnMaterialController', 'LabourTrackerController',
            'IssueController', 'IndentController', 'QmsController',
            'SiteMaterialController', 'ProgressReviewController', 'LabourPaymentController',
        ], true);
        $requiresProject = $context->scoped || in_array($controller, [
            'MaterialController', 'MaterialCategoryController', 'OrderController', 'StockController',
        ], true) || $request->route()->getName() === 'user.project_workspace';

        // Remembering a site must not append its menu to company-wide pages.
        $context->workspace = $context->scoped || $request->route()->getName() === 'user.project_workspace';

        View::share('workspaceProject', $context->project);
        View::share('inProjectWorkspace', $context->workspace);
        if ($requiresProject && !$context->project) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Choose a project before opening site work.',
                    'redirect' => route('user.projects')], 409);
            }
            return redirect()->route('user.projects')->with('error', 'Choose a project to open its site workspace.');
        }

        if ($context->scoped) {
            foreach (['project', 'project_id', 'dwork_project', 'dprogress_project',
                'amaterial_project', 'rmaterial_project', 'ltracker_project', 'issue_project',
                'indent_project', 'pboq_project'] as $field) {
                if ($request->filled($field) && (string) $request->input($field) !== (string) $context->id()) {
                    abort(403, 'Switch projects before accessing another site.');
                }
            }
            $projectFields = [
                'DailyWorkController' => 'dwork_project', 'DailyProgressController' => 'dprogress_project',
                'AssignMaterialController' => 'amaterial_project', 'ReturnMaterialController' => 'rmaterial_project',
                'LabourTrackerController' => 'ltracker_project', 'IssueController' => 'issue_project',
                'IndentController' => 'indent_project',
            ];
            if (isset($projectFields[$controller])) {
                $request->merge([$projectFields[$controller] => $context->id()]);
            }
            $recordModels = [
                'DailyWorkController' => \App\Models\DailyWork::class,
                'DailyProgressController' => \App\Models\DailyProgress::class,
                'LabourTrackerController' => \App\Models\LabourTracker::class,
                'IssueController' => \App\Models\Issue::class,
                'IndentController' => \App\Models\Indent::class,
            ];
            if ($controller === 'IndentController' && $request->route()->getName() === 'user.status_quotation') {
                $recordModels[$controller] = \App\Models\IndentQuotation::class;
            }
            if (isset($recordModels[$controller]) && $request->route('id')) {
                $recordModels[$controller]::findOrFail($request->route('id'));
            }
            $inputRecords = match ($controller) {
                'DailyWorkController' => ['dwork_id' => \App\Models\DailyWork::class],
                'DailyProgressController' => ['dprogress_id' => \App\Models\DailyProgress::class],
                'BoqTrackerController' => ['pboq_id' => \App\Models\ProjectBoq::class],
                'IndentController' => ['item_id' => \App\Models\IndentItem::class],
                'IssueController' => ['issue_id' => \App\Models\Issue::class],
                default => [],
            };
            foreach ($inputRecords as $field => $model) {
                if ($request->filled($field)) {
                    $request->validate([$field => 'integer']);
                    $model::findOrFail($request->input($field));
                }
            }
        }

        return $next($request);
    }
}
