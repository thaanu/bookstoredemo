@extends('admin-panel.layout')
@section('page-title', 'Session Appointment')
@section('page-content')

<div class="row mb-2">
    <div class="col-sm-4">
        <label class="form-label">Search doctor by speciality</label> <br>
        <select id="speciality-select">
            @if ( $specialities['count'] == 0 )
                <option value="">No Specialities Available</option>
            @else
                <option value="">Select</option>
                @foreach ($specialities['data'] as $speciality)
                    <option value="{{ $speciality['doctor_speciality_code'] }}">{{ $speciality['doctor_speciality'] }} ({{ $speciality['doctor_speciality_code'] }})</option>
                @endforeach
            @endif
        </select>
    </div>
</div>

<div id="appContainer"></div>

@include('admin-panel.appointments.parts._modal')
@include('admin-panel.appointments.parts._offcanvas')

@include('admin-panel.appointments.script')

@endsection