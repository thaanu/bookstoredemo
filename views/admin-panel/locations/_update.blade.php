<form action="{{ cpanelPermalink('locations/ajax/update-location/'.$location['location_id']) }}" method="post" id="location-form" >
    @include('admin-panel.components.form.input', [
        'type' => 'text',
        'label' => 'Location Name',
        'name' => 'location_name',
        'id' => 'location_name',
        'value' => $location['location_name']
    ])
    @include('admin-panel.components.form.select', [
        'label' => 'Location Status',
        'name' => 'is_active',
        'id' => 'is_active',
        'options' => [
            '1' => 'Active',
            '0' => 'In-Active'
        ],
        'value' => $location['is_active']
    ])
    @include('admin-panel.components.form.submit', [
        'label' => 'Update Location',
        'name' => 'submit',
        'id' => 'submit'
    ])
</form>