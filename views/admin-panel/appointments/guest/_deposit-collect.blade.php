@if ( $appointment['count'] == 0 )
    No Appointment Found
@else
    
    <form action="#" id="appt-deposit-collect-form" method="post">
        @include('admin-panel.components.form.input', [
            'type' => 'text',
            'name' => 'collection_amount',
            'id' => 'collection_amount',
            'label' => 'Deposit collect amount'
        ])

        @include('admin-panel.components.form.select', [
            'name' => 'payment_code',
            'id' => 'payment_code',
            'label' => 'Payment Type',
            'options' => [
                'GUESTPORTAL' => 'Guest Portal'
            ]
        ])

        @include('admin-panel.components.form.submit', [
            'name' => 'submit',
            'id' => 'submit',
            'label' => 'Done'
        ])
    </form>

@endif