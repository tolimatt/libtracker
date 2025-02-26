<?php include 'db_config.php'; ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["delete_book"])) {
        $book_id = $_POST["book_id"];
        $delete_sql = "DELETE FROM books WHERE book_id = $book_id";
        if ($conn->query($delete_sql) === TRUE) {
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
    <link rel="stylesheet" href="add_book1.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <title>Book Management</title>
</head>
<body>
<div class="container">
    <div class="search-sort">
        <h1>Book Management</h1>
        <input type="text" id="search" placeholder="Search...">
        <button class="filter-btn"><i class='bx bx-filter-alt'></i> Filter</button>
        <button class="sort-btn"><i class='bx bx-sort'></i> Sort</button>
    </div>
    <button id="openFormButton" class="add-btn">+ ADD BOOK</button>

    <div id="addBookForm" class="addbookform-container">
        <h1>Add Book</h1>
        <form action="" method="POST" class="modal-content" id="bookForm">
            <input type="text" name="title" id="title" placeholder="Book Title" required>
            <input type="text" name="author" id="author" placeholder="Author" required>
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
                <option value="CCJE">CCJE</option>
                <option value="CAHS">CAHS</option>
            </select>
            <button type="submit">Add Book</button>
            <button type="button" id="closeFormButton">Cancel</button>
        </form>
    </div>

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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="bookTableBody">
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
    const searchInput = document.getElementById('search');
    const bookTableBody = document.getElementById('bookTableBody');
    const bookForm = document.getElementById('bookForm');

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

    searchInput.addEventListener('input', function() {
        const filter = searchInput.value.toLowerCase();
        const rows = bookTableBody.getElementsByTagName('tr');

        Array.from(rows).forEach(row => {
            const title = row.cells[1].textContent.toLowerCase();
            const author = row.cells[2].textContent.toLowerCase();
            const isbn = row.cells[3].textContent.toLowerCase();
            const department = row.cells[6].textContent.toLowerCase();

            if (title.includes(filter) || author.includes(filter) || isbn.includes(filter) || department.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    bookForm.addEventListener('submit', function(event) {
        const titleInput = document.getElementById('title');
        const authorInput = document.getElementById('author');

        titleInput.value = toTitleCase(titleInput.value);
        authorInput.value = toTitleCase(authorInput.value);
    });

    function toTitleCase(str) {
        return str.replace(/\w\S*/g, function(txt) {
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        });
    }
});
</script>

</body>
</html>
