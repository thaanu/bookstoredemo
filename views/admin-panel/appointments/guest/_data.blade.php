{{-- 
@php
    echo '<pre>'; print_r($appointments); echo '</pre>';
@endphp --}}

<div class="card"> 
    <div class="card-body">
        
        <h4 class="m-t-0 header-title"><b>Appointments</b></h4>

        @if ( $appointments['count'] == 0 )
            <center>No Appointment Found</center>
        @else
            <table class="table table-sm">
                <thead>

                    <tr>
                        <th>PRN</th>
                        <th>NID</th>
                        <th>Appointment Date/Time</th>
                        <th>Doctor Name</th>
                        <th>Doctor MCR</th>
                        <th>Status</th>
                        <th>Deposit Amount</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($appointments['data'] as $appointment)
                    <tr>
                        <td>{{ $appointment['prn'] }} {{ $appointment['appt_id'] }}</td>
                        <td>{{ $appointment['nid'] }}</td>
                        <td>{{ date(_env('DT_FORMAT'), strtotime($appointment['appt_dt'])) }}</td>
                        <td>{{ $appointment['doctor_name'] }}</td>
                        <td>{{ $appointment['doctor_mcr'] }}</td>
                        <td>{{ $appointment['appt_status'] }}</td>
                        <td>{{ ( $appointment['appt_deposit_col']  ? "MVR " . $appointment['appt_deposit_amount']  : 'Not Collected' )}}</td>
                        <td>
                            
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"> Actions <i class="mdi mdi-chevron-down"></i> </button>
                                <div class="dropdown-menu">
                                    @if ( empty($appointment['parent_appt_id']) )
                                        <a class="dropdown-item change-appt-btn" data-appt-id="{{ $appointment['appt_id'] }}" href="javascript:void(0)">Change Date</a>

                                        @if ( $appointment['appt_deposit_col'] == false )
                                            <a class="dropdown-item collect-deposit-btn" data-appt-id="{{ $appointment['appt_id'] }}" href="javascript:void(0)">Collect Deposit</a>
                                        @endif
                                    @endif
                                    @if ( $appointment['appt_status'] != 'CANCELLED' )
                                        @if ( empty($appointment['parent_appt_id']) )
                                            <a class="dropdown-item cancel-appt-btn" data-appt-id="{{ $appointment['appt_id'] }}" href="javascript:void(0)">Cancel Appointment</a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>

            <div>
                {{ generateBSPagination( cpanelPermalink('appointments/ajax/get-appointments'), $appointments['page'], $appointments['total_pages'], 'sw-appt-pg' ) }}
            </div>

        @endif

    </div>
</div>





