<?php 
include '../includes/header.php'; 
include 'index_logic.php';
$sqlSubmissions = "SELECT COUNT(*) as total_submissions FROM form_submissions";
$totalSubmissions = query($sqlSubmissions)->fetch_assoc()['total_submissions'];

$latestNews = query("SELECT 
    news.title AS title,
    news.content,
    news.image_url,
    news.created_at,
    departments.department_name
FROM 
    news
LEFT JOIN departments ON news.department_id = departments.id
WHERE 
    news.status = 'approved'
ORDER BY 
    news.created_at DESC 
LIMIT 2")->fetch_all(MYSQLI_ASSOC); // Fetches at least 2 records, change the limit if needed

?> 

<div class="row mb-4">
    <div class="col-12 col-md-3 px-1 mb-2">
        <div class="card card-body h-100 p-3 d-flex justify-content-between">
            <div class="row align-items-center">
                <div class="col-8">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Departments</p>
                    <h5 class="font-weight-bolder mb-0">
                        <?php echo $departmentCount;?>
                    </h5>
                </div>
                <div class="col-4 text-center">
                    <i class="fas fa-building text-primary fa-3x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-3 px-1 mb-2">
        <div class="card card-body h-100 p-3 d-flex justify-content-between">
            <div class="row align-items-center">
                <div class="col-8">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Programs</p>
                    <h5 class="font-weight-bolder mb-0">
                        <?php echo $programCount; ?>
                    </h5>
                </div>
                <div class="col-4 text-center">
                    <i class="fas fa-list text-info fa-3x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-3 px-1 mb-2">
        <div class="card card-body h-100 p-3 d-flex justify-content-between">
            <div class="row align-items-center">
                <div class="col-8">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Users</p>
                    <h5 class="font-weight-bolder mb-0">
                        <?php echo $userCount; ?>
                    </h5>
                </div>
                <div class="col-4 text-center">
                    <i class="fas fa-users text-success fa-3x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-3 px-1 mb-2">
        <div class="card card-body h-100 p-3 d-flex justify-content-between">
            <div class="row align-items-center">
                <div class="col-8">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Applications</p>
                    <h5 class="font-weight-bolder mb-0">
                        <?php echo htmlspecialchars($totalSubmissions); ?>
                    </h5>
                </div>
                <div class="col-4 text-center">
                    <i class="fas fa-file text-warning fa-3x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Resident Registrations Over Time (Area Chart) -->
    <div class="col-12 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header pb-0">
                Resident Registrations Over Time
            </div>
            <div class="card-body">
                <canvas id="registrationsChart" class="chart-canvas"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header pb-0">
                Programs with Most Applications
            </div>
            <div class="card-body">
                <canvas id="applicationsChart" class="chart-canvas"></canvas>
            </div>
            <div class="card-footer pt-0">
                
            </div>
        </div>
    </div>
</div>
<div class="row mb-4">
        <div class="col-md-12 mb-4"> 
            <div class="card h-100">
                <div class="card-header pb-0">
                    News Update
                </div>
                <div class="card-body">
                <?php foreach ($latestNews as $news): ?>
                    <h6 class="card-title mb-2 "><?php echo htmlspecialchars($news['title']); ?></h6>
                    <div class="row">
                      <div class="col-md-3">
                        <img src="<?php echo htmlspecialchars('../../../assets/img/uploads/' . $news['image_url']); ?>" class="img-fluid mb-2" style="height: 150px; width: 250px; object-fit: cover;" alt="News Image">
                      </div>
                      <div class="col-md-9">
                        <p class="card-text mb-2"><?php echo $news['content']; ?></p>
                      </div>
                    </div>
                    <p class="card-text text-muted text-sm">From <?php echo htmlspecialchars($news['department_name']); ?> on <?php echo date('m/d/Y', strtotime($news['created_at'])); ?></p>
                    <hr class="horizontal dark m-0">
                    <?php endforeach; ?>
                    
                </div>
            </div>
        </div>
    
</div>

<script>
fetch('registered_chart.php')
  .then(response => response.json())
  .then(data => {
    const ctx = document.getElementById('registrationsChart').getContext('2d');
    const registrationsChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['Jan', 'Feb', 'March', 'April', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'],
        datasets: [{
          label: 'Registered Users',
          data: data,  
          fill: true,
          borderColor: 'rgba(75, 192, 192, 1)',
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          tension: 0.1
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  })
  .catch(error => console.error('Error fetching data:', error));

const applicationsCtx = document.getElementById('applicationsChart').getContext('2d');
fetch('program_chart.php')
  .then(response => response.json())
  .then(data => {
    const applicationsChart = new Chart(applicationsCtx, {
      type: 'bar',
      data: {
        labels: data.labels,
        datasets: [{
          label: 'Total Applications', 
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
            display: true 
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
