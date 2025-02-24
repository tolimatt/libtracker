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

<div class="container3">
    <div class="search-sort">
        <h1>Borrowed Book</h1>
        <input type="text" id="search" placeholder="Search...">
        <button class="filter-btn"><i class='bx bx-filter-alt'></i> Filter</button>
        <button class="sort-btn"><i class='bx bx-sort'></i> Sort</button>
    </div>

    <div class="table-container4">
        <table>
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Book ID</th>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Borrowed Date</th>
                    <th>Return Date</th>
                    <th>Return Status</th>
                    <th>Renewal Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT borrowed_books.transaction_id, borrowed_books.book_id, user.last_name, user.first_name, 
                                 borrowed_books.borrowed_date, borrowed_books.return_date, borrowed_books.return_status, borrowed_books.renewal_status 
                          FROM borrowed_books 
                          INNER JOIN user ON borrowed_books.user_id = user.user_id 
                          ORDER BY borrowed_books.borrowed_date DESC";

                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['transaction_id']}</td>
                                <td>{$row['book_id']}</td>
                                <td>{$row['last_name']}</td>
                                <td>{$row['first_name']}</td>
                                <td>{$row['borrowed_date']}</td>
                                <td>{$row['return_date']}</td>
                                <td>{$row['return_status']}</td>
                                <td>{$row['renewal_status']}</td>
                                <td>
                                    <button class='return-btn' data-id='{$row['transaction_id']}'>Return</button>
                                    <button class='renew-btn' data-id='{$row['transaction_id']}'>Renew</button>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No Borrowed Books Found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.return-btn').forEach(button => {
        button.addEventListener('click', function() {
            const transactionId = this.getAttribute('data-id');
            updateStatus(transactionId, 'return');
        });
    });

    document.querySelectorAll('.renew-btn').forEach(button => {
        button.addEventListener('click', function() {
            const transactionId = this.getAttribute('data-id');
            updateStatus(transactionId, 'renew');
        });
    });

    function updateStatus(transactionId, action) {
        fetch('update_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ transaction_id: transactionId, action: action })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Status updated successfully!');
                location.reload(); // Reload the page to reflect changes
            } else {
                alert('Error updating status: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
});
</script>

</body>
</html>