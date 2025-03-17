<?php
include 'db_config.php';
date_default_timezone_set('Asia/Manila');

$user_id = $_GET['user_id'];

// Fetch user details
$user_query = "SELECT student_id, first_name, last_name, department, year_level, phinmaed_email, contact_number FROM user WHERE user_id = ?";
$stmt_user = $conn->prepare($user_query);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$user_result = $stmt_user->get_result();
$user_data = [];
if ($user_result->num_rows > 0) {
    $user_data = $user_result->fetch_assoc();
} else {
    // Return an error response if the user_id does not exist
    $response = [
        'success' => false,
        'message' => 'User not found'
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
    $stmt_user->close();
    $conn->close();
    exit;
}

// Fetch attendance data
$attendance_query = "SELECT DATE(entry_time) as date, TIME(entry_time) as entry_time FROM attendance WHERE student_id = ?";
$stmt_attendance = $conn->prepare($attendance_query);
$stmt_attendance->bind_param("s", $user_data['student_id']);
$stmt_attendance->execute();
$attendance_result = $stmt_attendance->get_result();
$attendance_data = [];
if ($attendance_result->num_rows > 0) {
    while ($row = $attendance_result->fetch_assoc()) {
        $attendance_data[] = $row;
    }
}

// Fetch borrowed books data
$borrow_query = "SELECT books.title, borrow.borrowed_date, borrow.status 
                 FROM borrow 
                 INNER JOIN books ON borrow.book_code = books.book_code
                 WHERE borrow.student_id = ?";
$stmt_borrow = $conn->prepare($borrow_query);
$stmt_borrow->bind_param("s", $user_data['student_id']);
$stmt_borrow->execute();
$borrow_result = $stmt_borrow->get_result();
$borrow_data = [];
if ($borrow_result->num_rows > 0) {
    while ($row = $borrow_result->fetch_assoc()) {
        $borrow_data[] = $row;
    }
}

$response = [
    'success' => true,
    'user' => $user_data,
    'attendance' => $attendance_data,
    'borrow' => $borrow_data
];

header('Content-Type: application/json');
echo json_encode($response);

// Close the prepared statements and connection
$stmt_user->close();
$stmt_attendance->close();
$stmt_borrow->close();
$conn->close();
?>