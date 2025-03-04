<?php
include 'db_config.php';

if (isset($_GET['book_id'])) {
    $book_id = intval($_GET['book_id']); // Convert to integer for security
    $query = "SELECT * FROM books WHERE book_id = $book_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc()); // Convert result to JSON
    } else {
        echo json_encode(["error" => "Book not found"]);
    }
} else {
    echo json_encode(["error" => "Invalid request"]);
}
?>