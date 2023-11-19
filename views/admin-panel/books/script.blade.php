<script>
    let loadingContent =
        `<div class="d-flex justify-content-center p-5"><div class="spinner-border text-primary" role="status"></div></div>`;

    function _a(element) {
        return document.querySelector(element);
    }
    function __a(element) {
        return document.querySelectorAll(element);
    }

    function getBooks() {
        hfPostRequest('/cp/books/ajax/fetch-books').then(response => {
            if (response.status == 200) {
                let myOffcanvas = document.getElementById('pageOffCanvas');
                let bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas);

                _a('#pg-content').innerHTML = response.view;

                _a('#new-book-btn').addEventListener('click', function(e) {
                    myOffcanvas.querySelector('#pageOffCanvasLabel').innerHTML = 'New Book';
                    myOffcanvas.querySelector('.offcanvas-body').innerHTML = loadingContent;
                    bsOffcanvas.show();
                    handleForm('/cp/books/ajax/new-book-form', myOffcanvas.querySelector('.offcanvas-body'), bsOffcanvas);
                });
                let editBtns = __a('.edit-btn');
                if ( editBtns.length > 0 ) {
                    for ( let i = 0; i < editBtns.length; i++ ) {
                        editBtns[i].addEventListener('click', function(e) {
                            myOffcanvas.querySelector('#pageOffCanvasLabel').innerHTML = 'Edit Book';
                            myOffcanvas.querySelector('.offcanvas-body').innerHTML = loadingContent;
                            bsOffcanvas.show();
                            handleForm(e.target.dataset.url, myOffcanvas.querySelector('.offcanvas-body'), bsOffcanvas);
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
                let removeBtn = element.querySelector('#remove-btn');
                if ( removeBtn !== null ) {
                    removeBtn.addEventListener('click', function(e) {
                        hfPostRequest(e.target.dataset.url).then(response => {
                            if ( response.status == 200 ) {
                                hf_success_toast(response.textMessage);
                                getBooks();
                                canvas.hide();
                            } else {
                                hf_error_toast(response.error);
                            }
                        });
                    });
                }

                // Handle the form
                let theForm = _a('#a-book-form');
                theForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    let target = e.target.action;
                    let form = new FormData(theForm);
                    hfPostRequest(target, form).then(response => {
                        if (response.status == 200) {
                            hf_success_toast(response.textMessage);
                            getBooks();
                            canvas.hide();
                        } else if ( response.status == 400 ) {
                            if ( response.error_fields.length > 0 ) {
                                for ( let x = 0; x < response.error_fields.length; x++ ) {
                                    hf_error_toast(response.error_fields[x].message);
                                }
                            }
                        }else {
                            hf_error_toast(response.error);
                        }
                    });
                });

            } else {
                hf_error_toast(response.error);
            }
        });
    }

    getBooks();
</script>
