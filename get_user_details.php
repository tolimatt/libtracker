<?php
include 'db_config.php';
date_default_timezone_set('Asia/Manila');

$user_id = $_GET['user_id'];

// Fetch user details
$user_query = "SELECT student_id, first_name, last_name, department, year_level, phinmaed_email, contact_number FROM user WHERE user_id = $user_id";
$user_result = $conn->query($user_query);
$user_data = [];
if ($user_result->num_rows > 0) {
    $user_data = $user_result->fetch_assoc();
}

// Fetch attendance data
$attendance_query = "SELECT DATE(entry_time) as date, TIME(entry_time) as entry_time FROM attendance WHERE student_id = '{$user_data['student_id']}'";
$attendance_result = $conn->query($attendance_query);
$attendance_data = [];
if ($attendance_result->num_rows > 0) {
    while ($row = $attendance_result->fetch_assoc()) {
        $attendance_data[] = $row;
    }
}

// Fetch borrowed books data
$borrowed_books_query = "SELECT books.title, borrowed_books.borrowed_date, borrowed_books.return_date, borrowed_books.return_status as status 
                         FROM borrowed_books 
                         INNER JOIN books ON borrowed_books.book_id = books.book_id 
                         WHERE borrowed_books.user_id = $user_id";
$borrowed_books_result = $conn->query($borrowed_books_query);
$borrowed_books_data = [];
if ($borrowed_books_result->num_rows > 0) {
    while ($row = $borrowed_books_result->fetch_assoc()) {
        $borrowed_books_data[] = $row;
    }
}

$response = [
    'user' => $user_data,
    'attendance' => $attendance_data,
    'borrowed_books' => $borrowed_books_data
];

header('Content-Type: application/json');
echo json_encode($response);
?>