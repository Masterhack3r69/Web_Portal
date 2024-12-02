<?php
$title = 'User Submissions';
include '../includes/header.php';
?>
<?php
if (isset($_GET['program_id'])) {
    $program_id = $_GET['program_id'];
    
    // Modified query to include department information
    $program_info_sql = "SELECT p.title, p.form_id, p.department_id FROM programs p WHERE p.id = ?";
    $program_info_result = query($program_info_sql, [$program_id]);
    
    if ($program_info_result->num_rows > 0) {
        $program = $program_info_result->fetch_assoc();
        $program_title = htmlspecialchars($program['title']);
        $form_id = $program['form_id'];
        $department_id = $program['department_id'];
        
        // Base query
        $sql = "SELECT id, submission_data, status, created_at FROM form_submissions WHERE form_id = ? AND program_id = ?";
        
        // Prepare parameters array
        $params = [$form_id, $program_id];
        
        $submissions = query($sql, $params)->fetch_all(MYSQLI_ASSOC);
    } else {
        echo "<p>Program not found.</p>";
        exit;
    }
} else {
    echo "<p>Invalid program ID.</p>";
    exit;
}

// Function to truncate text
function truncateText($text, $length = 50) {
    if (strlen($text) > $length) {
        return substr($text, 0, $length) . '...';
    }
    return $text;
}

$first_submission = !empty($submissions) ? json_decode($submissions[0]['submission_data'], true) : null;

?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header pb-0">
                <h6 class="text-info"><?php echo $program_title; ?> 
                    <button onclick="resetFilters()" class="btn bg-gradient-info float-end me-2 py-2 px-3" aria-label="Reset all filters" data-bs-toggle="tooltip" data-bs-placement="top" title="Reset all filters">Reset Filter
                    </button>
                    <button class="btn bg-gradient-danger float-end me-2 py-2 px-3" onclick="confirmDeleteSubmissions('<?php echo $form_id; ?>', '<?php echo $program_id; ?>')">Delete All</button>
                    <button onclick="exportFilteredData()" class="btn bg-gradient-success float-end me-2 py-2 px-3">Export</button>
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-2 mb-2">
                    <!-- Search Filter -->
                    <div class="col-md-4">
                        <label class="form-label small mb-1">Search All Fields</label>
                        <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search..." onkeyup="filterSubmissions()">
                    </div>

                    <!-- Date Filter -->
                    <div class="col-md-4">
                        <div class="row g-1">
                            <div class="col-md-6">
                                <label class="form-label small mb-1">From</label>
                                <input type="date" id="startDate" class="form-control form-control-sm" onchange="filterSubmissions()" value="<?php echo isset($_GET['start_date']) ? htmlspecialchars($_GET['start_date']) : ''; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small mb-1">To</label>
                                <input type="date" id="endDate" class="form-control form-control-sm" onchange="filterSubmissions()" value="<?php echo isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : ''; ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="col-md-2">
                        <label class="form-label small mb-1">Status</label>
                        <select id="statusSelect" class="form-control form-control-sm" onchange="filterSubmissions()">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>

                    <!-- Age Filter -->
                    <div class="col-md-2">
                        <label class="form-label small mb-1">Age Range</label>
                        <select id="ageSelect" class="form-control form-control-sm" onchange="filterSubmissions()">
                            <option value="">All Ages</option>
                            <option value="18-25">18-25</option>
                            <option value="26-35">26-35</option>
                            <option value="36-45">36-45</option>
                            <option value="46+">46+</option>
                        </select>
                    </div>

                
                </div>
                <div class="table-responsive px-0 pt-0 pb-2">
                    <table class="table text-center border-0 mb-0" style="font-size: 0.875rem;" id="submissionsTable">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">#</th>
                                <?php 
                                if ($first_submission) {
                                    // Create a list of headers excluding the uploaded_files
                                    foreach ($first_submission as $key => $value) {
                                        if ($key !== 'uploaded_files' && $key !== 'municipality' && $key !== 'barangay') {
                                            echo '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">' . htmlspecialchars($key) . '</th>';
                                        }
                                    }
                                    // Add a single column for uploaded files
                                    echo '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Uploaded Files</th>';
                                    echo '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date Applied</th>';
                                    echo '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>';
                                    echo '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Actions</th>'; 
                                } else {
                                    echo '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">No submissions found.</th>';
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if (!empty($submissions)) {
                                $count = 1;
                                foreach ($submissions as $submission) {
                                    $submission_data = json_decode($submission['submission_data'], true);
                                    $currentStatus = htmlspecialchars($submission['status']);
                                     
                                    $rowClass = '';
                                    if ($currentStatus === 'pending') {
                                        $rowClass = 'table-warning'; 
                                    } elseif ($currentStatus === 'approved') {
                                        $rowClass = 'table-success'; 
                                    } elseif ($currentStatus === 'rejected') {
                                        $rowClass = 'table-secondary'; 
                                    }

                                    echo "<tr class='{$rowClass}'><td>{$count}</td>";
                                    
                                    // Display regular fields first
                                    foreach ($submission_data as $key => $value) {
                                        if ($key !== 'uploaded_files' && $key !== 'municipality' && $key !== 'barangay') {
                                            echo "<td>" . htmlspecialchars(truncateText($value)) . "</td>";
                                        }
                                    }
                                    
                                    // Display all files in a single column
                                    echo "<td>";
                                    if (isset($submission_data['uploaded_files']) && is_array($submission_data['uploaded_files'])) {
                                        echo "<div class='d-flex gap-2 justify-content-center align-items-center '>";
                                        foreach ($submission_data['uploaded_files'] as $fileKey => $fileName) {
                                            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                            $isImage = in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif']);
                                            $filePath = "../../../assets/img/uploads/applications/" . htmlspecialchars($fileName);
                                            
                                            if ($isImage) {
                                                echo "<a href='" . $filePath . "' class='image-popup btn btn-link p-1 mb-0' title='" . htmlspecialchars($fileKey) . "'>" .
                                                     "<i class='fas fa-image text-info'></i> " . htmlspecialchars($fileKey) . "</a>";
                                            } else {
                                                echo "<a href='" . $filePath . "' target='_blank' class='btn btn-link p-1 mb-0' title='" . htmlspecialchars($fileKey) . "'>" .
                                                     "<i class='fas fa-file text-info'></i> " . htmlspecialchars($fileKey) . "</a>";
                                            }
                                        }
                                        echo "</div>";
                                    }
                                    echo "</td>";
                                     
                                    // Display date applied
                                    echo "<td>" . date('M d, Y', strtotime($submission['created_at'])) . "</td>";
                                    
                                    // Display status
                                    $statusClass = '';
                                    switch($currentStatus) {
                                        case 'pending':
                                            $statusClass = 'text-warning';
                                            break;
                                        case 'approved':
                                            $statusClass = 'text-success';
                                            break;
                                        case 'rejected':
                                            $statusClass = 'text-secondary';
                                            break;
                                    }
                                    echo "<td><span class='{$statusClass} text-capitalize'>{$currentStatus}</span></td>";
                                     
                                    echo "<td>";
                                    echo "<div class='d-flex gap-2 justify-content-center align-items-center'>";
                                    // View Details button - only show for department ID 136 (Remedey Aksyon Center)
                                    if ($department_id == '136') {
                                        echo "<a href='view_application_details.php?id={$submission['id']}' class='btn btn-link text-primary p-1 mb-0' title='View Details'>";
                                        echo "<i class='fas fa-eye'></i></a>";
                                    }
                                    // Update Status button
                                    echo "<a href='#' class='btn btn-link text-info p-1 mb-0' data-bs-toggle='modal' data-bs-target='#statusUpdateModal' ";
                                    echo "data-id='{$submission['id']}' data-status='{$currentStatus}' title='Update Status'>";
                                    echo "<i class='fas fa-pencil-alt'></i></a>";
                                    echo "</div>";
                                    echo "</td>";
                                    echo "</tr>";
                                    $count++;
                                }
                            } else {
                                echo "<tr><td colspan='1' class='text-center'>No submissions found.</td></tr>";
                            }
                            ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusUpdateModal" tabindex="-1" aria-labelledby="statusUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusUpdateModalLabel">Update Submission Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateStatusForm">
                    <input type="hidden" name="submission_id" id="submissionId" value="">
                    <div class="form-group">
                        <label for="submissionStatus">Status</label>
                        <select class="form-control" id="submissionStatus" name="status">
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Update Status</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<script>
$(document).ready(function() {
    const statusModal = document.getElementById('statusUpdateModal');
    if (statusModal) {
        statusModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const submissionId = button.getAttribute('data-id');
            const currentStatus = button.getAttribute('data-status');
            
            this.querySelector('#submissionId').value = submissionId;
            this.querySelector('#submissionStatus').value = currentStatus;
        });
    }
    
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    $('#updateStatusForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            type: 'POST',
            url: 'update_application_status.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('statusUpdateModal'));
                modal.hide();
                
                if (response.success) {
                    // Show success message
                    swal({
                        title: "Success!",
                        text: response.message || "Status has been updated successfully",
                        icon: "success",
                    }).then(() => {
                        // If there's a warning, show it after the success message
                        if (response.warning) {
                            swal({
                                title: "Notice",
                                text: response.warning,
                                icon: "info"
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            location.reload();
                        }
                    });
                } else {
                    swal("Error", response.message || "Failed to update status", "error");
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                swal("Error", "An error occurred while updating the status", "error");
            }
        });
    });
});
</script>

<script>
    function confirmDeleteSubmissions(formId, programId) {
        swal({
            title: "Are you sure?",
            text: "Once deleted, all submissions for this form and program will be permanently removed!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                fetch(`delete_submissions.php?form_id=${formId}&program_id=${programId}`, { method: 'GET' })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        swal("All submissions have been deleted!", {
                            icon: "success",
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        swal(data.message, {
                            icon: "error",
                        });
                    }
                })
                .catch(error => {
                    swal("An error occurred while deleting submissions.", {
                        icon: "error",
                    });
                });
            } else {
                swal("Your submissions are safe!");
            }
        });
    }
</script>

<script>
$(document).ready(function() {
    $('.image-popup').magnificPopup({
        type: 'image',
        gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0,1]
        },
        image: {
            tError: '<a href="%url%">The image</a> could not be loaded.',
            titleSrc: 'title'
        },
        zoom: {
            enabled: true,
            duration: 300,
            easing: 'ease-in-out'
        },
        callbacks: {
            beforeOpen: function() {
                this.st.image.markup = this.st.image.markup;
            }
        }
    });
});
</script>

<script>
function getColumnIndex(columnName) {
    const headers = document.querySelectorAll('#submissionsTable thead th');
    for (let i = 0; i <headers.length; i++) {
        if (headers[i].textContent.toLowerCase().includes(columnName.toLowerCase())) {
            return i;
        }
    }
    return -1;
}

function filterSubmissions() {
    const table = document.getElementById('submissionsTable');
    const rows = Array.from(table.getElementsByTagName('tr')).slice(1); // Skip header row
    
    // Get all filter values
    const searchText = document.getElementById('searchInput').value.toLowerCase();
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const status = document.getElementById('statusSelect').value.toLowerCase();
    const ageFilter = document.getElementById('ageSelect').value;

    // Apply all filters
    rows.forEach(row => {
        const cells = Array.from(row.getElementsByTagName('td'));
        let showRow = true;
        
        // Search filter
        if (searchText) {
            const rowText = cells.map(cell => cell.textContent.toLowerCase()).join(' ');
            if (!rowText.includes(searchText)) {
                showRow = false;
            }
        }
        
        // Date filter
        if (showRow && (startDate || endDate)) {
            const dateCell = cells[cells.length - 3]; // Date is third to last column
            if (dateCell) {
                const rowDate = new Date(dateCell.textContent);
                
                if (startDate && new Date(startDate) > rowDate) {
                    showRow = false;
                }
                if (endDate && new Date(endDate) < rowDate) {
                    showRow = false;
                }
            }
        }
        
        // Status filter
        if (showRow && status) {
            const statusCell = cells[cells.length - 2]; // Status is second to last column
            if (statusCell) {
                const rowStatus = statusCell.querySelector('span').textContent.toLowerCase().trim();
                if (rowStatus !== status) {
                    showRow = false;
                }
            }
        }
        
        // Age filter
        if (showRow && ageFilter) {
            const ageIndex = getColumnIndex('age');
            if (ageIndex !== -1) {
                const age = parseInt(cells[ageIndex].textContent);
                const [min, max] = ageFilter.split('-').map(num => num.replace('+', ''));
                if (max) {
                    if (!(age >= parseInt(min) && age <= parseInt(max))) {
                        showRow = false;
                    }
                } else {
                    if (!(age >= parseInt(min))) {
                        showRow = false;
                    }
                }
            }
        }
        
        row.style.display = showRow ? '' : 'none';
    });
}

function resetFilters() {
    // Clear all filters
    document.getElementById('searchInput').value = '';
    document.getElementById('startDate').value = '';
    document.getElementById('endDate').value = '';
    document.getElementById('statusSelect').value = '';
    document.getElementById('ageSelect').value = '';
    
    // Show all rows
    const table = document.getElementById('submissionsTable');
    const rows = table.getElementsByTagName('tr');
    for (let i = 1; i < rows.length; i++) { // Start from 1 to skip header
        rows[i].style.display = '';
    }
}

function exportFilteredData() {
    // Get all filter values
    const searchText = document.getElementById('searchInput').value;
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const status = document.getElementById('statusSelect').value;
    const ageFilter = document.getElementById('ageSelect').value;

    // Get program_id from URL
    const urlParams = new URLSearchParams(window.location.search);
    const programId = urlParams.get('program_id');

    // Build query string
    const params = new URLSearchParams();
    params.set('program_id', programId);
    params.set('search', searchText);
    params.set('start_date', startDate);
    params.set('end_date', endDate);
    params.set('status', status);
    params.set('age', ageFilter);

    // Redirect to export with filters
    window.location.href = `export_excel.php?${params.toString()}`;
}
</script>
