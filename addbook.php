<?php include 'db_config.php'; ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["delete_book"])) {
        $book_id = $_POST["book_id"];
        $delete_sql = "DELETE FROM books WHERE book_id = $book_id";
        if ($conn->query($delete_sql) === TRUE) {
            // Book deleted successfully
        } else {
            echo "<script>alert('Error deleting book: " . $conn->error . "');</script>";
        }
    } elseif (isset($_POST["update_book"])) {
        $book_id = $_POST["book_id"];
        $title = $_POST["title"];
        $author = $_POST["author"];
        $isbn = $_POST["isbn"];
        $copies_available = $_POST["copies_available"];
        $total_copies = $_POST["total_copies"];
        $department = $_POST["department"];

        $update_sql = "UPDATE books SET title = '$title', author = '$author', isbn = '$isbn', copies_available = '$copies_available', total_copies = '$total_copies', department = '$department' WHERE book_id = $book_id";
        if ($conn->query($update_sql) === TRUE) {
            // Book updated successfully
        } else {
            echo "<script>alert('Error updating book: " . $conn->error . "');</script>";
        }
    } else {
        if (isset($_POST['title'], $_POST['author'], $_POST['isbn'], $_POST['copies_available'], $_POST['total_copies'], $_POST['department'])) {
            $title = $_POST['title'];
            $author = $_POST['author'];
            $isbn = $_POST['isbn'];
            $copies_available = $_POST['copies_available'];
            $total_copies = $_POST['total_copies'];
            $department = $_POST['department'];

            $sql = "INSERT INTO books (title, author, isbn, copies_available, total_copies, department) 
                    VALUES ('$title', '$author', '$isbn', '$copies_available', '$total_copies', '$department')";

            if ($conn->query($sql) === TRUE) {
                // Book added successfully
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
    <link rel="stylesheet" href="global.css">
    <link rel="stylesheet" href="add_book.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <title>Book Management</title>
</head>
<body>
<div class="container" id="container5">
    <div class="search-sort">
        <h1>Book Management</h1>
        <input type="text" id="search" placeholder="Search...">
        <select id="bookmanagementFilter" class="filter-attendance">
            <option value="">All Departments</option>
            <option value="CITE">CITE</option>
            <option value="CMA">CMA</option>
            <option value="CEA">CEA</option>
            <option value="CAS">CAS</option>
            <option value="CELA">CELA</option>
            <option value="CCJE">CCJE</option>
            <option value="CAHS">CAHS</option>
        </select>
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
                <option value="" disabled selected>Select Genre</option>
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

    <div id="editBookContainer" class="edit-book-container">
        <h1>Edit Book</h1>
        <form id="editBookForm" method="POST">
            <input type="hidden" name="book_id" id="editBookId">
            <input type="text" name="title" id="editTitle" placeholder="Book Title" required>
            <input type="text" name="author" id="editAuthor" placeholder="Author" required>
            <input type="number" name="isbn" id="editIsbn" placeholder="Book Code" required>
            <input type="number" name="copies_available" id="editCopiesAvailable" placeholder="Available Copies" required>
            <input type="number" name="total_copies" id="editTotalCopies" placeholder="Total Copies" required>
            <select name="department" id="editDepartment" required>
                <option value="" disabled selected>Select Genre</option>
                <option value="CITE">CITE</option>
                <option value="CMA">CMA</option>
                <option value="CEA">CEA</option>
                <option value="CAS">CAS</option>
                <option value="CELA">CELA</option>
                <option value="CCJE">CCJE</option>
                <option value="CAHS">CAHS</option>
            </select>
            <button type="submit" name="update_book" class="update-btn">Update</button>
            <button type="button" id="closeEditFormButton" onclick="closeEditForm()">Cancel</button>
        </form>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th data-column="title">Title<i class='bx bx-sort sort-icon'></i></th>
                    <th data-column="author">Author<i class='bx bx-sort sort-icon'></i></th>
                    <th data-column="isbn">Book Code<i class='bx bx-sort sort-icon'></i></th>
                    <th data-column="copies_available">Available Copies<i class='bx bx-sort sort-icon'></i></th>
                    <th data-column="total_copies">Total Copies<i class='bx bx-sort sort-icon'></i></th>
                    <th data-column="department">Genre<i class='bx bx-sort sort-icon'></i></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="bookTableBody">
                <?php
                $result = $conn->query("SELECT * FROM books ORDER BY book_id DESC");
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['title']}</td>
                                <td>{$row['author']}</td>
                                <td>{$row['isbn']}</td>
                                <td>{$row['copies_available']}</td>
                                <td>{$row['total_copies']}</td>
                                <td>{$row['department']}</td>
                                <td>
                                     <div class='action-buttons'>
                                        <form method='POST' onsubmit='return confirmDelete()'>
                                        <input type='hidden' name='book_id' value='{$row['book_id']}'>
                                        <button type='submit' name='delete_book' class='delete-btn'><i class='bx bx-trash'></i></button>
                                    </form>
                                    <button type='button' class='edit-btn' onclick='editBook({$row['book_id']})'><i class='bx bx-edit'></i></button>
                                </div>
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

function editBook(bookId) {
    fetch(`get_book.php?book_id=${bookId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('editBookId').value = data.book_id;
            document.getElementById('editTitle').value = data.title;
            document.getElementById('editAuthor').value = data.author;
            document.getElementById('editIsbn').value = data.isbn;
            document.getElementById('editCopiesAvailable').value = data.copies_available;
            document.getElementById('editTotalCopies').value = data.total_copies;
            document.getElementById('editDepartment').value = data.department;
            document.getElementById('editBookContainer').classList.add('active');
            document.querySelector('.container').classList.add('shifted');
        });
}



document.addEventListener('DOMContentLoaded', function() {
    const openFormButton = document.getElementById('openFormButton');
    const addBookForm = document.getElementById('addBookForm');
    const container = document.querySelector('.container');
    const container_id = document.querySelector('#container');
    const closeFormButton = document.getElementById('closeFormButton');
    const searchInput = document.getElementById('search');
    const bookTableBody = document.getElementById('bookTableBody');
    const bookForm = document.getElementById('bookForm');
    const tableHeaders = document.querySelectorAll('th[data-column]');
    const closeEditFormButton = document.getElementById('closeEditFormButton');

    openFormButton.addEventListener('click', function() {
    addBookForm.classList.add('active');
    container.classList.add('shifted'); 
    });

    function closeEditForm() {
    document.getElementById('editBookContainer').classList.remove('active');
    document.querySelector('.container').classList.remove('shifted');
    }
    

    closeFormButton.addEventListener('click', function() {
        addBookForm.classList.remove('active');
        container.classList.remove('shifted');
    });

    closeEditFormButton.addEventListener('click', function() {
        closeEditForm();
    });

    document.addEventListener('click', function(event) {
        if (!addBookForm.contains(event.target) && !openFormButton.contains(event.target)) {
            addBookForm.classList.remove('active');
            container.classList.remove('shifted');
        }
        if (!editBookContainer.contains(event.target) && !event.target.classList.contains('edit-btn')) {
            editBookContainer.classList.remove('active');
            container_id.classList.remove('shifted');
        }
    });

    searchInput.addEventListener('input', function() {
        const filter = searchInput.value.toLowerCase();
        const rows = bookTableBody.getElementsByTagName('tr');

        Array.from(rows).forEach(row => {
            const title = row.cells[0].textContent.toLowerCase();
            const author = row.cells[1].textContent.toLowerCase();
            const isbn = row.cells[2].textContent.toLowerCase();
            const department = row.cells[5].textContent.toLowerCase();

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

    tableHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const column = header.getAttribute('data-column');
            const order = header.getAttribute('data-order') === 'asc' ? 'desc' : 'asc';
            header.setAttribute('data-order', order);
            sortTable(column, order);
        });
    });

    function sortTable(column, order) {
        const rows = Array.from(bookTableBody.getElementsByTagName('tr'));
        rows.sort((a, b) => {
            const cellA = a.querySelector(`td:nth-child(${getColumnIndex(column)})`).textContent.toLowerCase();
            const cellB = b.querySelector(`td:nth-child(${getColumnIndex(column)})`).textContent.toLowerCase();

            if (order === 'asc') {
                return cellA.localeCompare(cellB);
            } else {
                return cellB.localeCompare(cellA);
            }
        });

        rows.forEach(row => bookTableBody.appendChild(row));
    }

    function getColumnIndex(column) {
        switch (column) {
            case 'title': return 1;
            case 'author': return 2;
            case 'isbn': return 3;
            case 'copies_available': return 4;
            case 'total_copies': return 5;
            case 'department': return 6;
            default: return 1;
        }
    }
});
</script>

</body>
</html>
