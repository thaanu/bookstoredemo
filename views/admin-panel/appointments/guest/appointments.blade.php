@extends('admin-panel.layout')
@section('page-title', 'Search Guest')
@section('page-content')

<div class="row mb-2">
    <div class="col-md-4">
        <form id="search-guest-form" method="post">
            <div class="mb-2">
                <input type="text" autocomplete="off" name="search_query" id="search_query" class="form-control" placeholder="Search by PRN or NID" />
            </div>
            <button type="submit" name="submit" id="submit" class="btn btn-primary">Search</button>
        </form>
    </div>
</div>

<div id="appointments-container"></div>


@include('admin-panel.appointments.parts._offcanvas')

@include('admin-panel.appointments.script')
@include('admin-panel.appointments.guest.script')

@endsection