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
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="userTableBody">
              <?php
              if (mysqli_num_rows($result) > 0) {
                  while($row = mysqli_fetch_assoc($result)) {
                      echo "<tr>";
                      echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                      echo '<td>
                              <a href="#" class="text-primary me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                <i class="fas fa-pencil-alt fa-lg"></i>
                              </a>
                              <a href="#" class="text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                <i class="fas fa-trash-alt fa-lg"></i>
                              </a>
                            </td>';
                      echo "</tr>";
                  }
              } else {
                  echo "<tr><td colspan='6'>No users found</td></tr>";
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

<script>
function searchBar() {
  var input, filter, table, tr, td, i, txtValue, matchFound;
  input = document.getElementById("input");
  filter = input.value.toUpperCase();
  table = document.getElementById("tableSearch");
  tr = table.getElementsByTagName("tr");
  matchFound = false; 

  for (i = 1; i < tr.length; i++) { 
    tr[i].style.display = "";
    var cells = tr[i].getElementsByTagName("td");
    for (var j = 0; j < cells.length - 1; j++) { 
      if (cells[j]) {
        txtValue = cells[j].textContent || cells[j].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          matchFound = true;
          break;
        }
      }
    }
    if (!matchFound) {
      tr[i].style.display = "none"; 
    }
    matchFound = false; 
  }

  var noMatchMessage = document.getElementById("noMatchMessage");
  noMatchMessage.style.display = (filter && !Array.from(tr).some(row => row.style.display !== "none")) ? "block" : "none";
}
</script>
