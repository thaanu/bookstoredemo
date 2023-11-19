// Success Message
// tmr : given in ms for auto dismiss
function hf_show_success_msg(text, tmr = null) {  
    Swal.fire({
        title: 'Success',
        text: text,
        icon: "success",
        timer: tmr,
        showConfirmButton: false
    });
}

// Error Message
// tmr : given in ms for auto dismiss
function hf_show_error_msg(text, tmr = null) {  
    Swal.fire({
        title: 'Whoops',
        text: text,
        icon: "error",
        timer: tmr,
        showConfirmButton: true
    });
}

// Warning Message
// tmr : given in ms for auto dismiss
function hf_show_warning_msg(text, tmr = null) {  
    Swal.fire({
        title: 'Whoops',
        text: text,
        icon: "error",
        timer: tmr,
        showConfirmButton: true
    });
}

// Success Toast
function hf_success_toast(text)
{
    // toastr.success(text)
    // $.Notification.notify('success', 'top right', 'Awesome', text);
    $.toast({
        heading: 'Awesome!',
        text: text,
        icon: 'success',
        position: 'top-center',
        showHideTransition: 'slide',
        loader: true
    });
}

// Error Toast
function hf_error_toast(text)
{
    // toastr.error(text)
    // $.Notification.notify('error', 'top right', 'Oops!!', text);
    $.toast({
        heading: 'Oops!',
        text: text,
        icon: 'error',
        position: 'top-center',
        showHideTransition: 'slide',
        loader: true
    });
}

// Warning Toast
function hf_warning_toast(text)
{
    // toastr.warning(text)
    // $.Notification.notify('warning', 'top right', 'Ummm..', text);
    $.toast({
        heading: 'Ummm!',
        text: text,
        icon: 'warning',
        position: 'top-center',
        showHideTransition: 'slide',
        loader: true
    });
}

// Info Toast
function hf_info_toast(text)
{
    // toastr.info(text)
    // $.Notification.notify('info', 'top right', 'Just to let you know', text);
    $.toast({
        heading: 'Just to let you know!',
        text: text,
        icon: 'info',
        position: 'top-center',
        showHideTransition: 'slide',
        loader: true
    });
}

function handle_field_errors( errorFields ) 
{
    for (var f = 0; f < errorFields.length; f++) {
        hf_error_toast(errorFields[f].message);
        var elem = errorFields[f].field;
        // document.getElementById(elem).classList.add('is-invalid');
        let formGroup = hfFindParentBySelector(document.getElementById(elem), '.hf-form-group');
        formGroup.classList.add('has-error');
    }
}
