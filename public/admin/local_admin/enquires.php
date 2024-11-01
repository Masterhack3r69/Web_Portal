<?php
$title = "News & Updates Management";
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
                        <i class="fa fa-plus me-2" aria-hidden="true"></i>Add News
                    </a>
                </h5>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table align-items-center table-flush text-center">
                    <thead class="thead-light">
                        <tr>
                            <th>Title</th>
                            <th>Small Description</th>
                            <th>Department</th>
                            <th>Program</th>
                            <th>Created By</th>
                            <th>Status</th>
                            <th>Actions</th>
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
                                <td class="py-0" style="font-size: 0.875rem;"><?php echo $news['small_description']; ?></td>
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
    <div class="modal-dialog  modal-lg  ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewNewsModalLabel">View News</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="newsImage" src="" alt="News Image" class="img-fluid mb-3">
                <h6 id="newsTitle"></h6>
                <p id="newsDescription"></p>
                <p><strong>Department:</strong> <span id="newsDepartment"></span></p>
                <p><strong>Program:</strong> <span id="newsProgram"></span></p>
                <p><strong>Created By:</strong> <span id="newsCreatedBy"></span></p>
                <p><strong>Status:</strong> <span id="newsStatusText"></span></p>
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
            const imagePath = data.news.image_url ? `../../../assets/img/uploads/${data.news.image_url}` : 'path/to/default/image.png'; // Use a 
            
            document.getElementById('newsImage').src = imagePath; 
            document.getElementById('newsTitle').innerText = data.news.title;
            document.getElementById('newsDescription').innerText = data.news.small_description;
            document.getElementById('newsDepartment').innerText = data.news.department_name;
            document.getElementById('newsProgram').innerText = data.news.program_title;
            document.getElementById('newsCreatedBy').innerText = data.news.created_by;
            document.getElementById('newsStatusText').innerText = data.news.status; 

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



