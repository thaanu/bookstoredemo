
@if ( $count == 0 )
    <p><em>Requested user was not found</em></p>
@else
    
    <form action="{{ cpanelPermalink('users/update/'.$user->user_id) }}" method="post" id="user-update-form" >
        @include('admin-panel.components.form.input', [
            'type' => 'text',
            'label' => 'Full Name',
            'name' => 'full_name',
            'id' => 'full_name',
            'value' => $user->full_name
        ])
        @include('admin-panel.components.form.input', [
            'type' => 'email',
            'label' => 'Email',
            'name' => 'email',
            'id' => 'email',
            'value' => $user->email
        ])
        @include('admin-panel.components.form.select', [
            'label' => 'User Group',
            'name' => 'user_group',
            'id' => 'user_group',
            'options' => $groups,
            'value' => $user->user_group
        ])
        @include('admin-panel.components.form.input', [
            'type' => 'password',
            'label' => 'Password',
            'name' => 'user_password',
            'id' => 'user_password'
        ])
        <p class="text-info">Enter a new password only to change the password</p>
        @include('admin-panel.components.form.checkbox', [
            'label' => 'Should take your',
            'name' => 'take_tour',
            'id' => 'take_tour'
        ])
        @include('admin-panel.components.form.submit', [
            'label' => 'Save',
            'name' => 'submit',
            'id' => 'submit'
        ])
    </form>

@endif
