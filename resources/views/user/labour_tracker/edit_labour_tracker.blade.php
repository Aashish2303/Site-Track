@extends('user.layouts.app')
@section('title', 'Correct labour attendance - '.Request()->settings->settings_title)
@section('contents')
@include('user.labour_tracker.attendance-form')
@endsection
