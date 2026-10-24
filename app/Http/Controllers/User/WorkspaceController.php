<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectBoq;
use App\Models\DailyProgress;
use App\Services\ProjectContext;
use App\Services\WorkflowSchema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Helper;

class WorkspaceController extends Controller
{
    public function select(Request $request)
    {
        abort_unless(Helper::userHasPermission('user.projects'), 403);
        $data = $request->validate(['project_id' => 'required|integer']);
        $project = Project::withoutGlobalScope('workspace_project')->where('project_trash', 1)->findOrFail($data['project_id']);
        $request->session()->put('workspace_project_id', $project->project_id);
        return redirect()->route('user.project_workspace');
    }

    public function leave(Request $request)
    {
        $request->session()->forget('workspace_project_id');
        return redirect()->route('user.projects');
    }

    public function show(ProjectContext $context, WorkflowSchema $schema)
    {
        abort_unless(Helper::userHasPermission('user.view_project'), 403);
        $project = $context->project;
        $boqs = ProjectBoq::where('pboq_project', $project->project_id)->get();
        $reported = DailyProgress::where('dprogress_project', $project->project_id)->where('dprogress_trash', 1)
            ->selectRaw('dprogress_boq, SUM(dprogress_qty) as quantity')->groupBy('dprogress_boq')->pluck('quantity', 'dprogress_boq');
        $approved = $schema->ready() ? DB::table('progress_reviews as r')->join('daily_progress as p', 'p.dprogress_id', '=', 'r.progress_id')
            ->where('p.dprogress_project', $project->project_id)->where('p.dprogress_trash', 1)->where('r.state', 'approved')
            ->selectRaw('p.dprogress_boq, SUM(r.approved_qty) as quantity')->groupBy('p.dprogress_boq')->pluck('quantity', 'dprogress_boq') : collect();
        return view('user.workspace.project', compact('project', 'boqs', 'reported', 'approved') + ['set' => 'project_workspace']);
    }
}
