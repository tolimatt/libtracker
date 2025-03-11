<?php
include 'db_config.php';
date_default_timezone_set('Asia/Manila');

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['student_id'])) {
    $student_id = $data['student_id'];
    $entry_time = date('F j, Y, g:i a');
    $day = date('l'); // Get the current day of the week

    // Fetch user details to get the department
    $user_query = "SELECT first_name, last_name, department, year_level FROM user WHERE student_id = '$student_id'";
    $user_result = $conn->query($user_query);
    if ($user_result->num_rows > 0) {
        $user = $user_result->fetch_assoc();
        $department = $user['department'];

        // Insert attendance record into the database
        $query = "INSERT INTO attendance (student_id, entry_time, day, department) VALUES ('$student_id', '$entry_time', '$day', '$department')";
        if ($conn->query($query) === TRUE) {
            echo json_encode([
                'success' => true,
                'student_id' => $student_id,
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'department' => $user['department'],
                'year_level' => $user['year_level'],
                'entry_time' => $entry_time
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error marking attendance: ' . $conn->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Student ID not provided']);
}
?>