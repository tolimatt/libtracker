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

$data = json_decode(file_get_contents("php://input"), true);

$studentId = $data['studentId'];
$bookCode = $data['bookCode'];
$bookTitle = $data['bookTitle'];
$borrowDate = $data['borrowDate'];
$dueDate = $data['dueDate'];
$status = $data['status']; // "Borrowed", "Returned", etc.

// Start transaction
$conn->begin_transaction();

try {
    // Check if the student already has this book borrowed and NOT returned
    $check = "SELECT * FROM borrow WHERE student_id = ? AND book_code = ? AND status != 'Returned'";
    $stmt_check = $conn->prepare($check);
    $stmt_check->bind_param("ss", $studentId, $bookCode);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo json_encode(array("status" => "already_borrowed"));
        exit; // Exit after sending the response
    }

    // If the book is being "Borrowed", check the borrow limit (excluding returned books)
    if ($status === "Borrowed") {
        $borrowed = "Borrowed";
        $check_borrowed = "SELECT COUNT(*) AS borrow_count FROM borrow WHERE student_id = ? AND status = ?";
        $stmt_check_borrowed = $conn->prepare($check_borrowed);
        $stmt_check_borrowed->bind_param("ss", $studentId, $borrowed);
        $stmt_check_borrowed->execute();
        $result_borrowed = $stmt_check_borrowed->get_result();
        $row = $result_borrowed->fetch_assoc();

        if ($row['borrow_count'] >= 3) {
            echo json_encode(array("status" => "limit_reached"));
            exit;
        }
    }

    // Insert the new borrow record
    $insert_borrowed_book = "INSERT INTO borrow (student_id, book_code, title, borrowed_date, due_date, status) 
                             VALUES (?, ?, ?, ?, ?, ?)";
    $stmt1 = $conn->prepare($insert_borrowed_book);
    $stmt1->bind_param("ssssss", $studentId, $bookCode, $bookTitle, $borrowDate, $dueDate, $status);

    if (!$stmt1->execute()) {
        throw new Exception("Error inserting into 'borrow' table: " . $stmt1->error);
    }

    // If the book is actually being "Borrowed", decrement available copies
    if ($status === "Borrowed") {
        $decrement_available_books = "UPDATE books SET copies_available = copies_available - 1 WHERE book_code = ?";
        $stmt2 = $conn->prepare($decrement_available_books);
        $stmt2->bind_param("s", $bookCode);

        if (!$stmt2->execute()) {
            throw new Exception("Error updating 'books' table: " . $stmt2->error);
        }
    }

    // Commit transaction
    $conn->commit();

    echo json_encode(array("status" => "success"));

} catch (Exception $e) {
    $conn->rollback();
    error_log($e->getMessage());
    echo json_encode(array("error" => $e->getMessage()));
}

// Close the prepared statements and connection
$stmt_check->close();
if (isset($stmt_check_borrowed)) $stmt_check_borrowed->close();
$stmt1->close();
if (isset($stmt2)) $stmt2->close();
$conn->close();
?>