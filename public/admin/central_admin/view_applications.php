<?php
$title = 'User Submissions';
include '../includes/header.php';

if (isset($_GET['program_id'])) {
    $program_id = $_GET['program_id'];
    
    // Fetch the program title and form_id associated with the program
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

// Initialize $first_submission for table headers
$first_submission = !empty($submissions) ? json_decode($submissions[0]['submission_data'], true) : null;
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5><?php echo $program_title; ?> <a href="export_excel.php?program_id=<?php echo $program_id; ?>" class="btn btn-primary float-end">Export to Excel</a>
                </h5>
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

<?php include '../includes/footer.php'; ?>

