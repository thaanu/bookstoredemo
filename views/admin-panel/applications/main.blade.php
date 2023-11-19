@extends('admin-panel.layout')
@section('page-title', 'API Clients')
@section('page-content')

    <div class="mb-3">
        <a class="btn btn-primary" href="{{ cpanelPermalink('api-clients/create') }}">New Client</a>
    </div>

    <div class="card-box">
        <h4 class="m-t-0 header-title"><b>Active Clients</b></h4>

        @if ($applications['count'] == 0)
            <p class="text-muted font-13">No Clients</p>
        @else
            <p class="text-muted font-13">{{ $applications['count'] }} Clients</p>

            <div class="row">
                @foreach ($applications['data'] as $i => $app)
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <h3>{{ $app['client_name'] }}</h3>
                                <p><code>{{ $app['client_key'] }}</code></p>
                                <p>
                                    <a href="{{ cpanelPermalink('applications.revoke.' . $app['client_id']) }}" class="HFActionBtn"
                                    data-title="Revoke Application?" data-text="You cannot undo this action." data-type="warning"
                                    data-ns="{{ cpanelPermalink('api-clients') }}">Revoke</a>
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        @endif

    </div>


@endsection
