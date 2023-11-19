<script>

    function loadDoctors( specialityCode ) {
        appContainer.innerHTML = loadingContent;
        hfPostRequest(`${urlPrefix}/list-doctors/${specialityCode}`).then( response => {
            if ( response.status == 200 ) {
                appContainer.innerHTML = response.view;
                initDoctorSelect();
            } else {
                hf_error_toast(response.error);
            }
        } );
    }

    function selectDoctor(doctorId) {

        // Prompt modal to enter PRN/NID Number
        pageModal = new bootstrap.Modal(document.querySelector('#pageModal'));
        pageModal.show();

        // Submit patient search form
        let searchForm = document.getElementById('searchGuestForm');
        searchForm.addEventListener('submit', function(e){
            e.preventDefault();
            let searchFormSubmitBtn = searchForm.querySelector('#submit-form');
            let searchFormSubmitBtnDefaultText = searchFormSubmitBtn.innerHTML;
            let searchFormData = new FormData(searchForm);
            let searchResults = document.querySelector('#search-results');
            searchFormSubmitBtn.setAttribute('disabled', 'disabled');
            searchFormSubmitBtn.innerHTML = 'Please wait...';
            searchResults.innerHTML = `<p class="placeholder-glow"><span class="placeholder col-8"></span>
                                        <span class="placeholder col-3"></span>
                                        <span class="placeholder col-12"></span></p>`;
            hfPostRequest(`${urlPrefix}/search-patient`, searchFormData).then(response => {
                searchFormSubmitBtn.removeAttribute('disabled');
                searchFormSubmitBtn.innerHTML = searchFormSubmitBtnDefaultText;
                if ( response.status == 200 ) {
                    searchResults.innerHTML = response.view;

                    // Handle select guest btn
                    let selectGuestBtns = searchResults.querySelectorAll('.select-guest-btn');
                    if ( selectGuestBtns.length > 0 ) {
                        for ( let i = 0; i < selectGuestBtns.length; i++ ) {
                            selectGuestBtns[i].addEventListener('click', function(e){
                                e.preventDefault();

                                let myOffcanvas = document.getElementById('pageOffCanvas');
                                let bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas);
                                myOffcanvas.querySelector('#pageOffCanvasLabel').innerHTML = 'Doctor Availability';
                                myOffcanvas.querySelector('.offcanvas-body').innerHTML = loadingContent;

                                pageModal.hide();
                                bsOffcanvas.show();

                                let guestInfo = selectGuestBtns[i].getAttribute('data-guest-info');
                                let prn = selectGuestBtns[i].getAttribute('data-guest-prn');
                                let availabilityData = new FormData();
                                availabilityData.append('prn', prn);
                                
                                hfPostRequest(`${urlPrefix}/select-doctor/${doctorId}`, availabilityData).then( response => {
                                    if ( response.status == 200 ) {

                                        myOffcanvas.querySelector('.offcanvas-body').innerHTML = response.view;
                                        
                                        // Handling time slot selection buttons
                                        let selectionBtns = document.querySelectorAll('.selection-btn');
                                        if ( selectionBtns.length > 0 ) {
                                            for ( let i = 0; i < selectionBtns.length; i++ ) {
                                                selectionBtns[i].addEventListener('click', function(event) {

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
                                                    formData.append('guest_info', guestInfo);
                                                    formData.append('time_info', timeInfo);

                                                    hfPostRequest(`${urlPrefix}/make-appointment`, formData).then(response => {
                                                        event.target.innerHTML = selectionBtnText;
                                                        if ( response.status == 200 ) {
                                                            bsOffcanvas.hide();
                                                            hf_success_toast(response.textMessage);
                                                            
                                                            // Initialize Deposit Collect
                                                            initDepositCollect(response.appointmentId);

                                                        } else {
                                                            hf_error_toast(response.error);
                                                        }

                                                    });
                                                });
                                            }
                                        }

                                    } else {
                                        hf_error_toast(response.error);
                                        bsOffcanvas.hide();
                                    }
                                } );

                            });
                        }
                    }

                } else {
                    hf_error_toast(response.error);
                }
            });
        });
        
    }

    function initDoctorSelect() {
        let docSelectBtns = document.querySelectorAll('.select-doctor-btn');
        if ( docSelectBtns.length > 0 ) {
            for ( let i = 0; i < docSelectBtns.length; i++ ) {
                let docbtn = docSelectBtns[i];
                docbtn.addEventListener('click', function(e){
                    let doctorId = e.target.dataset.doctorId;
                    selectDoctor(doctorId);
                });
            }
        }
    }

    function initDepositCollect(appointmentId, callbackFunction = '', args = '') {
        hfPostRequest(`${urlPrefix}/collect-deposit-form/${appointmentId}`).then(response => {

            if ( response.status == 200 ) {
                let myOffcanvas = document.getElementById('pageOffCanvas');
                let bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas);
                myOffcanvas.querySelector('#pageOffCanvasLabel').innerHTML = 'Deposit Collection';
                myOffcanvas.querySelector('.offcanvas-body').innerHTML = loadingContent;
                myOffcanvas.querySelector('.offcanvas-body').innerHTML = response.view;
                bsOffcanvas.show();
    
                // Handle deposit collect form
                let depositCollectForm = document.getElementById('appt-deposit-collect-form');
                depositCollectForm.addEventListener('submit', function(e){
                    e.preventDefault();
                    let submitBtn = depositCollectForm.querySelector('#submit');
                    let defaultText = submitBtn.innerHTML;
                    submitBtn.setAttribute('disabled', 'disabled');
                    submitBtn.innerHTML = 'Please wait...';
                    let depositData = new FormData(depositCollectForm);
                    hfPostRequest(`${urlPrefix}/collect-deposit/${appointmentId}`,depositData).then(response => {
                        submitBtn.removeAttribute('disabled');
                        submitBtn.innerHTML = defaultText;
                        if ( response.status == 200 ) {
                            hf_success_toast(response.textMessage);
                            bsOffcanvas.hide();

                            if ( callbackFunction ) {
                                callbackFunction(args);
                            }

                        }
                        else {
                            hf_error_toast(response.error);
                        }
                    });
                });
            }
            else {
                hf_error_toast(response.error);
            }
        });
    }

    initDoctorSelect();

    let slotNumber;
    let urlPrefix = '/cp/appointments/ajax';
    let appContainer = document.querySelector('#appContainer');
    let loadingContent = `<div class="d-flex justify-content-center p-5"><div class="spinner-border text-primary" role="status"></div></div>`;
    let pageModal;

    let searchControlInput = document.querySelector('#search-control-input')
    if ( searchControlInput != null ) {
        searchControlInput.addEventListener('keyup', function(e) {
            e.preventDefault();
            let searchQuery = e.target.value;
            let doctorGrid = document.querySelector('#doctor-grid');
            let cards = doctorGrid.querySelectorAll('.card-title');
            for (var i = 0; i < cards.length; i++) {
                if(cards[i].innerText.toLowerCase().includes(searchQuery.toLowerCase())) {
                    var t = cards[i].dataset.targetId;
                    document.getElementById(t).style.display = "block";
                } else {
                    var t = cards[i].dataset.targetId;
                    document.getElementById(t).style.display = "none";
                }
            }
        }); 
    }

    $(document).ready(function(){
        let specialitySelectControl = $('#speciality-select');
        specialitySelectControl.select2();
        specialitySelectControl.on('select2:select', function (e) {
            let sCode = $(this).val();
            loadDoctors(sCode);
        });
    });

</script>