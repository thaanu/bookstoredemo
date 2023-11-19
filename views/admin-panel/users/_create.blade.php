<form action="{{ cpanelPermalink('users/create') }}" method="post" id="user-create-form" >
    @include('admin-panel.components.form.input', [
        'type' => 'text',
        'label' => 'Full Name',
        'name' => 'full_name',
        'id' => 'full_name'
    ])
    @include('admin-panel.components.form.input', [
        'type' => 'email',
        'label' => 'Email',
        'name' => 'email',
        'id' => 'email'
    ])
    @include('admin-panel.components.form.select', [
        'label' => 'User Group',
        'name' => 'user_group',
        'id' => 'user_group',
        'options' => $groups
    ])
    @include('admin-panel.components.form.input', [
        'type' => 'password',
        'label' => 'Password',
        'name' => 'user_password',
        'id' => 'user_password'
    ])
    @include('admin-panel.components.form.submit', [
        'label' => 'Save',
        'name' => 'submit',
        'id' => 'submit'
    ])
</form>