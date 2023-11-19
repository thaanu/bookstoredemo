@extends('admin-panel.layout')
@section('page-title', 'Applications')
@section('page-content')


<div class="card-box">
    <h4 class="m-t-0 header-title"><b>Active Applications</b></h4>
    
    @if ( $applications['count'] == 0 )
        <p class="text-muted font-13">No Applications</p>
    @else
        <p class="text-muted font-13">{{ $applications['count'] }} Applications</p>

        <div class="p-20">
            <div class="table-responsive">
                <table class="table m-0">
                    <thead>
                        <tr>
                            <th width="1%">#</th>
                            <th>Application Name</th>
                            <th>Auth Key</th>
                            <th>Created Date Time</th>
                            <th width="1%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($applications['data'] as $i => $app)
                            <tr>
                                <td>{{ ($i+1) }}</td>
                                <td>{{ $app['app_name'] }}</td>
                                <td><code>{{ $app['app_auth_key'] }}</code></td>
                                <td>{{ hfDateTimeFormat($app['app_auth_dt']) }}</td>
                                <td>
                                    <a href="{{ permalink('applications.revoke.'.$app['app_id']) }}" class="HFActionBtn" data-title="Revoke Application?" data-text="You cannot undo this action." data-type="warning" data-ns="{{ permalink('applications') }}" >Revoke</a>
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