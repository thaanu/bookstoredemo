@if ( $locations['count'] == 0 )
    <p class="text-center">You do not have any devices</p>
    <p class="text-center">
        <button type="button" id="add-device-btn" class="btn btn-primary" >Add Device</button>
    </p>
@else
    
    <div class="py-2 mb-2">
        <button type="button" id="add-location-btn" class="btn btn-primary" >Add Location</button>
    </div>

    <div class="row">
        @foreach ($locations['data'] as $location)
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <h4>{{ $location['location_name'] }}</h4>
                        <p>
                            @if ( $location['is_active'] )
                                <span class="text text-success">Location is Active</span>
                            @else
                                <span class="text text-danger">Location is In-Active</span> 
                            @endif
                        </p>
                        <p>
                            <button data-url="{{ cpanelPermalink('locations/ajax/show-location/'.$location['location_id']) }}" class="edit-btn btn btn-sm btn-light" style="margin-right: 10px;">Edit</button> 
                            {{-- <button data-url="{{ cpanelPermalink('locations/ajax/remove-device/'.$location['location_id']) }}" class="remove-btn btn btn-sm btn-light" style="margin-right: 10px;">Remove</button>  --}}
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endif