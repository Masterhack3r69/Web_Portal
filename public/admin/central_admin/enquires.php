<?php
$title = "News & Updates";
include '../includes/header.php';

// Fetch all news records
$newsRecords = query("SELECT n.id, n.title, n.small_description, d.department_name, 
    p.title AS program_title, a.username AS created_by, n.status
    FROM news n
    LEFT JOIN departments d ON n.department_id = d.id
    LEFT JOIN programs p ON n.program_id = p.id
    JOIN admin a ON n.created_by = a.id
    ORDER BY n.created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>


<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header pb-0">
                <h5>
                    News and Updates
                    <a href="create_enquires.php" class="btn bg-gradient-primary float-end d-none d-lg-inline">Add News</a>
                    <a href="create_enquires.php" class="btn bg-gradient-primary float-end d-inline d-lg-none" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Add News and Updates">
                        <i class="fa fa-plus"></i>
                    </a>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-items-center table-flush text-center">
                        <thead class="thead-light">
                            <tr>
                                <th>Title</th>
                                <th>Department</th>
                                <th>Program</th>
                                <th>Created By</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($newsRecords)): ?>
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
                                                <i class="fa fa-eye text-info fa-lg" aria-hidden="true"></i>
                                            </a>
                                            <a onclick="openUpdateStatusModal(<?php echo $news['id']; ?>, '<?php echo $news['status']; ?>')" class="btn btn-link px-1 py-2 m-0" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Update Status">
                                                <i class="fa fa-refresh text-primary fa-lg" aria-hidden="true"></i>
                                            </a>
                                            <a href="edit_enquires.php?id=<?php echo $news['id']; ?>" class="btn btn-link px-1 py-2 m-0" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit News">
                                                <i class="fa fa-pencil text-warning fa-lg" aria-hidden="true"></i>
                                            </a>
                                            <a onclick="confirmDeleteNews(<?php echo $news['id']; ?>)" class="btn btn-link px-1 py-2 m-0" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete News">
                                                <i class="fa fa-trash text-danger fa-lg" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="py-3 " style="font-size: 0.875rem;">No news and updates available.</td>
                                </tr>
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

<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStatusModalLabel">Update News Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateStatusForm">
                    <div class="mb-3">
                        <label for="newsStatus" class="form-label">Select Status</label>
                        <select id="newsStatus" class="form-select" required>
                            <option value="approved">Approved</option>
                            
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="mb-3" id="rejectionReasonDiv" style="display: none;">
                        <label for="rejectionReason" class="form-label">Rejection Reason</label>
                        <textarea id="rejectionReason" class="form-control" rows="3"></textarea>
                    </div>
                    <input type="hidden" id="newsId" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="updateStatusButton" class="btn btn-primary">Update Status</button>
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
    
    function openViewNewsModal(newsId) {
    fetch(`fetch_news.php?id=${newsId}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Set image path with fallback if image URL is missing
            const imagePath = data.news.image_url 
                ? `../../../assets/img/uploads/${data.news.image_url}` 
                : 'path/to/default/image.png'; // Default image
            
            // Set the image source
            document.getElementById('newsImage').src = imagePath; 
            
            // Set the title, description, and department with fallback
            document.getElementById('newsTitle').innerText = data.news.title;
            document.getElementById('newsDescription').innerText = data.news.small_description;

            // Check for department name and set fallback if NULL or empty
            const departmentName = data.news.department_name ? data.news.department_name : "No department associated";
            document.getElementById('newsDepartment').innerText = departmentName;

            // Check for program title and set fallback if NULL or empty
            const programTitle = data.news.program_title ? data.news.program_title : "No program associated";
            document.getElementById('newsProgram').innerText = programTitle;

            // Set other information
            document.getElementById('newsCreatedBy').innerText = data.news.created_by;
            document.getElementById('newsStatusText').innerText = data.news.status; 

            // Show the modal
            const modal = new bootstrap.Modal(document.getElementById('viewNewsModal'));
            modal.show();
        } else {
            // Handle failure response
            swal(data.message, {
                icon: "error",
            });
        }
    })
    .catch(error => {
        // Handle fetch error
        swal("An error occurred while fetching news data.", {
            icon: "error",
        });
    });
}



function openUpdateStatusModal(newsId, currentStatus) {
document.getElementById('newsId').value = newsId;
document.getElementById('newsStatus').value = currentStatus;

// Show or hide the rejection reason text area based on selected status
document.getElementById('newsStatus').onchange = function() {
    document.getElementById('rejectionReasonDiv').style.display = this.value === 'rejected' ? 'block' : 'none';
};

// Show the modal
const modal = new bootstrap.Modal(document.getElementById('updateStatusModal'));
modal.show();
}

document.getElementById('updateStatusButton').onclick = function() {
    const newsId = document.getElementById('newsId').value;
    const status = document.getElementById('newsStatus').value;
    const rejectionReason = document.getElementById('rejectionReason').value;

    const data = {
        id: newsId,
        status: status,
        rejection_reason: status === 'rejected' ? rejectionReason : null
    };

    fetch('update_news_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            swal("Success!", "News status updated successfully!", "success").then(() => {
                location.reload();
            });
        } else {
            swal("Error!", data.message, "error");
        }
    })
    .catch(error => {
        swal("An error occurred.", {
            icon: "error",
        });
    });
};

</script>


