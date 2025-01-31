<?php
include 'db_config.php';

if (isset($_GET['book_id'])) {
    $id = $_GET['book_id'];

    $sql = "DELETE FROM books WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.php");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>
