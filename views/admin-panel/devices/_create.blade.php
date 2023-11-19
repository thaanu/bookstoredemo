<form action="{{ cpanelPermalink('devices/ajax/add-device') }}" method="post" id="device-form" >
    @include('admin-panel.components.form.input', [
        'type' => 'text',
        'label' => 'Device Nickname',
        'name' => 'device_nickname',
        'id' => 'device_nickname'
    ])
    @include('admin-panel.components.form.select', [
        'label' => 'Device Type',
        'name' => 'device_type',
        'id' => 'device_type',
        'options' => $device_types
    ])
    @include('admin-panel.components.form.select', [
        'label' => 'Device Location',
        'name' => 'location_id',
        'id' => 'location_id',
        'options' => $locations
    ])
    @include('admin-panel.components.form.submit', [
        'label' => 'Add Device',
        'name' => 'submit',
        'id' => 'submit'
    ])
</form>