<?php
$title = "Edit Form";
include '../includes/header.php'; 

$form_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$form_name = '';
$form_html = '';

if ($form_id > 0) {
    $sql = "SELECT form_name, form_html FROM forms WHERE id = ?";
    $form_data = query($sql, [$form_id])->fetch_assoc();

    if ($form_data) {
        $form_name = $form_data['form_name'];
        $form_html = $form_data['form_html'];
    } else {
        $_SESSION['warning_message'] = "Form not found";
    }
}
?>

<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12 col-md-6 mb-1">
                        <input type="text" id="form-name" class="form-control" placeholder="Form Name" value="<?php echo htmlspecialchars($form_name); ?>">
                    </div>
                    <div class="col-12 col-md-6 d-flex justify-content-end align-items-center mb-1">
                        <button class="btn bg-gradient-info btn-lg me-2 px-3 py-2 m-0" onclick="viewFormNew()" data-toggle="tooltip" title="View Form">
                            <i class="fa fa-eye"></i>
                        </button>
                        <button class="btn bg-gradient-success btn-lg me-2 px-3 py-2 m-0" onclick="confirmSaveForm(<?php echo $form_id; ?>)" data-toggle="tooltip" title="Save Form">
                            <i class="fa fa-save"></i>
                        </button>
                        <a href="form.php" class="btn back-btn bg-gradient-dark me-2 px-3 m-0" data-toggle="tooltip" title="Go Back to Program List">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-md-8 mb-3">
        <div class="card">
            <div class="card-header text-center pb-0">
                <h5>Form Preview</h5>
                <hr class="horizontal dark m-0">
            </div>
            <div class="card-body">
                <div class="form-builder">
                    <form id="generated-form">
                        <div id="form-fields" class="mb-3" style="min-height: 265px; padding: 20px; position: relative; background-color: #ffffff;">
                            <?php echo $form_html; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

   <div class="col-md-4">
        <div class="card"  >
            <div class="card-header text-center pb-0">
                <h5>Form Fields</h5>
                <hr class="horizontal dark m-0">
            </div>
            <div class="card-body" style="min-height: 330px;">
                <div class="mb-3">
                    <input type="text" id="field-label" class="form-control" placeholder="Enter field label">
                </div>
                <div class="mb-3">
                    <select id="field-type" class="form-select" onchange="toggleOptionsInput()">
                        <option value="">Select Field Type</option>
                        <option value="header">Text View</option>
                        <option value="text">Text Input</option>
                        <option value="textarea">Textarea</option>
                        <option value="email">Email</option>
                        <option value="number">Number</option>
                        <option value="radio">Radio Buttons</option>
                        <option value="select">Select Dropdown</option>
                        <option value="checkbox">Checkbox</option>
                        <option value="date">Date</option>
                        <option value="file">File Upload</option>
                        
                    </select>
                </div>
                <div class="mb-3" id="textview-input-container" style="display: none;">
                    <label for="field-textview-content">Enter text:</label>
                    <textarea id="field-textview-content" class="form-control" placeholder="Enter the text to display"></textarea>
                </div>

                <div class="mb-3" id="options-input-container" style="display: none;">
                    <input type="text" id="field-options" class="form-control" placeholder="Enter options, separated by commas">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="field-required">
                            <label class="form-check-label" for="field-required">Required</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 form-check" id="inline-option-container" style="display: none;">
                            <input type="checkbox" class="form-check-input" id="field-inline">
                            <label class="form-check-label" for="field-inline">Inline</label>
                        </div>
                    </div>
                </div>         
                <div class="mb-3 d-grid">
                    <button class="btn btn-light mb-3 shadow-none" style="border: 3px gray dashed;" onclick="addField()">Add Field</button>
                    <div class="col-md-12 d-flex justify-content-center">
                        <br>
                    </div>
                </div>
                <ul class="list-group" id="field-list">
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewFormModal" tabindex="-1" aria-labelledby="viewFormModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewFormModalLabel">Form Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal-form-preview-new"></div> 
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>


<script>
function confirmSaveForm(formId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to update this form? it will not restore once you confirm",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, update it!'
    }).then((result) => {
        if (result.isConfirmed) {
            saveForm(formId);
        }
    })
}
</script>

