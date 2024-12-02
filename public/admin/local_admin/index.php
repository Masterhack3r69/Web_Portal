<?php include '../includes/header.php';

$departmentId = $_SESSION['department_id'];

$sqlPrograms = "SELECT COUNT(*) as total_programs FROM programs WHERE department_id = '$departmentId'";
$totalPrograms = query($sqlPrograms)->fetch_assoc()['total_programs'];

$sqlSubmissions = "SELECT COUNT(*) as total_submissions FROM form_submissions WHERE program_id IN (SELECT id FROM programs WHERE department_id = '$departmentId')";
$totalSubmissions = query($sqlSubmissions)->fetch_assoc()['total_submissions'];

$sqlNewSubmissions = "SELECT COUNT(*) as new_submissions FROM form_submissions WHERE program_id IN (SELECT id FROM programs WHERE department_id = '$departmentId') AND DATE(created_at) = CURDATE()";
$newSubmissions = query($sqlNewSubmissions)->fetch_assoc()['new_submissions'];

$sqlApprovedSubmissions = "SELECT COUNT(*) as approved_submissions FROM form_submissions WHERE program_id IN (SELECT id FROM programs WHERE department_id = '$departmentId') AND status = 'approved'";
$approvedSubmissions = query($sqlApprovedSubmissions)->fetch_assoc()['approved_submissions'];

$sqlProgramsChart = "SELECT programs.title, COUNT(form_submissions.id) as total_applications 
                     FROM programs 
                     INNER JOIN form_submissions ON programs.id = form_submissions.program_id AND programs.form_id = form_submissions.form_id
                     WHERE programs.department_id = '$departmentId' 
                     GROUP BY programs.id 
                     ORDER BY total_applications DESC 
                     LIMIT 5";
$programsChart = query($sqlProgramsChart)->fetch_all(MYSQLI_ASSOC);

?>

<div class="row">
  <div class="col-md-4 ">
    <div class="row">
      <div class="col-md-12 mb-3">
        <div class="card card-body p-2 d-flex justify-content-between">
          <div class="row align-items-center">
            <div class="col-8">
              <div class="card-body">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Programs</p>
                <h5 class="font-weight-bolder mb-0">
                    <?php echo htmlspecialchars($totalPrograms); ?>
                </h5>
              </div>
            </div>
            <div class="col-4 text-center">
              <i class="fas fa-tasks text-primary fa-3x"></i>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-md-12 mb-3">
        <div class="card card-body p-2 d-flex justify-content-between">
          <div class="row align-items-center">
            <div class="col-8">
              <div class="card-body">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Applications</p>
                <h5 class="font-weight-bolder mb-0">
                    <?php echo htmlspecialchars($totalSubmissions); ?>
                </h5>
              </div>
            </div>
            <div class="col-4 text-center">
              <i class="fas fa-file-lines text-info fa-3x"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12 mb-3">
        <div class="card card-body p-2 d-flex justify-content-between">
          <div class="row align-items-center">
            <div class="col-8">
              <div class="card-body">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">New Applications</p>
                <h5 class="font-weight-bolder mb-0">
                    <?php echo htmlspecialchars($newSubmissions); ?>
                </h5>
              </div>
            </div>
            <div class="col-4 text-center">
              <i class="fas fa-file text-warning fa-3x"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12 mb-3">
        <div class="card card-body p-2 d-flex justify-content-between">
          <div class="row align-items-center">
            <div class="col-8">
              <div class="card-body">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Approved Applications</p>
                <h5 class="font-weight-bolder mb-0">
                    <?php echo htmlspecialchars($approvedSubmissions); ?>
                </h5>
              </div>
            </div>
            <div class="col-4 text-center">
              <i class="fas fa-check-circle text-success fa-3x"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="card"> 
      <div class="card-body">
        <p class="text-sm text-capitalize font-weight-bold">Programs with most Applications</p>
        <canvas id="applicationsChart" style="height: 250px; width: 100%;"></canvas>
        <div class="mt-3" style="max-height: 150px; overflow-y: auto;">
          <ul class="list-group list-group-flush">
            <?php if (!empty($programsChart)): ?>
              <?php foreach ($programsChart as $program): ?>
              <li class="list-group-item d-flex justify-content-between">
                <span><?php echo htmlspecialchars($program['title']); ?></span>
                <span><?php echo htmlspecialchars($program['total_applications']); ?></span>
              </li>
              <?php endforeach; ?>
            <?php else: ?>
              <li class="list-group-item text-center">No Programs available</li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </div>
</div>

</div>

<script>
const applicationsCtx = document.getElementById('applicationsChart').getContext('2d');
fetch('program_chart.php')
  .then(response => response.json())
  .then(data => {
    const applicationsChart = new Chart(applicationsCtx, {
      type: 'bar',
      data: {
        labels: data.labels,
        datasets: [{
          label: '', 
          data: data.applications,
          backgroundColor: [
            'rgba(255, 99, 132, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(255, 206, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)'
          ],
          borderColor: [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        plugins: {
          legend: {
            display: false 
          }
        },
        scales: {
          x: {
            display: false
          },
        }
      }
    });
  })
  .catch(error => console.error('Error fetching data:', error));
</script>
<?php include '../includes/footer.php'; ?>
