<?php include 'db_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="addbook.css">
    <title>Add Book</title>
</head>
<body>
    <h1>Add Book</h1>
    <form action="add_book.php" method="POST">
        <input type="text" name="title" placeholder="Book Title" required>
        <input type="text" name="author" placeholder="Author" required>
        <input type="text" name="book number" placeholder="Book code" required>
        <input type="text" name="copies" placeholder="Copies" required>
        <input type="text" name="department" placeholder="Department" required>
        <button type="submit">Add Book</button>
    </form>
    
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $author = $_POST['author'];
        $department=$_POST['department'];
        $sql = "INSERT INTO books (title, author,department) VALUES ('$title', '$author','$department')";
        if ($conn->query($sql) === TRUE) {
            echo "Book added successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    ?>
</body>
</html>