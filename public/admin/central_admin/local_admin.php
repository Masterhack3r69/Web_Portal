<?php
$title = 'Local Admin';
include '../includes/header.php';

$sql = "SELECT * FROM admin WHERE admin_type = 'local'";
$local_admins = query($sql)->fetch_all(MYSQLI_ASSOC);

$department_sql = "SELECT * FROM departments";
$departments = query($department_sql)->fetch_all(MYSQLI_ASSOC); 

$department_map = []; 
foreach ($departments as $department) {
    $department_map[$department['id']] = $department['department_name'];
}

?>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h5>
          Local Admin
          <a href="create_local_admin.php" class="btn bg-gradient-primary float-end d-none d-lg-inline">Add Local Admin</a>
          <a href="create_local_admin.php" class="btn bg-gradient-primary float-end d-inline d-lg-none" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Add Local Admin">
            <i class="fa fa-plus"></i>
          </a>
        </h5>
      </div>
      <div class="card-body px-0 pt-0 pb-2">
        <table class="table table align-items-center text-center mb-0" style="font-size: 0.875rem;">
          <thead>
            <tr>
              <th class="d-none d-md-table-cell">#</th>
              <th>Name</th>
              <th class="d-none d-md-table-cell">Email</th> 
              <th class="d-none d-lg-table-cell">Department</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $count = 1; 

            if (!empty($local_admins)) {
                foreach ($local_admins as $row) {
                    $department_name = isset($department_map[$row['department_id']]) ? $department_map[$row['department_id']] : 'N/A';
                    $status_icon = ($row['status'] === 'active') ? '<i class="fa fa-check text-success" title="Active"></i>' : '<i class="fa fa-times text-danger" title="Inactive"></i>';

                    echo "<tr style='height: 30px;'>"
                        . "<td class='d-none d-md-table-cell'>{$count}</td>"
                        . "<td>{$row['username']}</td>"
                        . "<td class='d-none d-md-table-cell'>{$row['email']}</td> "
                        . "<td class='d-none d-lg-table-cell'>{$department_name}</td>"
                        . "<td>{$status_icon}</td>"
                        . "<td class='align-middle text-center'>"
                            . "<a href='edit_local_admin.php?id={$row['id']}' class='btn bg-gradient-success text-white px-3 py-2 me-2 my-2 d-none d-lg-inline' data-bs-toggle='tooltip' data-bs-placement='bottom' title='Edit local'>"
                                . "<i class='fa fa-edit'></i>"
                            . "</a>"
                            . "<a href='edit_local_admin.php?id={$row['id']}' class='btn bg-gradient-success text-white px-3 py-2 me-2 my-2 d-inline d-lg-none' data-bs-toggle='tooltip' data-bs-placement='bottom' title='Edit local'>"
                                . "<i class='fa fa-edit'></i>"
                            . "</a>"
                            . "<a href='#' "
                                . "class='btn bg-gradient-danger text-white m-0 px-3 py-2 d-none d-lg-inline' "
                                . "data-bs-toggle='tooltip' data-bs-placement='bottom' title='Delete Local Admin' "
                                . "onclick='confirmDeleteLocalAdmin({$row['id']})'>"
                                . "<i class='fa fa-user'></i>"
                            . "</a>"
                            . "<a href='#' "
                                . "class='btn bg-gradient-danger text-white m-0 px-3 py-2 d-inline d-lg-none' "
                                . "data-bs-toggle='tooltip' data-bs-placement='bottom' title='Delete Local Admin' "
                                . "onclick='confirmDeleteLocalAdmin({$row['id']})'>"
                                . "<i class='fa fa-user'></i>"
                            . "</a>"
                        . "</td>"
                    . "</tr>";
                    $count++; 
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>No local admins found.</td></tr>";
            }?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>

<script>
  function confirmDeleteLocalAdmin(adminId) {
    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this local admin!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            fetch('delete_local_admin.php?id=' + adminId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        swal("Poof! The local admin has been deleted!", {
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
                    swal("An error occurred: " + error, {
                        icon: "error",
                    });
                });
        } else {
            swal("Your local admin is safe!");
        }
    });
}

</script>