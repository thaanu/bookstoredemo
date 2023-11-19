@extends('admin-panel.layout')
@section('page-title', 'API Clients')
@section('page-content')

<div class="mb-3">
    <a class="btn btn-primary" href="{{ cpanelPermalink('api-clients/create') }}">New Client</a>
</div>

<div class="card-box">
    <h4 class="m-t-0 header-title"><b>Active Clients</b></h4>
    
    @if ( $applications['count'] == 0 )
        <p class="text-muted font-13">No Clients</p>
    @else
        <p class="text-muted font-13">{{ $applications['count'] }} Clients</p>

        <div class="p-20">
            <div class="table-responsive">
                <table class="table m-0">
                    <thead>
                        <tr>
                            <th width="1%">#</th>
                            <th>Client Name</th>
                            <th>Auth Key</th>
                            <th width="1%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($applications['data'] as $i => $app)
                            <tr>
                                <td>{{ ($i+1) }}</td>
                                <td>{{ $app['client_name'] }}</td>
                                <td><code>{{ $app['client_key'] }}</code></td>
                                <td>
                                    <a href="{{ cpanelPermalink('applications.revoke.'.$app['client_id']) }}" class="HFActionBtn" data-title="Revoke Application?" data-text="You cannot undo this action." data-type="warning" data-ns="{{ cpanelPermalink('api-clients') }}" >Revoke</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
    @endif
        
</div>


@endsection