<script>
    let loadingContent =
        `<div class="d-flex justify-content-center p-5"><div class="spinner-border text-primary" role="status"></div></div>`;

    function _a(element) {
        return document.querySelector(element);
    }
    function __a(element) {
        return document.querySelectorAll(element);
    }

    function getDevices() {
        hfPostRequest('/cp/groups/ajax/fetch-groups').then(response => {
            if (response.status == 200) {
                let myOffcanvas = document.getElementById('pageOffCanvas');
                let bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas);

                _a('#pg-content').innerHTML = response.view;

                // Switchery
                // var elems = document.querySelectorAll('.js-switch');
                // if ( elems.length > 0 ) { for ( let i = 0; i < elems.length; i++ ) {
                //     let elem = elems[i];
                //     var init = new Switchery(elem);
                //     elem.addEventListener('change', function(e) {
                //         let checked = 'off';
                //         if (elem.checked == true){ checked = 'on'; }
                //         let form = new FormData();
                //         form.append('device_status', checked);
                //         hfPostRequest(e.target.dataset.url, form).then(response => {
                //             if ( response.status != 200 ) {
                //                 hf_error_toast(response.error);
                //             }
                //         });
                //     });
                // } }

                _a('#add-group-btn').addEventListener('click', function(e) {
                    myOffcanvas.querySelector('#pageOffCanvasLabel').innerHTML = 'New Group';
                    myOffcanvas.querySelector('.offcanvas-body').innerHTML = loadingContent;
                    bsOffcanvas.show();
                    handleForm('/cp/groups/ajax/add-group-form', myOffcanvas.querySelector('.offcanvas-body'), bsOffcanvas);
                });
                let editBtns = __a('.edit-btn');
                if ( editBtns.length > 0 ) {
                    for ( let i = 0; i < editBtns.length; i++ ) {
                        editBtns[i].addEventListener('click', function(e) {
                            myOffcanvas.querySelector('#pageOffCanvasLabel').innerHTML = 'Edit Device';
                            myOffcanvas.querySelector('.offcanvas-body').innerHTML = loadingContent;
                            bsOffcanvas.show();
                            handleForm(e.target.dataset.url, myOffcanvas.querySelector('.offcanvas-body'), bsOffcanvas);
                        }); 
                    }
                }
                let readingBtns = __a('.reading-btn');
                if ( readingBtns.length > 0 ) {
                    for ( let i = 0; i < readingBtns.length; i++ ) {
                        readingBtns[i].addEventListener('click', function(e) {
                            hfPostRequest(e.target.dataset.url).then(response => {
                                if ( response.status == 200 ) {
                                    const myModalAlternative = new bootstrap.Modal('#a-large-modal');
                                    _a('#a-large-modal').querySelector('.modal-title').innerHTML = 'Reading';
                                    _a('#a-large-modal').querySelector('.modal-body').innerHTML = response.view;
                                    myModalAlternative.show();
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

                // Remove device function
                // let removeBtn = element.querySelector('#remove-btn');
                // if ( removeBtn !== undefined ) {
                //     removeBtn.addEventListener('click', function(e) {
                //         hfPostRequest(e.target.dataset.url).then(response => {
                //             if ( response.status == 200 ) {
                //                 hf_success_toast(response.textMessage);
                //                 getDevices();
                //                 canvas.hide();
                //             } else {
                //                 hf_error_toast(response.error);
                //             }
                //         });
                //     });
                // }

                // Handle the form
                let theForm = _a('#group-form');
                theForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    let target = e.target.action;
                    let form = new FormData(theForm);
                    hfPostRequest(target, form).then(response => {
                        if (response.status == 200) {
                            hf_success_toast(response.textMessage);
                            getDevices();
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

    getDevices();
</script>
