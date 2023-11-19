@extends('admin-panel.layout')
@section('page-title', 'Groups')
@section('page-content')

<div id="pg-content">
    <center>LOADING . . .</center>
</div>

@include('admin-panel.components._large-modal')
@include('admin-panel.components._offcanvas')
@include('admin-panel.groups.script')

@endsection