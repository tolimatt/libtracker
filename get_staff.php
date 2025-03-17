<?php
include 'db_config.php';

header('Content-Type: application/json');

if (isset($_GET['staff_id'])) {
    $staff_id = intval($_GET['staff_id']);

    $query = "SELECT staff_id, first_name, last_name, position, email, department FROM staff WHERE staff_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $staff_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $staff = $result->fetch_assoc();
        echo json_encode(['success' => true, 'staff' => $staff]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Staff not found']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

$conn->close();
?>