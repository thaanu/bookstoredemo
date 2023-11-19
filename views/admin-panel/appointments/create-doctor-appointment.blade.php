@extends('admin-panel.layout')
@section('page-title', 'Doctor Appointment')
@section('page-content')


    @if ( $doctors['count'] == 0 )
        <div class="row mb-2">
            <div class="col-md-12">No Doctors Available</div>
        </div>
    @else
        
        <div class="row mb-4">
            <div class="col-md-4">
                <input type="text" placeholder="Search doctor" class="form-control" id="search-control-input" />
            </div>
        </div>

        <div class="row" id="doctor-grid">
            @foreach ($doctors['data'] as $i => $doctor)
                <div class="col-lg-6 col-xl-3" id="cid-{{ $i }}">
                    <div class="card">
                        <img class="card-img-top img-fluid" src="{{ assets('assets/images/small/img-1.jpg') }}" alt="Card image cap">
                        <div class="card-body">
                            <h5 data-target-id="cid-{{ $i }}" class="card-title">{{ $doctor['doctor_name'] }}</h5>
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

<div id="appContainer"></div>

@include('admin-panel.appointments.parts._modal')
@include('admin-panel.appointments.parts._offcanvas')

@include('admin-panel.appointments.script')

@endsection