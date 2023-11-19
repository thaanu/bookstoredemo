@extends('admin-panel.layout')
@section('page-title', 'Cronjobs')
@section('page-content')

<div id="jobs-container"></div>

@include('admin-panel.cronjobs.script')

@endsection