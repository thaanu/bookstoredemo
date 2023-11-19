<form action="{{ cpanelPermalink('locations/ajax/add-location') }}" method="post" id="location-form" >
    @include('admin-panel.components.form.input', [
        'type' => 'text',
        'label' => 'Location Name',
        'name' => 'location_name',
        'id' => 'device_nickname'
    ])
    @include('admin-panel.components.form.submit', [
        'label' => 'Add Location',
        'name' => 'submit',
        'id' => 'submit'
    ])
</form>