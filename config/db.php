
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
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);
    }
    if (!$stmt->execute()) {
        die("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }
    if ($stmt->affected_rows > 0) {
        $stmt->close();
        $conn->close();
        return true;  
    }
    if (strpos(strtoupper($sql), 'SELECT') === 0) {
        $result = $stmt->get_result();
        $stmt->close();
        $conn->close();
        return $result;  
    }
    $stmt->close();
    $conn->close();
    return false;
}
?>
