<?php
include 'db_config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $first_name = ucwords(strtolower(trim($_POST['first_name'])));
    $last_name = ucwords(strtolower(trim($_POST['last_name'])));
    $department = $_POST['department'];
    $year_level = $_POST['year_level'];
    $phinmaed_email = $_POST['phinmaed_email'];
    $contact_number = $_POST['contact_number'];

    $query = "UPDATE user SET first_name = '$first_name', last_name = '$last_name', department = '$department', year_level = '$year_level', phinmaed_email = '$phinmaed_email', contact_number = '$contact_number' WHERE user_id = $user_id";

    if ($conn->query($query) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating user: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
