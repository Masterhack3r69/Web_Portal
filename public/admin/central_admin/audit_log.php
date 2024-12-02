<?php
$title = "Audit Logs";
include '../includes/header.php';

// Fetch audit logs
$sql = "SELECT a.username, al.type, al.action, al.description, al.created_at
        FROM audit_logs al
        INNER JOIN admin a ON al.admin_id = a.id
        ORDER BY al.created_at DESC";
$result = query($sql);
?>


<div class="row">
    <div class="col-md-12">
        <div class="card">
        <div class="card-header">
            <h5>Audit Logs</h5>
                <a href="#" class="text-danger float-end my-1" onclick="confirmDeleteLog()"><i class="fas fa-trash-alt fa-lg"></i></a>
                <input class="form-control form-control-sm float-end me-3" type="text" id="auditLogSearch" onkeyup="filterAuditLogs()" placeholder="Search audit logs..." style="width: 250px; display: inline-block;">
            </div>
        <div class="card-body">
            <table class="table table-sm table-borderless">
                <thead>
                    <tr class="text-center text-sm">
                        <th scope="col">Admin Username</th>
                        <th scope="col">Type</th>
                        <th scope="col">Action</th>
                        <th scope="col">Description</th>
                        <th scope="col">Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr class="text-center text-sm">
                                <td><?= htmlspecialchars($row['username']) ?></td>
                                <td><?= htmlspecialchars($row['type']) ?></td>
                                <td><?= htmlspecialchars($row['action']) ?></td>
                                <td><?= htmlspecialchars($row['description']) ?></td>
                                <td><?= htmlspecialchars($row['created_at']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr class="text-center text-sm">
                            <td colspan="5">No audit logs found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<script>
function filterAuditLogs() {
    var input, filter, table, tr, td, i, j, txtValue;
    input = document.getElementById("auditLogSearch");
    filter = input.value.toLowerCase();
    table = document.querySelector(".card-body table tbody");
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        tr[i].style.display = "none";
        td = tr[i].getElementsByTagName("td");
        for (j = 0; j < td.length; j++) {
            if (td[j]) {
                txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toLowerCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                    break;
                }
            }
        }
    }
}
</script>

<script>
function confirmDeleteLog() {
    swal({
        title: "Are you sure?",
        text: "This action will permanently delete all log entries.",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            // Make an AJAX request to delete the audit logs
            fetch('delete_log.php', {
                method: 'POST',
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    swal("Poof! The log entries have been deleted!", {
                        icon: "success",
                    }).then(() => {
                        location.reload(); // Reload the page after deletion
                    });
                } else {
                    swal("Error: " + data.message, {
                        icon: "error",
                    });
                }
            })
            .catch(error => {
                swal("An error occurred while trying to delete the logs.", {
                    icon: "error",
                });
            });
        } else {
            swal("The log entries are safe!");
        }
    });
}

</script>