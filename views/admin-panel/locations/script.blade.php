<script>
    let loadingContent =
        `<div class="d-flex justify-content-center p-5"><div class="spinner-border text-primary" role="status"></div></div>`;

    function _a(element) {
        return document.querySelector(element);
    }
    function __a(element) {
        return document.querySelectorAll(element);
    }

    function getLocations() {
        hfPostRequest('/cp/locations/ajax/fetch-locations').then(response => {
            if (response.status == 200) {
                let myOffcanvas = document.getElementById('pageOffCanvas');
                let bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas);
                _a('#pg-content').innerHTML = response.view;
                _a('#add-location-btn').addEventListener('click', function(e) {
                    myOffcanvas.querySelector('#pageOffCanvasLabel').innerHTML = 'Add Location';
                    myOffcanvas.querySelector('.offcanvas-body').innerHTML = loadingContent;
                    bsOffcanvas.show();
                    handleForm('/cp/locations/ajax/add-location-form', myOffcanvas.querySelector('.offcanvas-body'), bsOffcanvas);
                });
                let editBtns = __a('.edit-btn');
                if ( editBtns.length > 0 ) {
                    for ( let i = 0; i < editBtns.length; i++ ) {
                        editBtns[i].addEventListener('click', function(e) {
                            myOffcanvas.querySelector('#pageOffCanvasLabel').innerHTML = 'Edit Location';
                            myOffcanvas.querySelector('.offcanvas-body').innerHTML = loadingContent;
                            bsOffcanvas.show();
                            handleForm(e.target.dataset.url, myOffcanvas.querySelector('.offcanvas-body'), bsOffcanvas);
                        }); 
                    }
                }
                let removeBtns = __a('.remove-btn');
                if ( removeBtns.length > 0 ) {
                    for ( let i = 0; i < removeBtns.length; i++ ) {
                        removeBtns[i].addEventListener('click', function(e) {
                            hfPostRequest(e.target.dataset.url).then(response => {
                                if ( response.status == 200 ) {
                                    hf_success_toast(response.textMessage);
                                    getLocations();
                                } else {
                                    hf_error_toast(response.error);
                                }
                            });
                        }); 
                    }
                }
            } else {
                hf_error_toast(response.error);
            }
        })
    }

    function handleForm(form, element, canvas) {
        hfPostRequest(form).then(response => {
            if (response.status == 200) {
                element.innerHTML = response.view;

                // Handle the form
                let theForm = _a('#location-form');
                theForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    let target = e.target.action;
                    let form = new FormData(theForm);
                    hfPostRequest(target, form).then(response => {
                        if (response.status == 200) {
                            hf_success_toast(response.textMessage);
                            getLocations();
                            canvas.hide();
                        } else {
                            hf_error_toast(response.error);
                        }
                    });
                });

            } else {
                hf_error_toast(response.error);
            }
        });
    }

    getLocations();
</script>
