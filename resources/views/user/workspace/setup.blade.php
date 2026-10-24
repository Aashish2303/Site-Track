@extends('user.layouts.app')
@section('title', 'Site workflows - '.Request()->settings->settings_title)
@section('contents')
<div class="content"><div class="card"><div class="card-body st-empty">
    <i class="ti ti-database" aria-hidden="true"></i><h4>Site workflows are awaiting activation</h4>
    <p>The administrator needs to complete the one-time database update before approvals, material usage and labour payments can be recorded.</p>
    <a class="btn btn-primary mt-3" href="{{ route('user.projects') }}">Return to projects</a>
</div></div></div>
@endsection
