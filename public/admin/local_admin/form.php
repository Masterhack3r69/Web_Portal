<?php 
include '../includes/header.php';


$sql = "SELECT * FROM forms"; 
$result = query($sql); 

$forms = []; 
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $forms[] = $row; 
    }
}
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>
                    Forms
                    <a href="form_builder.php" class="btn bg-gradient-primary float-end d-none d-md-block">Form Builder</a>
                    <a href="form_builder.php" class="btn bg-gradient-primary float-end d-md-none" data-toggle="tooltip" title="Form Builder">
                        <i class="fa fa-plus"></i>
                    </a>
                </h5>
            </div>
            <div class="card-body">
                <table class="table table align-items-center text-center mb-0" style="font-size: 0.875rem;">
                    <thead>
                        <tr>
                            <th>Form Name</th>
                            <th class="d-none d-md-table-cell">Created At</th> 
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($forms) > 0): ?>
                            <?php foreach ($forms as $form): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($form['form_name']); ?></td>
                                <td class="d-none d-md-table-cell"><?php echo htmlspecialchars($form['created_at']); ?></td>
                                <td>
                                    <a href="#" class="btn bg-gradient-info text-white m-0 px-3 py-2" onclick="viewForm(<?php echo $form['id']; ?>)" data-toggle="tooltip" title="View Form">
                                        <i class="fa fa-eye"></i>
                                    </a>

                                    <a href="#" class="btn bg-gradient-success text-white m-0 px-3 py-2" data-toggle="tooltip" title="Copy Form" onclick="copyForm(<?php echo $form['id']; ?>)">
                                        <i class="fa fa-copy"></i>
                                    </a>
                                    <a href="edit_form_builder.php?id=<?php echo $form['id']; ?>" class="btn bg-gradient-warning text-white m-0 px-3 py-2" data-toggle="tooltip" title="Edit Form">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <!--                                     
                                    <a href="#" class="btn bg-gradient-danger px-3 py-2 mb-0" data-toggle="tooltip" title="Delete Form" onclick="confirmDeleteForm(<?php //echo $form['id']; ?>)">
                                        <i class="fa fa-trash"></i>
                                    </a> -->
                                </td>
                            </tr>
                            <div id="form-fields-<?php echo $form['id']; ?>" style="display: none;">
                                <?php echo $form['form_html']; ?>
                            </div>
                        <?php endforeach; ?>

                        <?php else: ?>
                            <tr>
                                <td colspan="4">No forms available.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="formViewModal" tabindex="-1" aria-labelledby="formViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formViewModalLabel">Form Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal-form-preview"></div>
            </div>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
?>

<!-- <script>
  function confirmDeleteForm(formId) {
    // Check if the form is used by any program
    fetch('check_form_usage.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: formId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.inUse) {
            // If the form is in use, show an alert and do not proceed with deletion
            swal("Cannot delete form!", "This form is currently in use by one or more programs.", {
                icon: "warning",
            });
        } else {
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this form!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    fetch('delete_form.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ id: formId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            swal("Poof! Your form has been deleted!", {
                                icon: "success",
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            swal("Error deleting the form.", {
                                icon: "error",
                            });
                        }
                    })
                    .catch(error => {
                        swal("An error occurred.", {
                            icon: "error",
                        });
                    });
                } else {
                    swal("Your form is safe!");
                }
            });
        }
    })
    .catch(error => {
        swal("An error occurred while checking form usage.", {
            icon: "error",
        });
    });
}


</script> -->

<script>
    function copyForm(formId) {
        fetch('copy_form.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: formId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                swal("Form copied successfully!", {
                    icon: "success",
                }).then(() => {
                    location.reload(); // Reload to see the new form in the list
                });
            } else {
                swal("Error copying the form.", {
                    icon: "error",
                });
            }
        })
        .catch(error => {
            swal("An error occurred.", {
                icon: "error",
            });
        });
    }
</script>