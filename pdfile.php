<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "libtrack";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    error_log("Database connection error: " . $conn->connect_error);
    die(json_encode(array("error" => "Connection failed: " . $conn->connect_error)));
}

// Get request parameters
$studentId = $_GET['studentId'];
$bookCode = $_GET['bookCode'];

// Check if the student already borrowed this book (excluding returned books)
$check = "SELECT * FROM borrow WHERE student_id = ? AND book_code = ? AND status != 'Returned'";
$stmt_check_borrowed = $conn->prepare($check);
$stmt_check_borrowed->bind_param("ss", $studentId, $bookCode);
$stmt_check_borrowed->execute();
$result_check = $stmt_check_borrowed->get_result();

if ($result_check->num_rows > 0) {
    echo json_encode(array("status" => "already_borrowed"));
    exit;
}

// Check if the student has reached the borrow limit (count only "Borrowed" books)
$borrowLimit = 3;
$check_borrowed_count = "SELECT COUNT(*) AS borrow_count FROM borrow WHERE student_id = ? AND status = 'Borrowed'";
$stmt_check_limit = $conn->prepare($check_borrowed_count);
$stmt_check_limit->bind_param("s", $studentId);
$stmt_check_limit->execute();
$result_limit = $stmt_check_limit->get_result()->fetch_assoc();

if ($result_limit['borrow_count'] >= $borrowLimit) {
    echo json_encode(array("status" => "limit"));
    exit;
}

// Default response if no borrowing issues
echo json_encode(array("status" => "not_borrowed"));

// Close statements
$stmt_check_borrowed->close();
$stmt_check_limit->close();
$conn->close();
?>