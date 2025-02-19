<?php
include 'db_config.php';

$data = json_decode(file_get_contents('php://input'), true);
$student_id = $data['student_id'];

if ($student_id) {
    $entry_time = date('Y-m-d H:i:s');
    $sql = "INSERT INTO attendance (student_id, entry_time) VALUES ('$student_id', '$entry_time')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid student ID']);
}
?>