

<div class="card"> 
    <div class="card-body">
        
        <h4 class="m-t-0 header-title"><b>All Cronjobs</b></h4>

        @if ( $data['count'] == 0 )
            <p class="text-muted font-13">No cronjobs found</p>
        @else

            <p class="text-muted font-13">{{ $data['count'] }} jobs</p>

            <table class="table table-bordered">
                <tr>
                    <th>Date Time</th>
                    <th>Cronjob Type</th>
                    <th>Status</th>
                    <th>Run Date Time</th>
                    <th></th>
                </tr>
                @foreach ($data['data'] as $job)
                <tr>
                    <td>{{ $job['cronjob_dt'] }}</td>
                    <td>{{ $job['cronjob_type'] }}</td>
                    <td>{{ $job['cronjob_status'] }}</td>
                    <td>{{ $job['cronjob_run_dt'] }}</td>
                    <td>
                        {{-- <a href="#" class="btn btn-primary">
                            <i class="fas fa-info"></i>
                        </a> --}}
                    </td>
                </tr>
                @endforeach
            </table>
            
        @endif

        

    </div>
</div>