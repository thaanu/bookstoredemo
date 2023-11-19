
<div class="card-box">
    
    <h4 class="m-t-0 header-title"><b>Records</b></h4>

    @if ( $auditLogs['count'] == 0 )
        <p class="text-muted font-13">No audit records found</p>
    @else

        @if ( $auditLogs['total_records'] >= $auditLogs['limit'] )
            <p class="text-muted font-13">Recent {{ $auditLogs['limit'] }} records of {{ $auditLogs['total_records'] }} records</p>
        @else
            <p class="text-muted font-13">Recent {{ $auditLogs['limit'] }} records</p>
        @endif

        <div class="p-20">
            <table class="table m-0">
                <thead>
                    <tr>
                        <th width="1%">#</th>
                        <th>Date/Time</th>
                        <th>Username</th>
                        <th>Event Status</th>
                        <th>Action Level</th>
                        <th>IP Address</th>
                        <th>Module</th>
                        <th>Route</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($auditLogs['data'] as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ hfDateTimeFormat($item['audit_dt']) }}</td>
                            <td>
                                @if ( empty($item['username']) )
                                    <em>Not Available</em>
                                @else
                                    {{ $item['username'] }}
                                @endif
                            </td>
                            <td>
                                @if ( $item['event_status'] == 'Success' )
                                    <span class="text-success">Success</span>
                                @endif
                                @if ( $item['event_status'] == 'Failure' )
                                    <span class="text-danger">Failure</span>
                                @endif
                            </td>
                            <td>{{ $item['action_level'] }}</td>
                            <td>{{ $item['ip_address'] }}</td>
                            <td>{{ $item['sys_module'] }}</td>
                            <td>
                                @php
                                    $route = json_decode($item['route']);
                                @endphp
                                @if ( $route->method == 'POST' )
                                    <span class="label label-purple" style="margin-right: 10px;">{{ $route->method }}</span>
                                @else
                                    <span class="label label-warning" style="margin-right: 10px;">{{ $route->method }}</span> 
                                @endif
                                {{ $route->uri }}
                            </td>
                            <td>
                                {{ $item['action'] }}
                                @if ( ! empty($item['data']) )
                                    <a href="javascript:void()" style="padding-left: 10px;" ><i class="ti-info-alt" data-info="{{ $item['data'] }}"></i></a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
    
</div>