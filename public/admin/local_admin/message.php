<?php



$title = "Feedback"; 
include '../includes/header.php';

$query_update = "UPDATE messages SET status = 'read' WHERE status = 'unread'";
query($query_update); 

$department_id = $_SESSION['department_id']; 
$query_select = "SELECT email, message, feedback_type, created_at FROM messages WHERE department_id = ? ORDER BY created_at DESC";

$result = query($query_select, [$department_id]);

?>

<div class="row">
  <div class="col-md-12">
  <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
          <div class="card mb-3">
              <div class="card-body">
                  <h6 class="card-title">
                      <i class="fas fa-envelope" aria-hidden="true"></i> 
                      From: <?= htmlspecialchars($row['email']) ?>
                  </h6>
                  <p style="font-size: 0.8em; color: #888;"><strong>Feedback Type:</strong> <?= htmlspecialchars($row['feedback_type']) ?></p>
                      <div class="border p-3 pb-1">
                      <p class="card-text"><?= htmlspecialchars($row['message']) ?></p>
                      <small class="text-muted" style="font-size: 0.7em;"><?= time_ago($row['created_at']) ?></small>
                  </div>
              </div>
          </div>
      <?php endwhile; ?>
  <?php else: ?>
      <div class="card">
        <div class="card-body text-center">
          <p>No messages found.</p>
        </div>
  <?php endif; ?>
  </div>
</div>
    

<?php include '../includes/footer.php'; ?>

<?php
function time_ago($timestamp) {
    $time_ago = strtotime($timestamp);
    $current_time = time();
    $time_difference = $current_time - $time_ago;
    $seconds = $time_difference;

    // Time calculations
    $minutes = round($seconds / 60);
    $hours = round($seconds / 3600);
    $days = round($seconds / 86400);
    $weeks = round($seconds / 604800);
    $months = round($seconds / 2629440);
    $years = round($seconds / 31553280);

    // Return formatted time ago
    if ($seconds <= 60) {
        return "Just now";
    } else if ($minutes <= 60) {
        return ($minutes == 1) ? "1 minute ago" : "$minutes minutes ago";
    } else if ($hours <= 24) {
        return ($hours == 1) ? "1 hour ago" : "$hours hours ago";
    } else if ($days <= 7) {
        return ($days == 1) ? "yesterday" : "$days days ago";
    } else if ($weeks <= 4) {
        return ($weeks == 1) ? "1 week ago" : "$weeks weeks ago";
    } else if ($months <= 12) {
        return ($months == 1) ? "1 month ago" : "$months months ago";
    } else {
        return ($years == 1) ? "1 year ago" : "$years years ago";
    }
}
?>

