@if ( $data['count'] == 0 )
    <p class="text-center">No data for this device</p>
@else
    
    <table class="table">
        <thead>
            <tr>
                <th>Date Time</th>
                <th>Temperature</th>
                <th>Humidity</th>
                <th>Cloudiness</th>
                <th>Precipitation</th>
                <th>Wind</th>
                <th>Atmosph. Pressure</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['data'] as $read)
            <tr>
                <td>{{ $read['entry_dt'] }}</td>
                <td>{{ $read['temp_v'] ?? 'NA' }}</td>
                <td>{{ $read['humidity'] ?? 'NA' }}</td>
                <td>{{ $read['cloudiness'] ?? 'NA' }}</td>
                <td>{{ $read['precipitation'] ?? 'NA' }}</td>
                <td>{{ $read['wind'] ?? 'NA' }}</td>
                <td>{{ $read['atmp'] ?? 'NA' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

@endif