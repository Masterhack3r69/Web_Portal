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
  $sql = "SELECT COUNT(*) AS count FROM programs WHERE department_id IS NOT NULL";
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
