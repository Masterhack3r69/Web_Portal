// summer note
$(document).ready(function() {
$(".summernote").summernote({
placeholder: 'Write here',
tabsize: 2,
height: 100
});
$('.dropdown-toggle').dropdown();

});

var win = navigator.platform.indexOf('Win') > -1;
if (win && document.querySelector('#sidenav-scrollbar')) {
var options = {
damping: '0.5'
}
Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
}

// confirm delete
function confirmAction(url, title = "Are you sure?", text = "Once deleted, this cannot be undone!", icon = "warning", confirmButtonText = "Yes, delete it!") {
swal({
    title: title,
    text: text,
    icon: icon,
    buttons: true,
    dangerMode: true,
}).then((willConfirm) => {
    if (willConfirm) {
        window.location.href = url; 
    } else {
        swal("Action canceled!", { icon: "info" });
    }
});
}

function confirmActionUpdate(form, title = "Are you sure?", text = "You are about to update the program details!", icon = "warning", confirmButtonText = "Yes, update it!") {
    swal({
        title: title,
        text: text,
        icon: icon,
        buttons: true,
        dangerMode: true,
    }).then((willConfirm) => {
        if (willConfirm) {
            form.submit(); 
        } else {
            swal("Action canceled!", { icon: "info" });
        }
    });
}

// save form
function saveForm(formId) {
    const formFieldsContainer = document.getElementById('form-fields');
    const formHtml = formFieldsContainer.innerHTML;
    const formName = document.getElementById('form-name').value;

    if (!formName) {
        Swal.fire({
            title: 'Warning!',
            text: 'Please enter a form name.',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return;
    }

    if (!formFieldsContainer.querySelector('[id^="field-"]')) {
        Swal.fire({
            title: 'Warning!',
            text: 'Please add at least one field to the form.',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "form_builder.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            swal("Good job!", "Form saved successfully!", "success", {
                button: "Continue",
            });
        }
    };
    xhr.send("form_id=" + formId + "&form_name=" + encodeURIComponent(formName) + "&form_html=" + encodeURIComponent(formHtml));

    function showAlert(message, type = 'warning') {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show position-absolute top-0 start-50 translate-middle-x mt-5" role="alert" style="z-index: 1050; max-width: 500px;">
                <strong>${type.charAt(0).toUpperCase() + type.slice(1)}!</strong> ${message}
                <button type="button" class="btn btn-close text-dark btn-lg py-3 shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`;
        document.body.insertAdjacentHTML('beforeend', alertHtml);
    }
}



// form view
function viewForm(formId) {
    const formHtml = document.getElementById('form-fields-' + formId).innerHTML;  
    const modalFormPreview = document.getElementById('modal-form-preview');
    modalFormPreview.innerHTML = formHtml;
    const formViewModal = new bootstrap.Modal(document.getElementById('formViewModal'));
    formViewModal.show();
}

function viewFormNew() {
    const formHtml = document.getElementById('form-fields').innerHTML; 
    const modalFormPreview = document.getElementById('modal-form-preview-new');
    modalFormPreview.innerHTML = formHtml;
    const viewFormModal = new bootstrap.Modal(document.getElementById('viewFormModal'));
    viewFormModal.show();
}
