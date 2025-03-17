<?php
include 'db_config.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['staff_id'])) {
    $staff_id = $data['staff_id'];
    $result = $conn->query("SELECT status FROM staff WHERE staff_id = $staff_id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $new_status = $row['status'] == 1 ? 0 : 1;
        if ($conn->query("UPDATE staff SET status = $new_status WHERE staff_id = $staff_id") === TRUE) {
            echo json_encode(['success' => true, 'new_status' => $new_status]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error updating status: ' . $conn->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Staff not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Staff ID not provided']);
}
?>
