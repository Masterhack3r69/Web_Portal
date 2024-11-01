<?php
function showAlert($message, $type = 'warning') {

    echo '
    <div class="alert alert-' . htmlspecialchars($type) . ' alert-dismissible fade show position-absolute top-0 start-50 translate-middle-x mt-5" role="alert" style="z-index: 1050; max-width: 500px;">
        <strong>' . ucfirst($type) . '!</strong> ' . htmlspecialchars($message) . '
        <button type="button" class="btn btn-close text-dark btn-lg py-3 shadow-none" data-bs-dismiss="alert" aria-label="Close"><i class="fa fa-times"></i></button>
    </div>';
}


?>