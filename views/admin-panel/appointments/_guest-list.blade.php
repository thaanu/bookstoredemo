@if ( empty($search_results) )
    <center>Unable to find guest</center>
@else

    <div class="list-group">
        @foreach ($search_results as $item)
        <a href="#" class="list-group-item list-group-item-action select-guest-btn" data-guest-prn="{{ $item['PRN'] }}" data-guest-info="{{ json_encode($item) }}">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1">{{ $item['FIRST_NAME'] }} {{ $item['MIDDLE_NAME'] }} {{ $item['LAST_NAME'] }}</h5>
                <small><i class="fas fa-birthday-cake"></i> {{ $item['DOB'] }}</small>
            </div>
            <p class="mb-1">{{ $item['PRN'] }} &bull; {{ $item['PATIENT_ID_NUMBER'] }}</p>
        </a>
        @endforeach
    </div>
    
    

@endif

