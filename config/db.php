
<?php
require 'config.php';

function connect() {
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function query($sql, $params = []) {
    $conn = connect(); 
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

    if ($params) {
        $types = '';
        foreach ($params as $param) {
            $types .= is_int($param) ? 'i' : 's'; 
        }
        $stmt->bind_param($types, ...$params);
    }

    if (!$stmt->execute()) {
        die("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }

    if (strpos(strtoupper($sql), 'SELECT') === 0) {
        $result = $stmt->get_result();
        $stmt->close();
        $conn->close();
        return $result; 
    }

    if ($stmt->affected_rows > 0) {
        $stmt->close();
        $conn->close();
        return true;
    }

    $stmt->close();
    $conn->close();
    return false; 
}

function audit_log($type, $action, $description) {
    if (!isset($_SESSION['user_id'])) {
        error_log("User is not logged in. Cannot log audit event.");
        return;
    }
    
    $admin_id = $_SESSION['user_id'];
    
    $sql = "INSERT INTO audit_logs (type, action, description, admin_id, created_at) VALUES (?, ?, ?, ?, NOW())";
    query($sql, [$type, $action, $description, $admin_id]);
}

?>
