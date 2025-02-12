<?php include 'db_config.php'; ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["delete_book"])) {
        $book_id = $_POST["book_id"];
        $delete_sql = "DELETE FROM books WHERE book_id = $book_id";
        if ($conn->query($delete_sql) === TRUE) {
            echo "<script>alert('Book deleted successfully!');</script>";
        } else {
            echo "<script>alert('Error deleting book: " . $conn->error . "');</script>";
        }
    } else {
        if (isset($_POST['title'], $_POST['author'], $_POST['isbn'], $_POST['copies_available'], $_POST['total_copies'], $_POST['department'])) {
            $title = $_POST['title'];
            $author = $_POST['author'];
            $book_number = $_POST['isbn'];
            $copies_available = $_POST['copies_available'];
            $total_copies = $_POST['total_copies'];
            $department = $_POST['department'];

            $sql = "INSERT INTO books (title, author, isbn, copies_available, total_copies, department) 
                    VALUES ('$title', '$author', '$book_number', '$copies_available', '$total_copies', '$department')";

            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Book added successfully!');</script>";
            } else {
                echo "<script>alert('Error: " . $conn->error . "');</script>";
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
    <title>User Management</title>
</head>
<body>
<div class="container">
    <div class="search-sort">
        <h1>Users</h1>
        <input type="text" id="search" placeholder="Search...">
        <button class="filter-btn"><i class='bx bx-filter-alt'></i> Filter</button>
        <button class="sort-btn"><i class='bx bx-sort'></i> Sort</button>
    </div>
    
   

    <div id="addBookForm" class="addbookform-container">
        <form action="" method="POST" class="modal-content">
            <input type="text" name="title" placeholder="Book Title" required>
            <input type="text" name="author" placeholder="Author" required>
            <input type="number" name="isbn" placeholder="Book Code" required>
            <input type="number" name="copies_available" placeholder="Available Copies" required>
            <input type="number" name="total_copies" placeholder="Total Copies" required>
            <select name="department" required>
                <option value="" disabled selected>Select Department</option>
                <option value="CITE">CITE</option>
                <option value="CMA">CMA</option>
                <option value="CEA">CEA</option>
                <option value="CAS">CAS</option>
                <option value="CELA">CELA</option>
            </select>
            <button type="submit">Add Book</button>
            <button type="button" id="closeFormButton">Cancel</button>
        </form>
    </div>
    <div class="main-content">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Book Code</th>
                    <th>Available Copies</th>
                    <th>Total Copies</th>
                    <th>Department</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM books ORDER BY book_id DESC");
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td><input type='checkbox' class='select-item'></td>
                                <td>{$row['title']}</td>
                                <td>{$row['author']}</td>
                                <td>{$row['isbn']}</td>
                                <td>{$row['copies_available']}</td>
                                <td>{$row['total_copies']}</td>
                                <td>{$row['department']}</td>
                                <td>
                                    <form method='POST' onsubmit='return confirmDelete()'>
                                        <input type='hidden' name='book_id' value='{$row['book_id']}'>
                                        <button type='submit' name='delete_book' class='delete-btn'><i class='bx bx-trash'></i></button>
                                    </form>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No books added yet.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function confirmDelete() {
    return confirm("Are you sure you want to delete this book?");
}
document.addEventListener('DOMContentLoaded', function() {
    const openFormButton = document.getElementById('openFormButton');
    const addBookForm = document.getElementById('addBookForm');
    const container = document.querySelector('.container');
    const closeFormButton = document.getElementById('closeFormButton');

    openFormButton.addEventListener('click', function() {
        addBookForm.classList.toggle('active');
        container.classList.toggle('shifted');
    });

    closeFormButton.addEventListener('click', function() {
        addBookForm.classList.remove('active');
        container.classList.remove('shifted');
    });

    document.addEventListener('click', function(event) {
        if (!addBookForm.contains(event.target) && !openFormButton.contains(event.target)) {
            addBookForm.classList.remove('active');
            container.classList.remove('shifted');
        }
    });
});
</script>

</body>
</html>
