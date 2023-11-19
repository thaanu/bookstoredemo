@extends('admin-panel.layout')
@section('page-title', 'Locations')
@section('page-content')

<div id="pg-content">
    <center>LOADING . . .</center>
</div>

@include('admin-panel.components._large-modal')
@include('admin-panel.components._offcanvas')
@include('admin-panel.locations.script')

@endsection