<?php
$title = 'User Submissions';
include '../includes/header.php';

if (isset($_GET['program_id'])) {
    $program_id = $_GET['program_id'];
    
    $program_info_sql = "SELECT title, form_id FROM programs WHERE id = ?";
    $program_info_result = query($program_info_sql, [$program_id]);
    
    if ($program_info_result->num_rows > 0) {
        $program = $program_info_result->fetch_assoc();
        $program_title = htmlspecialchars($program['title']);
        $form_id = $program['form_id'];
        
        // Fetch submissions specific to this program and form
        $sql = "SELECT id, submission_data, status FROM form_submissions WHERE form_id = ? AND program_id = ?";
        $submissions = query($sql, [$form_id, $program_id])->fetch_all(MYSQLI_ASSOC);
    } else {
        echo "<p>Program not found.</p>";
        exit;
    }
} else {
    echo "<p>Invalid program ID.</p>";
    exit;
}

$first_submission = !empty($submissions) ? json_decode($submissions[0]['submission_data'], true) : null;
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5><?php echo $program_title; ?> <a href="export_excel.php?program_id=<?php echo $program_id; ?>" class="btn bg-gradient-success float-end">Export to Excel</a></h5>
            </div>
            <div class="table-responsive px-0 pt-0 pb-2">
                <table class="table text-center border-0 mb-0" style="font-size: 0.875rem;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <?php 
                            if ($first_submission) {
                                foreach ($first_submission as $key => $value) {
                                    echo "<th>" . htmlspecialchars($key) . "</th>";
                                }
                                echo "<th>Actions</th>"; 
                            } else {
                                echo "<th colspan='1'>No submissions found.</th>";
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
                                foreach ($submission_data as $value) {
                                    echo "<td>" . htmlspecialchars($value) . "</td>";
                                }
                                
                                echo "<td>";
                                echo "<a href='#' class='text-primary' data-toggle='modal' data-target='#statusUpdateModal' data-id='{$submission['id']}' data-status='{$currentStatus}' data-toggle='tooltip' title='Update Status'><i class='fas fa-pencil-alt fa-lg'></i></a>";
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

<!-- Status Update Modal -->
<div class="modal fade" id="statusUpdateModal" tabindex="-1" role="dialog" aria-labelledby="statusUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusUpdateModalLabel">Update Submission Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
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
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<script>
$(document).ready(function() {
    $('#statusUpdateModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); 
        var submissionId = button.data('id'); 
        var currentStatus = button.data('status');
        
        var modal = $(this);
        modal.find('#submissionId').val(submissionId);
        modal.find('#submissionStatus').val(currentStatus);
    });

    $('#updateStatusForm').on('submit', function(e) {
        e.preventDefault(); 
        
        $.ajax({
            type: 'POST',
            url: 'update_status_form.php', 
            data: $(this).serialize(),
            success: function(response) {
                $('#statusUpdateModal').modal('hide');
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
});
</script>
