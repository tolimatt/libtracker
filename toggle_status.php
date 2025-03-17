<?php
include 'db_config.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['user_id'])) {
    $user_id = $data['user_id'];
    $result = $conn->query("SELECT status FROM user WHERE user_id = $user_id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $new_status = $row['status'] == 1 ? 0 : 1;
        if ($conn->query("UPDATE user SET status = $new_status WHERE user_id = $user_id") === TRUE) {
            echo json_encode(['success' => true, 'new_status' => $new_status]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error updating status: ' . $conn->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'User ID not provided']);
}
?>
