@if ( $devices['count'] == 0 )
    <p class="text-center">You do not have any devices</p>
    <p class="text-center">
        <button type="button" id="add-device-btn" class="btn btn-primary" >Add Device</button>
    </p>
@else
    
    <div class="py-2 mb-2">
        <button type="button" id="add-device-btn" class="btn btn-primary" >Add Device</button>
    </div>

    <div class="row">
        @foreach ($devices['data'] as $device)
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <i class="{{ $device_types[$device['device_type']]['icon'] }}" style="font-size: 2rem;"></i>
                        <h4>{{ $device['device_nickname'] }}</h4>
                        <p>UID <code>{{ $device['device_uid'] }}</code></p>
                        <p>
                            <button data-url="{{ cpanelPermalink('devices/ajax/show-device/'.$device['device_id']) }}" class="edit-btn btn btn-sm btn-light" style="margin-right: 10px;">Edit</button> 
                            <button data-url="{{ cpanelPermalink('devices/ajax/show-reading/'.$device['device_id']) }}" class="reading-btn btn btn-sm btn-light" style="margin-right: 10px;">Readings</button> 
                            <input type="checkbox" {{ ( $device['device_status'] == 'on' ? ' checked ' : '' ) }} data-url="{{ cpanelPermalink('devices/ajax/switch-device/'.$device['device_id']) }}" data-size="small" class="js-switch" />

                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endif