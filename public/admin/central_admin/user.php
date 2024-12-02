<?php 
$title = "Users";
include '../includes/header.php';

$sql = "SELECT first_name, last_name, username, email, created_at FROM users";
$result = query($sql);
?>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header pb-0">
        <h5>Users</h5>
      </div>
      <div class="card-body">
        <div class="mb-3 d-flex justify-content-end">
          <input class="form-control p-1" type="text" id="input" onkeyup="searchBar()" placeholder="Search for names..." style="width: 250px; display: inline-block;">
        </div>
        <div class="table-responsive" id="tableSearch">
          <table class="table table-sm text-center" style="font-size: 0.80rem;">
            <thead class="thead-dark">
              <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Created At</th>
              </tr>
            </thead>
            <tbody id="userTableBody">
            <tbody id="userTableBody">
              <?php
              if (mysqli_num_rows($result) > 0) {
                  while($row = mysqli_fetch_assoc($result)) {
              ?>
                      <tr>
                        <td><?= $row['first_name'] ?></td>
                        <td><?= $row['last_name'] ?></td>
                        <td><?= $row['username'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td><?= $row['created_at'] ?></td>
                      </tr>
              <?php
                  }
              } else {
              ?>
                      <tr><td colspan='6'>No users found</td></tr>
              <?php
              }
              ?>
            </tbody>
          </table>
          <div id="noMatchMessage" style="display:none;" class="text-center text-danger">No matches found.</div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php 
include '../includes/footer.php';
?>
