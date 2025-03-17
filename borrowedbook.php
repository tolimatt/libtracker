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
<nav class="header">
    <h1>Borrowed Book</h1>

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
<div class="container3">
    
    <div class="search-sort">
        <input type="text" id="search3" placeholder="Search...">
        <select id="statusFilter" class="filter-attendance">
            <option value="">All Status</option>
            <option value="Borrowed">Borrowed</option>
            <option value="Returned">Returned</option>
        </select>
    </div>

    <div class="table-container4">
        <table>
            <thead>
                <tr>
                    <th data-column1="transaction_id" data-order="asc">Transaction ID<i class='bx bx-sort sort-icon'></i></th>
                    <th data-column1="title" data-order="asc">Book Title<i class='bx bx-sort sort-icon'></i></th>
                    <th data-column1="student_id" data-order="asc">Student ID<i class='bx bx-sort sort-icon'></i></th>
                    <th data-column1="last_name" data-order="asc">Last Name<i class='bx bx-sort sort-icon'></i></th>
                    <th data-column1="first_name" data-order="asc">First Name<i class='bx bx-sort sort-icon'></i></th>
                    <th data-column1="borrowed_date" data-order="asc">Borrowed Date<i class='bx bx-sort sort-icon'></i></th>
                    <th data-column1="status" data-order="asc">Status<i class='bx bx-sort sort-icon'></i></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="borrowedBookTableBody">
                <?php
                $query = "SELECT borrow.transaction_id, borrow.title, user.student_id, user.last_name, user.first_name, 
                                 borrow.borrowed_date, borrow.status
                          FROM borrow
                          INNER JOIN user ON borrow.student_id = user.student_id
                          ORDER BY borrow.borrowed_date DESC";

                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $disabled = ($row['status'] === 'Returned') ? 'disabled' : '';
                        echo "<tr>
                                <td>{$row['transaction_id']}</td>
                                <td>{$row['title']}</td>
                                <td>{$row['student_id']}</td>
                                <td>{$row['last_name']}</td>
                                <td>{$row['first_name']}</td>
                                <td>{$row['borrowed_date']}</td>
                                <td>{$row['status']}</td>
                                <td>
                                <div class='actions_button'>
                                    <button class='return-btn' data-transaction-id='{$row['transaction_id']}' {$disabled}>Return</button>

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
            'title': 1,
            'student_id': 2,
            'last_name': 3,
            'first_name': 4,
            'borrowed_date': 5,
            'status': 6
        };
        return columnOrder[column];
    }
});

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.return-btn').forEach(button => {
        button.addEventListener('click', function() {
            const transactionId = this.getAttribute('data-transaction-id');
            updateStatus(transactionId, 'return');
        });
    });

    document.querySelectorAll('.set-returned-btn').forEach(button => {
        button.addEventListener('click', function() {
            const transactionId = this.getAttribute('data-transaction-id');
            updateStatus(transactionId, 'set_returned');
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
            console.log("Response from server:", data); // Debugging line

            if (data.success) {
                const row = document.querySelector(`button[data-transaction-id='${transactionId}']`).closest('tr');
                if (action === 'return' || action === 'set_returned') {
                    row.cells[6].textContent = 'Returned';
                    row.querySelectorAll('button').forEach(btn => btn.disabled = true);
                }
            } else {
                alert('Error updating status: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
        });
    }
});

function filterTable() {
    const searchInput = document.getElementById('search3');
    const statusFilter = document.getElementById('statusFilter');
    const filter = searchInput.value.toLowerCase();
    const status = statusFilter.value.toLowerCase();
    const rows = document.getElementById('borrowedBookTableBody').getElementsByTagName('tr');

    Array.from(rows).forEach(row => {
        const transaction_id = row.cells[0].textContent.toLowerCase();
        const title = row.cells[1].textContent.toLowerCase();
        const student_id = row.cells[2].textContent.toLowerCase();
        const last_name = row.cells[3].textContent.toLowerCase();
        const first_name = row.cells[4].textContent.toLowerCase();
        const borrowed_date = row.cells[5].textContent.toLowerCase();
        const status_text = row.cells[6].textContent.toLowerCase();

        const matchesSearch = transaction_id.includes(filter) || title.includes(filter) || student_id.includes(filter) || last_name.includes(filter) || first_name.includes(filter) || borrowed_date.includes(filter);
        const matchesStatus = status === "" || status_text === status;

        if (matchesSearch && matchesStatus) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

document.getElementById('search3').addEventListener('input', filterTable);
document.getElementById('statusFilter').addEventListener('change', filterTable);
</script>

</body>
</html>