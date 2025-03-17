<?php include 'db_config.php'; ?>

<?php
$imageFolder = "C:/xampp/htdocs/LibTrack/libtracker/book_images/";
$pdfFolder = "C:/xampp/htdocs/LibTrack/libtracker/book_pdf/";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["delete_book"])) {
        $book_id = $_POST["book_id"];
        $delete_sql = "DELETE FROM books WHERE book_id = $book_id";
        if ($conn->query($delete_sql) === TRUE) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "<script>alert('Error deleting book: " . $conn->error . "');</script>";
        }
    } elseif (isset($_POST["update_book"])) {
        $book_id = $_POST["book_id"];
        $title = $_POST["title"];
        $author = $_POST["author"];
        $book_code = $_POST["book_code"];
        $copies_available = $_POST["copies_available"];
        $total_copies = $_POST["total_copies"];
        $category = $_POST["category"];
        $image_url = $_POST["current_image_url"];
        $pdf_url = $_POST["current_pdf_url"];

        // Handle file uploads
        if (isset($_FILES['book_cover']) && $_FILES['book_cover']['error'] == 0) {
            $imageName = basename($_FILES['book_cover']['name']);
            $image_url = 'http://192.168.1.248/LibTrack/libtracker/book_images/' . $imageName;
            move_uploaded_file($_FILES['book_cover']['tmp_name'], $imageFolder . $imageName);
        }
        
        if (isset($_FILES['book_pdf']) && $_FILES['book_pdf']['error'] == 0) {
            $pdfName = basename($_FILES['book_pdf']['name']);
            $pdf_url = 'http://192.168.1.248/LibTrack/libtracker/book_pdf/' . $pdfName;
            move_uploaded_file($_FILES['book_pdf']['tmp_name'], $pdfFolder . $pdfName);
        }

        $update_sql = "UPDATE books SET title = '$title', author = '$author', book_code = '$book_code', copies_available = '$copies_available', total_copies = '$total_copies', category = '$category', image_url = '$image_url', pdf_url = '$pdf_url' WHERE book_id = $book_id";
        if ($conn->query($update_sql) === TRUE) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "<script>alert('Error updating book: " . $conn->error . "');</script>";
        }
    } else {
        if (isset($_POST['title'], $_POST['author'], $_POST['book_code'], $_POST['copies_available'], $_POST['total_copies'], $_POST['category'])) {
            $title = $_POST['title'];
            $author = $_POST['author'];
            $book_code = $_POST['book_code'];
            $copies_available = $_POST['copies_available'];
            $total_copies = $_POST['total_copies'];
            $category = $_POST['category'];
            $image_url = '';
            $pdf_url = '';

            // Handle file uploads
            if (isset($_FILES['book_cover']) && $_FILES['book_cover']['error'] == 0) {
                $imageName = basename($_FILES['book_cover']['name']);
                $image_url = 'http://192.168.1.248/LibTrack/libtracker/book_images/' . $imageName;
                move_uploaded_file($_FILES['book_cover']['tmp_name'], $imageFolder . $imageName);
            }
            
            if (isset($_FILES['book_pdf']) && $_FILES['book_pdf']['error'] == 0) {
                $pdfName = basename($_FILES['book_pdf']['name']);
                $pdf_url = 'http://192.168.1.248/LibTrack/libtracker/book_pdf/' . $pdfName;
                move_uploaded_file($_FILES['book_pdf']['tmp_name'], $pdfFolder . $pdfName);
            }

            $sql = "INSERT INTO books (title, author, book_code, copies_available, total_copies, category, image_url, pdf_url) 
                    VALUES ('$title', '$author', '$book_code', '$copies_available', '$total_copies', '$category', '$image_url', '$pdf_url')";

            if ($conn->query($sql) === TRUE) {
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "<script>alert('Error: " . $conn->error . "');</script>";
            }
        } else {
            echo "<script>alert('Please fill in all required fields.');</script>";
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
<nav class="header">
    <h1>Book Management</h1>

    <!-- Container to keep notification and header-right together -->
    <div class="header-actions">
        <button id="notificationButton" class="notification-btn">
            <i class='bx bx-bell'></i>
        </button>
        <div class="header-right">
            <?php echo date('l, F j, Y g:i A'); ?>
        </div>
    </div>
</nav>
<div class="container" id="container5">
    <div class="search-sort">
        <button id="openFormButton" class="add-btn">+ ADD BOOK</button>
        <input type="text" id="search" placeholder="Search..." >
        <select id="bookmanagementFilter" class="filter-attendance">
            <option value="">All categorys</option>
            <option value="CITE">CITE</option>
            <option value="CMA">CMA</option>
            <option value="CEA">CEA</option>
            <option value="CAS">CAS</option>
            <option value="CELA">CELA</option>
            <option value="CCJE">CCJE</option>
            <option value="CAHS">CAHS</option>
        </select>
    </div>
    

    <div id="addBookForm" class="addbookform-container">
        <h1>Add Book</h1>
        <form action="" method="POST" class="modal-content" id="bookForm" enctype="multipart/form-data">
            <input type="text" name="title" id="title" placeholder="Book Title" required>
            <input type="text" name="author" id="author" placeholder="Author" required>
            <input type="number" name="book_code" placeholder="Book Code" required>
            <input type="number" name="copies_available" placeholder="Available Copies" required>
            <input type="number" name="total_copies" placeholder="Total Copies" required>
            <select name="category" required>
                <option value="" disabled selected>Select Category</option>
                <optgroup label="CAHS">
                    <option value="Medicine">Medicine</option>
                    <option value="Psychology">Psychology</option>
                    <option value="Anatomy">Anatomy</option>
                </optgroup>
                <optgroup label="CCJE">
                    <option value="Crime">Crime</option>
                    <option value="Law">Law</option>
                    <option value="Murder">Murder</option>
                </optgroup>
                <optgroup label="CEA">
                    <option value="Architecture">Architecture</option>
                    <option value="Physics">Physics</option>
                    <option value="Engineering">Engineering</option>
                </optgroup>
                <optgroup label="CELA">
                    <option value="Politics">Politics</option>
                    <option value="Dictionary">Dictionary</option>
                    <option value="Education">Education</option>
                </optgroup>
                <optgroup label="CITE">
                    <option value="Technology">Technology</option>
                    <option value="Databases">Databases</option>
                    <option value="Internet">Internet</option>
                </optgroup>
                <optgroup label="CMA">
                    <option value="Entrepreneurship">Entrepreneurship</option>
                    <option value="Business">Business</option>
                    <option value="Management">Management</option>
                </optgroup>
                <optgroup label="ETC">
                    <option value="Action">Action</option>
                    <option value="Fiction">Fiction</option>
                    <option value="Biographies">Biographies</option>
                </optgroup>
            </select>
                <div class="input_bookcover">
                    <input type="file" name="book_cover" id="book_cover" accept="image/*" required>
                    <label for="book_cover" class="file-label">
                        <span class="button-text">Upload Cover Image</span>
                        <span class="file-name"></span>
                        <i class='bx bx-image'></i>
                    </label>
                </div>

                <div class="input_bookpdf">
                    <input type="file" name="book_pdf" id="book_pdf" accept="application/pdf" required>
                    <label for="book_pdf" class="file-label">
                        <span class="button-text">Upload PDF</span>
                        <span class="file-name"></span>
                        <i class='bx bx-file'></i>
                </label>
                </div>
            <button type="submit">Add Book</button>
            <button type="button" id="closeFormButton">Cancel</button>
        </form>
    </div>

    <div id="editBookContainer" class="edit-book-container">
        <h1>Edit Book</h1>
        <form id="editBookForm" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="book_id" id="editBookId">
    <input type="hidden" name="current_image_url" id="currentImageUrl">
    <input type="hidden" name="current_pdf_url" id="currentPdfUrl">
    <input type="text" name="title" id="editTitle" placeholder="Book Title" required>
    <input type="text" name="author" id="editAuthor" placeholder="Author" required>
    <input type="number" name="book_code" id="editbook_code" placeholder="Book Code" required>
    <input type="number" name="copies_available" id="editCopiesAvailable" placeholder="Available Copies" required>
    <input type="number" name="total_copies" id="editTotalCopies" placeholder="Total Copies" required>
    <select name="category" id="editCategory" required>
    <option value="" disabled selected>Select Category</option>
    <optgroup label="CAHS">
        <option value="Medicine">Medicine</option>
        <option value="Psychology">Psychology</option>
        <option value="Anatomy">Anatomy</option>
    </optgroup>
    <optgroup label="CCJE">
        <option value="Crime">Crime</option>
        <option value="Law">Law</option>
        <option value="Murder">Murder</option>
    </optgroup>
    <optgroup label="CEA">
        <option value="Architecture">Architecture</option>
        <option value="Physics">Physics</option>
        <option value="Engineering">Engineering</option>
    </optgroup>
    <optgroup label="CELA">
        <option value="Politics">Politics</option>
        <option value="Dictionary">Dictionary</option>
        <option value="Education">Education</option>
    </optgroup>
    <optgroup label="CITE">
        <option value="Technology">Technology</option>
        <option value="Databases">Databases</option>
        <option value="Internet">Internet</option>
    </optgroup>
    <optgroup label="CMA">
        <option value="Entrepreneurship">Entrepreneurship</option>
        <option value="Business">Business</option>
        <option value="Management">Management</option>
    </optgroup>
    <optgroup label="ETC">
        <option value="Action">Action</option>
        <option value="Fiction">Fiction</option>
        <option value="Biographies">Biographies</option>
    </optgroup>
</select>
    

    
<div class="input_bookcover">
    <input type="file" name="book_cover" id="editBookCover" accept="image/*">
    <label for="editBookCover" class="file-label">
        <span class="button-text">Upload Cover Image</span>
        <span class="file-name"></span>
        <i class='bx bx-image'></i>
    </label>
</div>

<div class="input_bookpdf">
    <input type="file" name="book_pdf" id="editBookPdf" accept="application/pdf">
    <label for="editBookPdf" class="file-label">
        <span class="button-text">Upload PDF</span>
        <span class="file-name"></span>
        <i class='bx bx-file'></i>
    </label>
</div>

                <div>
        <label>Current Book Cover:</label>
        <img id="currentBookCover" src="" alt="Current Book Cover" style="max-width: 100px;">
    </div>

    <div>
        <label>Current Book PDF:</label>
        <a id="currentBookPdf" href="" target="_blank">View Current PDF</a>
    </div>
    <button type="submit" name="update_book" class="update-btn">Update</button>
    <button type="button" id="closeEditFormButton" onclick="closeEditForm()">Cancel</button>
</form>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Book Cover</th>
                    <th data-column="title">Title<i class='bx bx-sort sort-icon'></i></th>
                    <th data-column="author">Author<i class='bx bx-sort sort-icon'></i></th>
                    <th data-column="book_code">Book Code<i class='bx bx-sort sort-icon'></i></th>
                    <th data-column="copies_available">Available Copies<i class='bx bx-sort sort-icon'></i></th>
                    <th data-column="total_copies">Total Copies<i class='bx bx-sort sort-icon'></i></th>
                    <th data-column="category">Category</th>
                    <th>Book PDF</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="bookTableBody">
                <?php
                $result = $conn->query("SELECT * FROM books ORDER BY book_id DESC");
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td><img src='{$row['image_url']}' alt='{$row['title']}' class='book-cover'></td>
                                <td>{$row['title']}</td>
                                <td>{$row['author']}</td>
                                <td>{$row['book_code']}</td>
                                <td>{$row['copies_available']}</td>
                                <td>{$row['total_copies']}</td>
                                <td>{$row['category']}</td>
                                <td><a href='{$row['pdf_url']}' target='_blank'>View PDF</a></td>
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
                    echo "<tr><td colspan='9'>No books added yet.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>

document.querySelectorAll('.input_bookcover input, .input_bookpdf input').forEach(input => {
  input.addEventListener('change', function(e) {
    const fileName = this.files[0] ? this.files[0].name : '';
    this.nextElementSibling.querySelector('.file-name').textContent = fileName;
  });
});

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
            document.getElementById('editbook_code').value = data.book_code;
            document.getElementById('editCopiesAvailable').value = data.copies_available;
            document.getElementById('editTotalCopies').value = data.total_copies;
            document.getElementById('editCategory').value = data.category;

            // Set the current image and PDF URLs
            document.getElementById('currentImageUrl').value = data.image_url;
            document.getElementById('currentPdfUrl').value = data.pdf_url;
            document.getElementById('currentBookCover').src = data.image_url;
            document.getElementById('currentBookPdf').href = data.pdf_url;

            document.getElementById('editBookContainer').classList.add('active');
            document.querySelector('.container').classList.add('shifted');
            document.querySelector('.header-actions').classList.add('shifted');
            
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
    const bookCovers = document.querySelectorAll('.book-cover');
    const headerActions = document.querySelector('.header-actions');

    

    bookCovers.forEach(cover => {
    cover.addEventListener('click', () => {
        // Close any other active cover
        bookCovers.forEach(otherCover => {
            if (otherCover !== cover) {
                otherCover.classList.remove('active');
            }
        });
        // Toggle the clicked cover
        cover.classList.toggle('active');
    });
});

    openFormButton.addEventListener('click', function() {
        addBookForm.classList.add('active');
        container.classList.add('shifted');
        headerActions.classList.add('shifted');
    });

    function closeEditForm() {
        document.getElementById('editBookContainer').classList.remove('active');
        document.querySelector('.container').classList.remove('shifted');
    }

    closeFormButton.addEventListener('click', function() {
        addBookForm.classList.remove('active');
        container.classList.remove('shifted');
        headerActions.classList.remove('shifted');
    });

    closeEditFormButton.addEventListener('click', function() {
        closeEditForm();
    });
    
    document.addEventListener('click', function(event) {
        if (!addBookForm.contains(event.target) && !openFormButton.contains(event.target)) {
            addBookForm.classList.remove('active');
            container.classList.remove('shifted');
            headerActions.classList.remove('shifted');
        }
        if (!editBookContainer.contains(event.target) && !event.target.classList.contains('edit-btn')) {
            editBookContainer.classList.remove('active');
            container_id.classList.remove('shifted');
            headerActions.classList.remove('shifted');
        }
    });

    searchInput.addEventListener('input', function() {
        const filter = searchInput.value.toLowerCase();
        const rows = bookTableBody.getElementsByTagName('tr');

        Array.from(rows).forEach(row => {
            const title = row.cells[1].textContent.toLowerCase();
            const author = row.cells[2].textContent.toLowerCase();
            const book_code = row.cells[3].textContent.toLowerCase();
            const category = row.cells[5].textContent.toLowerCase();

            if (title.includes(filter) || author.includes(filter) || book_code.includes(filter) || category.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });



    // This will make the input in to title case
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

    // Sort table

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
            case 'title': return 2;
            case 'author': return 3;
            case 'book_code': return 4;
            case 'copies_available': return 5;
            case 'total_copies': return 6;
            case 'category': return 7;
            default: return 1;
        }
    }
});



</script>

</body>
</html>