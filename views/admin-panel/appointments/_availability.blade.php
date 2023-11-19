{{-- Handle Change Appointment Date --}}
@if ( $change_date == true )

    @if ( $doctor['doctor_has_session'] )
                
        @if ( empty($availability) )
            Sessions not available
        @else
            <p>Select a session</p>
            <div class="d-grid">
                @foreach ($availability as $time)
                    <button type="button" data-parent-appt-id="{{ $parent_appt_id }}" data-time-info="{{ json_encode($time) }}" data-speciality-code="{{ $doctor['doctor_speciality_code'] }}" data-slot-number="" data-start-date="{{ $time['CAL_DATE'] }}" data-start-time="{{ date('H:i', strtotime($time['START_TIME'])) }}" data-action="session" data-doctor-mcr="{{ $doctor['doctor_mcr'] }}" class="btn btn-outline-primary waves-effect waves-light chng-appt-btn d-grid mb-2" >
                        {{ date('h:iA', strtotime($time['START_TIME'])) }} to {{ date('h:iA', strtotime($time['END_TIME'])) }} ({{ $time['MAX_APPT_COUNT'] }})
                    </button>
                @endforeach
            </div>
        @endif

    @else

        @if ( empty($availability) )
            Appointments not available
        @else
            <p>Select an appointment time</p>
            <div class="d-grid">
                @foreach ($availability as $time)
                    <button type="button" data-parent-appt-id="{{ $parent_appt_id }}" data-time-info="{{ json_encode($time) }}" data-speciality-code="{{ $doctor['doctor_speciality_code'] }}" data-slot-number="{{ $time->SlotNumber }}" data-start-time="" data-action="appointment" data-doctor-mcr="{{ $doctor['doctor_mcr'] }}" class="btn btn-outline-primary waves-effect waves-light chng-appt-btn d-grid mb-2" >
                        {{ date('d M Y', strtotime($time->Date)) }} - 
                        {{ date('h:iA', strtotime($time->StartTime)) }} to {{ date('h:iA', strtotime($time->EndTime)) }}
                    </button>
                @endforeach
            </div>
        @endif

    @endif
@endif

{{-- Handle Initial Appointments --}}
@if ( $change_date == false )
    
    @if ( $doctor['doctor_has_session'] )
        
        @if ( empty($availability) )
            Sessions not available
        @else
            <p>Select a session</p>
            <div class="d-grid">
                @foreach ($availability as $time)
                    <button type="button" data-time-info="{{ json_encode($time) }}" data-speciality-code="{{ $doctor['doctor_speciality_code'] }}" data-slot-number="" data-start-date="{{ $time['CAL_DATE'] }}" data-start-time="{{ date('H:i', strtotime($time['START_TIME'])) }}" data-action="session" data-doctor-mcr="{{ $doctor['doctor_mcr'] }}" class="btn btn-outline-primary waves-effect waves-light selection-btn d-grid mb-2" >
                        {{ date('h:iA', strtotime($time['START_TIME'])) }} to {{ date('h:iA', strtotime($time['END_TIME'])) }} ({{ $time['MAX_APPT_COUNT'] }})
                    </button>
                @endforeach
            </div>
        @endif

    @else

        @if ( empty($availability) )
            Appointments not available
        @else
            <p>Select an appointment time</p>
            <div class="d-grid">
                @foreach ($availability as $time)
                    <button type="button" data-time-info="{{ json_encode($time) }}" data-speciality-code="{{ $doctor['doctor_speciality_code'] }}" data-slot-number="{{ $time->SlotNumber }}" data-start-time="" data-action="appointment" data-doctor-mcr="{{ $doctor['doctor_mcr'] }}" class="btn btn-outline-primary waves-effect waves-light selection-btn d-grid mb-2" >
                        {{ date('d M Y', strtotime($time->Date)) }} - 
                        {{ date('h:iA', strtotime($time->StartTime)) }} to {{ date('h:iA', strtotime($time->EndTime)) }}
                    </button>
                @endforeach
            </div>
        @endif
        
    @endif

@endif