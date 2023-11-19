<div class="mt-3">

    @if ( $doctors['count'] == 0 )
        <p class="text-muted font-13">No doctors found</p>
    @else

        <p class="text-muted font-13">{{ $doctors['count'] }} doctors</p>
        <div class="row">
            @foreach ($doctors['data'] as $doctor)
                <div class="col-lg-6 col-xl-3">
                    <div class="card">
                        <img class="card-img-top img-fluid" src="{{ assets('assets/images/small/img-1.jpg') }}" alt="Card image cap">
                        <div class="card-body">
                            <h5 class="card-title">{{ $doctor['doctor_name'] }}</h5>
                            <p class="card-text">{{ $doctor['doctor_speciality'] }} &bull; {{ $doctor['doctor_mcr'] }}</p>
                            <p class="card-text">{{ ( $doctor['doctor_has_session'] ? 'Session Doctor' :  'Appointment Doctor') }}</p>
                            <a href="javascript:void(0);" data-doctor-id="{{ $doctor['doctor_id'] }}" class="btn btn-primary waves-effect waves-light select-doctor-btn ">Select</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
    @endif
</div>