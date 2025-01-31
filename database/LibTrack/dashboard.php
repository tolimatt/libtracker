<?php include 'db_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>BOOKS</h1>
    <a href="index.php">back</a>
    <a href="add_book.php">Add Book</a>

    <h2>Books</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>Department</th>
            <th>Action</th>
        </tr>
        <?php
        $sql = "SELECT * FROM books";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['book_id']}</td>
                    <td>{$row['title']}</td>
                    <td>{$row['author']}</td>
                    <td>{$row['department']}</td>

                    <td><a href='delete_book.php?id={$row['book_id']}'>Delete</a></td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No books found</td></tr>";
        }
        ?>
    </table>
</body>
</html>
