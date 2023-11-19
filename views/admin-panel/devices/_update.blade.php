<form action="{{ cpanelPermalink('devices/ajax/update-device/'.$device['device_id']) }}" method="post" id="device-form" >
    @include('admin-panel.components.form.input', [
        'type' => 'text',
        'label' => 'Device Nickname',
        'name' => 'device_nickname',
        'id' => 'device_nickname',
        'value' => $device['device_nickname']
    ])
    @include('admin-panel.components.form.select', [
        'label' => 'Device Type',
        'name' => 'device_type',
        'id' => 'device_type',
        'options' => $device_types,
        'value' => $device['device_type']
    ])
    @include('admin-panel.components.form.select', [
        'label' => 'Device Location',
        'name' => 'location_id',
        'id' => 'location_id',
        'options' => $locations,
        'value' => $device['location_id']
    ])
    @include('admin-panel.components.form.select', [
        'label' => 'Device Status',
        'name' => 'device_status',
        'id' => 'device_status',
        'options' => [
            'on' => 'On',
            'off' => 'Off'
        ],
        'value' => $device['device_status']
    ])
    @include('admin-panel.components.form.submit', [
        'label' => 'Update Device',
        'name' => 'submit',
        'id' => 'submit'
    ])
</form>

<hr>

<p class="text-danger"><strong>Remove Device</strong></p>
<p>Please confirm before removing the device</p>
<button data-url="{{ cpanelPermalink('devices/ajax/remove-device/'.$device['device_id']) }}" id="remove-btn" class="btn btn-danger">Remove</button> 


