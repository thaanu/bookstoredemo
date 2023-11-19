@extends('admin-panel.layout')
@section('page-title', 'Dashboard')
@section('page-content')
{{-- <div id="pg-content">Loading...</div> --}}
{{-- @include('admin-panel.dashboard.script') --}}

<div class="row">
    @foreach ($apps as $app)
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <h3>{{ $app['name'] }}</h3>
                    <a href="{{ cpanelPermalink($app['url']) }}" class="btn btn-light">View</a>
                </div>
            </div>
        </div>
    @endforeach
</div>


@endsection