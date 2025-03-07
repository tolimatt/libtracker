<?php
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $result = $conn->query("SELECT status FROM user WHERE user_id = $user_id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $new_status = $row['status'] == 1 ? 0 : 1;
        $conn->query("UPDATE user SET status = $new_status WHERE user_id = $user_id");
        echo json_encode(['status' => $new_status]);
    } else {
        echo json_encode(['error' => 'User not found']);
    }
}
?>