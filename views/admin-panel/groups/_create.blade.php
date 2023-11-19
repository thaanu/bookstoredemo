<form action="{{ cpanelPermalink('groups/ajax/add-group') }}" method="post" id="group-form" >
    @include('admin-panel.components.form.input', [
        'type' => 'text',
        'label' => 'Group Name',
        'name' => 'group_name',
        'id' => 'group_name'
    ])

    @if ( $roles['count'] > 0 )
        @foreach ($roles['data'] as $i => $role)
            @include('admin-panel.components.form.checkbox', [
                'label' => $role['role_name'],
                'name' => 'roles[]',
                'id' => 'role_' . $i,
                'value' => $role['role_id']
            ])
        @endforeach
    @endif
    
    @include('admin-panel.components.form.submit', [
        'label' => 'Add Group',
        'name' => 'submit',
        'id' => 'submit'
    ])
</form>