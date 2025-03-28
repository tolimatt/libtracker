<?php include 'db_config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="borrowedbook.css">
    <link rel="stylesheet" href="global.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <title>Borrowed Book Management</title>
</head>
<body>

<div class="container3">
    <div class="search-sort">
        <h1>Borrowed Book</h1>
        <input type="text" id="search3" placeholder="Search...">
        <select id="statusFilter" class="filter-attendance">
            <option value="">All Status</option>
            <option value="Pending">Pending</option>
            <option value="Returned">Returned</option>
            <option value="Renewed">Renewed</option>
        </select>
        
    </div>

    <div class="table-container4">
        <table>
            <thead>
                <tr>
                    <th data-column1="transaction_id" data-order="asc">Transaction ID<i class='bx bx-sort sort-icon'></i></th>
      
                    <th data-column1="last_name" data-order="asc">Last Name<i class='bx bx-sort sort-icon'></i></th>
                    <th data-column1="first_name" data-order="asc">First Name<i class='bx bx-sort sort-icon'></i></th>
                    <th data-column1="borrowed_date" data-order="asc">Borrowed Date<i class='bx bx-sort sort-icon'></i></th>
                    <th data-column1="return_date" data-order="asc">Return Date<i class='bx bx-sort sort-icon'></i></th>
                    <th data-column1="return_status" data-order="asc">Return Status<i class='bx bx-sort sort-icon'></i></th>
                    <th data-column1="renewal_status" data-order="asc">Renewal Status<i class='bx bx-sort sort-icon'></i></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="borrowedBookTableBody">
                <?php
                $query = "SELECT borrowed_books.transaction_id, user.last_name, user.first_name, 
                                 borrowed_books.borrowed_date, borrowed_books.return_date, borrowed_books.return_status, borrowed_books.renewal_status 
                          FROM borrowed_books 
                          INNER JOIN user ON borrowed_books.user_id = user.user_id 
                          ORDER BY borrowed_books.borrowed_date DESC";

                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['transaction_id']}</td>
                                <td>{$row['last_name']}</td>
                                <td>{$row['first_name']}</td>
                                <td>{$row['borrowed_date']}</td>
                                <td>{$row['return_date']}</td>
                                <td>{$row['return_status']}</td>
                                <td>{$row['renewal_status']}</td>
                                <td>
                                <div class='actions_button'>
                                    <button class='return-btn' data-id='{$row['transaction_id']}'>Return</button>
                                    <button class='renew-btn' data-id='{$row['transaction_id']}'>Renew</button>
                                </div>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No Borrowed Books Found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>

document.addEventListener('DOMContentLoaded', function() {
    const borrowedBook_tableHeaders = document.querySelectorAll('th[data-column1]');
    const borrowedBook_tableBody = document.getElementById('borrowedBookTableBody');

    borrowedBook_tableHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const column = header.getAttribute('data-column1');
            let order = header.getAttribute('data-order');

            order = order === 'asc' ? 'desc' : 'asc';
            header.setAttribute('data-order', order);

            borrowedBook_sortTable(column, order);
        });
    

    function borrowedBook_sortTable(column, order) {
        const rows = Array.from(borrowedBook_tableBody.querySelectorAll('tr'));
        const columnIndex = borrowedBook_getColumnIndex(column);

        rows.sort((a, b) => {
            const cellA = a.cells[columnIndex].textContent.trim().toLowerCase();
            const cellB = b.cells[columnIndex].textContent.trim().toLowerCase();

            if (order === 'asc') {
                return cellA.localeCompare(cellB);
            } else {
                return cellB.localeCompare(cellA);
            }
        });

        rows.forEach(row => borrowedBook_tableBody.appendChild(row));
    }

    function borrowedBook_getColumnIndex(column) {
        const columnOrder = {
            'transaction_id': 0,
            'last_name': 1,
            'first_name': 2,
            'borrowed_date': 3,
            'return_date': 4,
            'return_status': 5,
            'renewal_status': 6
        };
        return columnOrder[column];
    }
});


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

    function filterTable() {
        const searchInput = document.getElementById('search3');
        const statusFilter = document.getElementById('statusFilter');
        const filter = searchInput.value.toLowerCase();
        const status = statusFilter.value.toLowerCase();
        const rows = document.getElementById('borrowedBookTableBody').getElementsByTagName('tr');

        Array.from(rows).forEach(row => {
            const transaction_id = row.cells[0].textContent.toLowerCase();
            const last_name = row.cells[1].textContent.toLowerCase();
            const first_name = row.cells[2].textContent.toLowerCase();
            const borrowed_date = row.cells[3].textContent.toLowerCase();
            const return_date = row.cells[4].textContent.toLowerCase();
            const return_status = row.cells[5].textContent.toLowerCase();
            const renewal_status = row.cells[6].textContent.toLowerCase();

            const matchesSearch = transaction_id.includes(filter) || last_name.includes(filter) || first_name.includes(filter) || borrowed_date.includes(filter) || return_date.includes(filter);
            const matchesStatus = status === "" || return_status === status || renewal_status === status;

            if (matchesSearch && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    document.getElementById('search3').addEventListener('input', filterTable);
    document.getElementById('statusFilter').addEventListener('change', filterTable);
});

</script>

</body>
</html>