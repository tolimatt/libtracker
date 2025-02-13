<?php include 'db_config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="borrowedbook.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <title>Borrowed Book Management</title>
</head>
<body>
<div class="container1">
    <div class="search-sort">
        <h1>Borrowed Book</h1>
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
</body>
</html>
