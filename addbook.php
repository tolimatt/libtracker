<?php include 'db_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="add_book.css">
    <title>Add Book</title>
</head>
<body>
    <h1>Add Book</h1>
    <form action="" method="POST">  <!-- Submits to the same file -->
        <input type="text" name="title" placeholder="Book Title" required>
        <input type="text" name="author" placeholder="Author" required>
        <input type="text" name="isbn" placeholder="Book Code" required> <!-- Fixed name -->
        <input type="number" name="copies_available" placeholder="Available Copies" required>
        <input type="number" name="total_copies" placeholder="Total Copies" required>
        <input type="text" name="department" placeholder="Department" required>
        <br>
        <button type="submit">Add Book</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get form values
        $title = $_POST['title'];
        $author = $_POST['author'];
        $book_number = $_POST['isbn'];
        $copies_available = $_POST['copies_available'];
        $total_copies = $_POST['total_copies'];
        $department = $_POST['department'];

        // SQL query
        $sql = "INSERT INTO books (title, author, isbn, copies_available, total_copies, department) 
                VALUES ('$title', '$author', '$book_number', '$copies_available', '$total_copies', '$department')";

        // Execute query
        if ($conn->query($sql) === TRUE) {
            echo "<p style='color: green;'>Book added successfully!</p>";
        } else {
            echo "<p style='color: red;'>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }
    }

    // Fetch and display books
    $result = $conn->query("SELECT * FROM books ORDER BY book_id DESC");

    if ($result->num_rows > 0) {
        echo "<h2>Book List</h2>";
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Title</th><th>Author</th><th>Book Code</th><th>Available Copies</th><th>Total Copies</th><th>Department</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['book_id']}</td>
                    <td>{$row['title']}</td>
                    <td>{$row['author']}</td>
                    <td>{$row['isbn']}</td>
                    <td>{$row['copies_available']}</td>
                    <td>{$row['total_copies']}</td>
                    <td>{$row['department']}</td>
                    <td class='deletebtn'>
                        <form class='delete' method='POST' action='deletebook.php' onsubmit='return confirmDelete()'>
                            <input type='hidden' name='book_id' value='{$row['book_id']}'>
                            <button type='submit' class='delete-btn'><i class='bx bx-trash'></i></button>
                        </form>
                    </td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No books added yet.</p>";
    }

    $conn->close();
    ?>
</body>
</html>
