<script>
    let loadingContent = `<div class="d-flex justify-content-center p-5"><div class="spinner-border text-primary" role="status"></div></div>`;

    let elemContent = document.querySelector('#content');

    function fetchUsers() {
        hfPostRequest('/cp/users/ajax/fetch-users').then(response => {
            if ( response.status == 200 ) {
                elemContent.innerHTML = response.view;
                
                // Update User
                let userUpBtns = document.querySelectorAll('.update-btn');
                if ( userUpBtns.length > 0 ) {
                    for ( let i = 0; i < userUpBtns.length; i++ ) {
                        
                        let myOffcanvas = document.getElementById('pageOffCanvas');
                        let bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas);
                        userUpBtns[i].addEventListener('click', function(e) {
                            e.preventDefault();
                            
                            myOffcanvas.querySelector('#pageOffCanvasLabel').innerHTML = 'Update User';
                            myOffcanvas.querySelector('.offcanvas-body').innerHTML = loadingContent;

                            let updateUrl = e.target.href;
                            bsOffcanvas.show();
                            
                            // Fetch user information
                            hfGetRequest(updateUrl).then(response => {
                                if ( response.status == 200 ) {
                                    myOffcanvas.querySelector('.offcanvas-body').innerHTML = response.view;

                                    // Handle Update Form
                                    let userUpdateForm = myOffcanvas.querySelector('#user-update-form');
                                    userUpdateForm.addEventListener('submit', function(e) {
                                        e.preventDefault();
                                        let form = new FormData(userUpdateForm);
                                        hfPostRequest(userUpdateForm.getAttribute('action'), form).then(response => {
                                            if ( response.status == 200 ) {
                                                hf_success_toast(response.textMessage);
                                                fetchUsers();
                                                bsOffcanvas.hide();
                                            } else {
                                                hf_error_toast(response.error);
                                            }
                                        });
                                    });

                                } else {
                                    hf_error_toast(response.error);
                                    bsOffcanvas.hide();
                                }
                            });
                            
                        });

                    }
                }

            } else {
                hf_error_toast(response.error);
            }
        });
    }

    fetchUsers();

    // New User
    document.querySelector('#create-user-btn').addEventListener('click', function(e) {
        e.preventDefault();

        let myOffcanvas = document.getElementById('pageOffCanvas');
        let bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas);
        
        myOffcanvas.querySelector('#pageOffCanvasLabel').innerHTML = 'Create New User';
        myOffcanvas.querySelector('.offcanvas-body').innerHTML = loadingContent;

        let createUrl = e.target.href;
        bsOffcanvas.show();
        
        // Fetch user information
        hfGetRequest(createUrl).then(response => {
            if ( response.status == 200 ) {
                myOffcanvas.querySelector('.offcanvas-body').innerHTML = response.view;
                // Handle Update Form
                let userCreateForm = myOffcanvas.querySelector('#user-create-form');
                userCreateForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    let form = new FormData(userCreateForm);
                    hfPostRequest(userCreateForm.getAttribute('action'), form).then(response => {
                        if ( response.status == 200 ) {
                            hf_success_toast(response.textMessage);
                            fetchUsers();
                            bsOffcanvas.hide();
                        } else {
                            hf_error_toast(response.error);
                        }
                    });
                });
            } else {
                hf_error_toast(response.error);
                bsOffcanvas.hide();
            }
        });



    });

    

</script>