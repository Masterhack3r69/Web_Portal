<?php 
include '../includes/header.php';

$department_id = $_SESSION['department_id'];
$sql = "SELECT * FROM forms WHERE department_id = ?"; 

$result = query($sql, [$department_id]); 

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
            <div class="card-header pb-0">
                <h5>
                    Forms
                    <a href="form_builder.php" class="btn bg-gradient-primary float-end d-none d-md-block">Form Builder</a>
                    <a href="form_builder.php" class="btn bg-gradient-primary float-end d-md-none" data-toggle="tooltip" title="Form Builder">
                        <i class="fa fa-plus"></i>
                    </a>
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3 d-flex justify-content-end">
                    <input class="form-control p-1" type="text" id="input" onkeyup="searchBar()" placeholder="Search for forms..." style="width: 250px; display: inline-block;">
                </div>
                <div id="tableSearch">
                    <div class="table-responsive">
                        <table class="table table-hover align-items-center mb-0" style="font-size: 0.875rem;">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Form Name</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-none d-md-table-cell">Created At</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Used By Programs</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($forms) > 0): ?>
                                <?php foreach ($forms as $form): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm"><?php echo htmlspecialchars($form['form_name']); ?></h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <p class="text-sm font-weight-bold mb-0"><?php echo htmlspecialchars($form['created_at']); ?></p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0" id="programs-<?php echo $form['id']; ?>">Loading...</p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-1">
                                            <a href="#" class="btn btn-link text-white m-0 px-2" onclick="viewForm(<?php echo $form['id']; ?>)" data-toggle="tooltip" title="View Form">
                                                <i class="fa fa-eye text-info fa-lg"></i>
                                            </a>
                                            
                                            <a href="#" class="btn btn-link text-white m-0 px-2" data-toggle="tooltip" title="Copy Form" onclick="copyForm(<?php echo $form['id']; ?>)">
                                                <i class="fa fa-copy text-success fa-lg"></i>
                                            </a>
                                            
                                            <a href="#" class="btn btn-link text-white m-0 px-2" data-toggle="tooltip" title="Edit Form" onclick="checkFormUsage(<?php echo $form['id']; ?>, 'edit'); return false;">
                                                <i class="fa fa-edit text-warning fa-lg"></i>
                                            </a>
                                            
                                            <a href="#" class="btn btn-link text-white m-0 px-2" data-toggle="tooltip" title="Delete Form" onclick="confirmDeleteForm(<?php echo $form['id']; ?>)">
                                                <i class="fa fa-trash text-danger fa-lg"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <div id="form-fields-<?php echo $form['id']; ?>" style="display: none;">
                                    <?php echo $form['form_html']; ?>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">No forms available for your department.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    </div>
                </div>            
            </div>
        </div>
    </div>
</div>

<!-- Modal for form preview -->
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

<!-- Modal for showing programs using a form -->
<div class="modal fade" id="programsUsingFormModal" tabindex="-1" aria-labelledby="programsUsingFormModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="programsUsingFormModalLabel">Programs Using This Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="programs-list"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
?>


<script>
function checkFormUsage(formId, action) {
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
            // Update the programs column for this form
            const programsCell = document.getElementById(`programs-${formId}`);
            programsCell.textContent = data.programList;
            
            if (action === 'delete') {
                swal("Cannot delete form!", "This form is currently in use by one or more programs.", {
                    icon: "warning",
                });
            } else if (action === 'edit') {
                swal("Cannot edit form!", "This form is currently in use by one or more programs.", {
                    icon: "warning",
                });
            }
        } else {
            // Update the programs column to show "Not in use"
            const programsCell = document.getElementById(`programs-${formId}`);
            programsCell.textContent = "Not in use";
            
            if (action === 'delete') {
                // If not in use and action is delete, proceed with delete confirmation
                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this form!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        deleteForm(formId);
                    } else {
                        swal("Your form is safe!");
                    }
                });
            } else if (action === 'edit') {
                // If not in use and action is edit, proceed to edit page
                window.location.href = `edit_form_builder.php?id=${formId}`;
            }
        }
    })
    .catch(error => {
        swal("An error occurred while checking form usage.", {
            icon: "error",
        });
    });
}

function confirmDeleteForm(formId) {
    checkFormUsage(formId, 'delete');
}

function deleteForm(formId) {
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
}

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
    
    // Add this at the end of your script section
document.addEventListener('DOMContentLoaded', function() {
    // Get all form IDs and check their usage
    const forms = document.querySelectorAll('[id^="programs-"]');
    forms.forEach(form => {
        const formId = form.id.split('-')[1];
        checkFormUsage(formId);
    });
});
</script>