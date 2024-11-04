  <?php
  $title = 'User Submissions';
  include '../includes/header.php';

  // Fetch all programs for the dropdown
  $programs = query("SELECT id, title FROM programs")->fetch_all(MYSQLI_ASSOC);

  // Fetch submissions grouped by program
  $sql = "SELECT fs.id, fs.submission_data, fs.status, p.title AS program_title, p.id AS program_id 
          FROM form_submissions fs 
          JOIN programs p ON fs.program_id = p.id 
          ORDER BY p.id, fs.id DESC";
  $submissions = query($sql)->fetch_all(MYSQLI_ASSOC);

  // Group submissions by program ID
  $grouped_submissions = [];
  foreach ($submissions as $submission) {
      $grouped_submissions[$submission['program_id']]['program_title'] = $submission['program_title'];
      $grouped_submissions[$submission['program_id']]['submissions'][] = $submission;
  }
  ?>

<div class="container">
    <h5>Select a Program to View Submissions</h5>
    <div class="form-group">
        <select id="programDropdown" class="form-control">
            <option value="">-- Select a Program --</option>
            <?php foreach ($programs as $program): ?>
                <option value="<?php echo htmlspecialchars($program['id']); ?>">
                    <?php echo htmlspecialchars($program['title']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <p id="selectPrompt" class="text-center text-muted">Please select a program from the dropdown above.</p>

    <!-- Tables for each program's submissions, hidden by default -->
    <?php foreach ($grouped_submissions as $program_id => $data): ?>
        <div id="programTable-<?php echo $program_id; ?>" class="program-table" style="display: none;">
            <div class="card my-4">
                <div class="card-header">
                    <h6><?php echo htmlspecialchars($data['program_title']); ?>
                        <a href="export_excel.php?program_id=<?php echo $program_id; ?>" class="text-success float-end border border-success px-3 py-1 rounded-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Export to Excel">
                            <i class="fa fa-file-excel fa-lg"></i>
                        </a>
                    </h6>
                </div>  
                
                <?php if (!empty($data['submissions'])): ?>
                    <div class="table-responsive px-0 pt-0 pb-2">
                        <table class="table text-center border-0 mb-0" style="font-size: 0.875rem;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <?php 
                                    // Display headers based on the first submission's keys
                                    $first_submission = json_decode($data['submissions'][0]['submission_data'], true);
                                    foreach ($first_submission as $key => $value) {
                                        echo "<th>" . htmlspecialchars($key) . "</th>";
                                    }
                                    ?>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $count = 1;
                                foreach ($data['submissions'] as $submission): 
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
                                    echo "<td>" . htmlspecialchars($currentStatus) . "</td>";
                                    echo "</tr>";
                                    $count++;
                                endforeach;
                                ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-center text-muted py-3">No submissions found for this program.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropdown = document.getElementById('programDropdown');
    const prompt = document.getElementById('selectPrompt');
    const tables = document.querySelectorAll('.program-table');

    dropdown.addEventListener('change', function() {
        tables.forEach(table => table.style.display = 'none');
        
        if (this.value) {
            prompt.style.display = 'none';
            const selectedTable = document.getElementById('programTable-' + this.value);
            if (selectedTable) selectedTable.style.display = 'block';
        } else {
            prompt.style.display = 'block'; 
        }
    });
});
</script>


