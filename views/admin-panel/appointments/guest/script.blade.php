<script>
    // GET APPOINTMENTS

    function loadAppointments( searchGuestFormData ) {
        let appointmentsContainer = document.querySelector('#appointments-container');
        appointmentsContainer.innerHTML = loadingContent;
        hfPostRequest(appointmentFetchURL, searchGuestFormData).then(response => {
            if ( response.status == 200 ) {
                appointmentsContainer.innerHTML = response.view;

                let myOffcanvas = document.getElementById('pageOffCanvas');
                let bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas);

                // Change Appointment
                let changeApptBtns = document.querySelectorAll('.change-appt-btn');
                if ( changeApptBtns.length > 0 ) {
                    for ( let i = 0; i < changeApptBtns.length; i++ ) {
                        changeApptBtns[i].addEventListener('click', function(e) {
                            let apptId = e.target.dataset.apptId;
                            myOffcanvas.querySelector('#pageOffCanvasLabel').innerHTML = 'Change Appointment Date';
                            myOffcanvas.querySelector('.offcanvas-body').innerHTML = loadingContent;
                            bsOffcanvas.show();
                            hfPostRequest(`${urlPrefix}/change-appt-date-view/${apptId}`).then(response => {

                                if ( response.status == 200 ) {
                                    myOffcanvas.querySelector('.offcanvas-body').innerHTML = response.view;

                                    let newApptTimeSlotBtns = myOffcanvas.querySelectorAll('.chng-appt-btn');
                                    if ( newApptTimeSlotBtns.length > 0 ) {
                                        for ( let i = 0; i < newApptTimeSlotBtns.length; i++ ) {
                                            newApptTimeSlotBtns[i].addEventListener('click', function(event) {
                                                event.preventDefault();

                                                let selectionBtnText = event.target.innerHTML;
                                                event.target.innerHTML = "Please wait...";

                                                let formData = new FormData();
                                                slotNumber = event.target.dataset.slotNumber;
                                                specialityCode = event.target.dataset.specialityCode;
                                                timeInfo = event.target.dataset.timeInfo;
                                                formData.append('doctor_mcr', event.target.dataset.doctorMcr);
                                                formData.append('action', event.target.dataset.action);
                                                formData.append('start_date', event.target.dataset.startDate);
                                                formData.append('slot_number', slotNumber);
                                                formData.append('speciality_code', specialityCode);
                                                formData.append('time_info', timeInfo);
                                                formData.append('parent_appt_id', event.target.dataset.parentApptId);

                                                hfPostRequest(`${urlPrefix}/change-appointment`, formData).then(response => {
                                                    event.target.innerHTML = selectionBtnText;
                                                    if ( response.status == 200 ) {
                                                        bsOffcanvas.hide();
                                                        hf_success_toast(response.textMessage);
                                                        loadAppointments( searchGuestFormData );
                                                    } else {
                                                        hf_error_toast(response.error);
                                                    }

                                                });

                                            })
                                        }
                                    }

                                } else {
                                    hf_error_toast(response.error);
                                }
                            });
                        });
                    }
                }

                // Collect Deposit
                let collectDepositBtns = document.querySelectorAll('.collect-deposit-btn');
                if ( collectDepositBtns.length > 0 ) {
                    for ( let i = 0; i < collectDepositBtns.length; i++ ) {
                        collectDepositBtns[i].addEventListener('click', function(e) {
                            e.preventDefault();
                            let apptId = e.target.dataset.apptId;
                            // Initialize Deposit collect is a function inside script.js from the parent directory
                            initDepositCollect(apptId, loadAppointments, searchGuestFormData);
                        });
                    }
                }

                // Cancel Appointment
                let cancelAppointmentBtns = document.querySelectorAll('.cancel-appt-btn');
                if ( cancelAppointmentBtns.length > 0 ) {
                    for ( let i = 0; i < cancelAppointmentBtns.length; i++ ) {
                        cancelAppointmentBtns[i].addEventListener('click', function(e) {
                            e.preventDefault();
                            let apptId = e.target.dataset.apptId;
                            myOffcanvas.querySelector('#pageOffCanvasLabel').innerHTML = 'Cancel Appointment';
                            myOffcanvas.querySelector('.offcanvas-body').innerHTML = loadingContent;
                            bsOffcanvas.show();
                            
                            hfPostRequest(`${urlPrefix}/appt-cancel-form/${apptId}`).then(response => {
                                if ( response.status == 200 ) {
                                    myOffcanvas.querySelector('.offcanvas-body').innerHTML = response.view;
                                    let cancelForm = document.getElementById('appt-cancel-form');
                                    cancelForm.addEventListener('submit', function(e) {
                                        e.preventDefault();
                                        cancelForm.querySelector('#submit').setAttribute('disabled', 'disabled');
                                        let cancelFormData = new FormData(cancelForm);
                                        hfPostRequest(`${urlPrefix}/cancel-appointment/${apptId}`, cancelFormData).then(response => {
                                            cancelForm.querySelector('#submit').removeAttribute('disabled');
                                            if ( response.status == 200 ) {
                                                bsOffcanvas.hide();
                                                hf_success_toast(response.textMessage);
                                                loadAppointments(searchGuestFormData);
                                            } else {
                                                hf_error_toast(response.error);
                                            }
                                        });
                                        
                                    });
                                } else {
                                    hf_error_toast(response.error);
                                    bsOffcanvas.show();
                                }
                            });

                        });
                    }
                }
                


                // Change Appointment Page
                let swApptBtns = document.querySelectorAll('.sw-appt-pg');
                if ( swApptBtns.length > 0 ) {
                    for ( let i = 0; i < swApptBtns.length; i++ ) {
                        swApptBtns[i].addEventListener('click', function(e) {
                            e.preventDefault();
                            appointmentFetchURL = e.target.href;
                            loadAppointments(searchGuestFormData);
                        });
                    }
                }

            } else {
                hf_error_toast(response.error);
            }
        });
    }

    let searchGuestFormData;
    let appointmentFetchURL = `${urlPrefix}/get-appointments/1`;
    
    let searchGuestForm = document.querySelector('#search-guest-form');
    if ( searchGuestForm != null && searchGuestForm.length > 0 ) {
        searchGuestForm.addEventListener('submit', function(e){
            e.preventDefault();
            searchGuestFormData = new FormData(searchGuestForm);
            loadAppointments(searchGuestFormData);
        });
    }

</script>