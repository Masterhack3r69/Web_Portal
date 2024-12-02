<?php
$title = "News & Updates";
include '../includes/header.php';


$departmentId = $_SESSION['department_id']; 

$newsRecords = query("SELECT n.id, n.title, n.small_description, d.department_name, p.title AS program_title, a.username AS created_by, n.status
  FROM news n
  JOIN departments d ON n.department_id = d.id
  JOIN programs p ON n.program_id = p.id
  JOIN admin a ON n.created_by = a.id
  WHERE n.department_id = ?
  ORDER BY n.created_at DESC", [$departmentId])->fetch_all(MYSQLI_ASSOC);
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header pb-0">
                <h5>News and Updates
                    <a href="create_enquires.php" class="btn bg-gradient-primary float-end">
                        Add News
                    </a>
                </h5>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                 <table class="table table-hover align-items-center mb-0" style="font-size: 0.875rem;">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Title</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Department</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Program</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Created By</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($newsRecords)): ?>
                            <tr>
                                <td colspan="7">No news found for your department.</td>
                            </tr>
                        <?php else: ?>
                        <?php foreach ($newsRecords as $news): ?>
                            <tr>
                                <td class="py-0" style="font-size: 0.875rem;"><?php echo $news['title']; ?></td>
                                <td class="py-0" style="font-size: 0.875rem;"><?php echo $news['department_name']; ?></td>
                                <td class="py-0" style="font-size: 0.875rem;"><?php echo $news['program_title']; ?></td>
                                <td class="py-0" style="font-size: 0.875rem;"><?php echo $news['created_by']; ?></td>
                                <td class="py-0" style="font-size: 0.875rem;">
                                    <?php if ($news['status'] == 'approved'): ?>
                                        <span class="badge rounded-pill bg-gradient-success">Approved</span>
                                    <?php elseif ($news['status'] == 'rejected'): ?>
                                        <span class="badge rounded-pill bg-gradient-secondary">Rejected</span>
                                    <?php elseif ($news['status'] == 'pending'): ?>
                                        <span class="badge rounded-pill bg-gradient-warning">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-0">
                                    <a onclick="openViewNewsModal(<?php echo $news['id']; ?>)" class="btn btn-link px-1 py-2 m-0" data-bs-toggle="tooltip" data-bs-placement="bottom" title="View News">
                                        <i class="fa fa-eye text-info fa-lg " aria-hidden="true"></i>
                                    </a>
                                    <a href="edit_enquires.php?id=<?php echo $news['id']; ?>" class="btn btn-link px-1 py-2 m-0" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit News">
                                        <i class="fa fa-pencil text-warning fa-lg" aria-hidden="true"></i>
                                    </a>
                                    <a onclick="confirmDeleteNews(<?php echo $news['id']; ?>)" class="btn btn-link px-1 py-2 m-0" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete News">
                                      <i class="fa fa-trash text-danger fa-lg " aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                  </table>
              </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewNewsModal" tabindex="-1" aria-labelledby="viewNewsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewNewsModalLabel">View News</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <img id="newsImage" src="" alt="News Image" class="img-fluid rounded" style="max-height: 300px;">
                </div>
                <div class="mb-4">
                    <h4 id="newsTitle" class="text-primary mb-3"></h4>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-2"><strong><i class="fas fa-building me-2"></i>Department:</strong> <span id="newsDepartment"></span></p>
                            <p class="mb-2"><strong><i class="fas fa-project-diagram me-2"></i>Program:</strong> <span id="newsProgram"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong><i class="fas fa-user me-2"></i>Created By:</strong> <span id="newsCreatedBy"></span></p>
                            <p class="mb-2"><strong><i class="fas fa-info-circle me-2"></i>Status:</strong> <span id="newsStatusText"></span></p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-dark mb-2">Short Description:</h6>
                        <p id="newsDescription" class="text-muted"></p>
                    </div>
                    <div>
                        <h6 class="text-dark mb-2">Content:</h6>
                        <div id="newsContent" class="text-muted"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>


<script>
function confirmDeleteNews(newsId) {
    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this news record!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            fetch(`delete_enquires.php?id=${newsId}`, { method: 'GET' })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    swal("Poof! Your news record has been deleted!", {
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
                swal("An error occurred.", {
                    icon: "error",
                });
            });
        } else {
            swal("Your news record is safe!");
        }
    });
}
</script>

    <script>
function openViewNewsModal(newsId) {
    fetch(`fetch_news.php?id=${newsId}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const imagePath = data.news.image_url ? `../../../assets/img/uploads/${data.news.image_url}` : '../../../assets/img/default-news.jpg';
            
            document.getElementById('newsImage').src = imagePath;
            document.getElementById('newsTitle').innerText = data.news.title;
            document.getElementById('newsDescription').innerText = data.news.small_description;
            document.getElementById('newsContent').innerHTML = data.news.content;
            document.getElementById('newsDepartment').innerText = data.news.department_name;
            document.getElementById('newsProgram').innerText = data.news.program_title;
            document.getElementById('newsCreatedBy').innerText = data.news.created_by;
            
            // Add appropriate status badge
            const statusSpan = document.getElementById('newsStatusText');
            let statusClass = '';
            switch(data.news.status) {
                case 'approved':
                    statusClass = 'text-success';
                    break;
                case 'rejected':
                    statusClass = 'text-secondary';
                    break;
                case 'pending':
                    statusClass = 'text-warning';
                    break;
            }
            statusSpan.className = statusClass;
            statusSpan.innerText = data.news.status.charAt(0).toUpperCase() + data.news.status.slice(1);

            // Show the modal
            const modal = new bootstrap.Modal(document.getElementById('viewNewsModal'));
            modal.show();
        } else {
            swal(data.message, {
                icon: "error",
            });
        }
    })
    .catch(error => {
        swal("An error occurred while fetching news data.", {
            icon: "error",
        });
    });
}
</script>
    