@if ( $devices['count'] == 0 )
    <p class="text-center">No Devices</p>
@else
    <div class="row">
        @foreach ($devices['data'] as $device)
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3">
                                <i class="{{ $device_types[$device['device_type']]['icon'] }}" style="font-size: 2rem;"></i>
                            </div>
                            <div class="col-lg-9">
                                <div style="text-align: right;">
                                    <h4>{{ $device['device_nickname'] }}</h4>
                                    <p><i class="fas fa-map-marker-alt"></i> {{ $device['location_name'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Humidity
                                    <span class="badge bg-primary rounded-pill">{!! deviceValues($device['humidity'], 'humidity') !!}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Temperature
                                    <span class="badge bg-success rounded-pill">{!! deviceValues($device['temp'], 'temp') !!}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Wind
                                    <span class="badge bg-danger rounded-pill">{!! deviceValues($device['wind'], 'wind') !!}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Precipitation
                                    <span class="badge bg-success rounded-pill">{!! deviceValues($device['precipitation'], 'precipitation') !!}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Atmosphere Pressure
                                    <span class="badge bg-warning rounded-pill">NA</span>
                                </li>
                            </ul>
                            <p class="text-center mt-2 mb-0">
                                <code>UID {{ $device['device_uid'] }}</code>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif