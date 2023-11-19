/**
 * ACTION BUTTONS
 * @author Ahmed Shan (@thaanu16)
 */
var HFActionBtns = document.querySelectorAll('.HFActionBtn');
if (HFActionBtns.length > 0) {
    
    for (var i = 0; i < HFActionBtns.length; i++ ) {

        // Add event listener
        HFActionBtns[i].addEventListener('click', function (event) {
            
            // Override the default action
            event.preventDefault();

            var actionBtn = this;
            var action = actionBtn.getAttribute('href');
            var next_screen = actionBtn.getAttribute('data-ns');

            swal({
                title: actionBtn.getAttribute('data-title'),
                text: actionBtn.getAttribute('data-text'),
                type: actionBtn.getAttribute('data-type'),
                dangerMode: true,
                showCancelButton: true,
                showConfirmButton: true,
                confirmButtonText: "Continue",
                closeOnConfirm: false
            }, function () { 

                fetch(action, {
                    method: "POST",
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.status == true) {
                            swal({
                                title: 'Alright!',
                                text: data.textMessage,
                                type: 'success',
                                showConfirmButton: true,
                                confirmButtonText: "Ok",
                                closeOnConfirm: true
                            }, function () {  
                                window.location.href = next_screen;
                            });
                        } else {
                            swal("Whoops!!", data.error, "error");
                        }
                    })
                    .catch((error) => {
                        swal("Whoops!!", error, "error");
                    });

            });

        });

    }

}