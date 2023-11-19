@if ( $appointment['count'] == 0 )
    No Appointment Found
@else
    
    <form action="#" id="appt-cancel-form" method="post">
        @include('admin-panel.components.form.textarea', [
            'name' => 'cancel_reason',
            'id' => 'cancel_reason',
            'label' => 'Reason for cancelling the appointment'
        ])

        @include('admin-panel.components.form.submit', [
            'name' => 'submit',
            'id' => 'submit',
            'label' => 'Done'
        ])
    </form>

@endif