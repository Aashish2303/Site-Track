@if(($inProjectWorkspace ?? false) && ($workspaceProject ?? null))
<div class="st-project-context">
    <a href="{{ route('user.project_workspace') }}"><i class="ti ti-building" aria-hidden="true"></i><span><small>SELECTED PROJECT</small><strong>{{ $workspaceProject->project_name }}</strong></span></a>
    <form action="{{ route('user.leave_project') }}" method="POST">@csrf<button class="btn btn-sm btn-white" type="submit">Switch project <i class="ti ti-switch-horizontal" aria-hidden="true"></i></button></form>
</div>
@endif
@if($errors->any())
<div class="alert alert-danger mx-3 mt-3" role="alert"><strong>Please check these details:</strong><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif
