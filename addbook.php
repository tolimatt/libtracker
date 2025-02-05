<?php include 'db_config.php'; ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["delete_book"])) {
        $book_id = $_POST["book_id"];
        $delete_sql = "DELETE FROM books WHERE book_id = $book_id";
        if ($conn->query($delete_sql) === TRUE) {
            echo "<p style='color: green;'>Book deleted successfully!</p>";
        } else {
            echo "<p style='color: red;'>Error deleting book: " . $conn->error . "</p>";
        }
    } else {
        // Check if form fields are set
        if (isset($_POST['title'], $_POST['author'], $_POST['isbn'], $_POST['copies_available'], $_POST['total_copies'], $_POST['department'])) {
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
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="add_book.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <title>Add Book</title>
    <link rel="stylesheet" href="add_book.css">
</head>
<body>
    <h1>Add Book</h1>
    <form class="addbookform" action="" method="POST">  <!-- Submits to the same file -->
        <input type="text" name="title" placeholder="Book Title" required>
        <input type="text" name="author" placeholder="Author" required>
        <input type="number" name="isbn" placeholder="Book Code" required> <!-- Fixed name -->
        <input type="number" name="copies_available" placeholder="Available Copies" required>
        <input type="number" name="total_copies" placeholder="Total Copies" required>
        <select name="department" required>
            <option value="" disabled selected>Select Department</option>
            <option value="CITE">CITE</option>
            <option value="CMA">CMA</option>
            <option value="CEA">CEA</option>
            <option value="CAS">CEA</option>
            <option value="CELA">CEA</option>
            <!-- Add more departments as needed -->
        </select>
        <button type="submit">Add Book</button>
    </form>

    <?php
    // Fetch and display books
    $result = $conn->query("SELECT * FROM books ORDER BY book_id DESC");

    if ($result->num_rows > 0) {
        echo "<h2>Book List</h2>";
        echo "<div class='table-container'>";
        echo "<table border='1'>";
        echo "<tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Book Code</th>
                <th>Available Copies</th>
                <th>Total Copies</th>
                <th>Department</th>
                <th>Action</th>
              </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['book_id']}</td>
                    <td>{$row['title']}</td>
                    <td>{$row['author']}</td>
                    <td>{$row['isbn']}</td>
                    <td>{$row['copies_available']}</td>
                    <td>{$row['total_copies']}</td>
                    <td>{$row['department']}</td>
                    <td>
                        <form class='deletebtn' method='POST' onsubmit='return confirmDelete()'>
                            <input type='hidden' name='book_id' value='{$row['book_id']}'>
                            <button type='submit' name='delete_book' class='delete-btn'>
                                <i class='bx bx-trash'></i>
                            </button>
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

<script>
function confirmDelete() {
    return confirm("Are you sure you want to delete this book?");
}
</script>
</body>
</html>
