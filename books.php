<?php
header('Content-Type: application/json');
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "libtrack";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

$bookID = $_GET['book_id']; // Get the book ID from the request

$sql = "SELECT book_id, title, author, description, image_url, total_copies, copies_available, pdf_url FROM books WHERE book_id = $bookID";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $book = $result->fetch_assoc();
    echo json_encode($book);
} else {
    echo json_encode(['error' => 'Book not found']);
}

$conn->close();
?>