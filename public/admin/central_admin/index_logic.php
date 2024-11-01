<?php
function getDepartmentCount() {
  $sql = "SELECT COUNT(*) AS count FROM departments";
  $result = query($sql);
  if ($result && $row = $result->fetch_assoc()) {
    return $row['count'];
  }
  return 0;  
}

$departmentCount = getDepartmentCount();

function getProgramCount() {
  $sql = "SELECT COUNT(*) AS count FROM programs";
  $result = query($sql);
  if ($result && $row = $result->fetch_assoc()) {
    return $row['count'];
  }
  return 0;
}

$programCount = getProgramCount();

function getUserCount() { 
  $sql = "SELECT COUNT(*) AS count FROM users";
  $result = query($sql);
  if ($result && $row = $result->fetch_assoc()) {
    return $row['count'];
  }
  return 0;
}

$userCount = getUserCount();
?>
