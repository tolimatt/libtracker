<?php
ob_start();
include 'db_config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="user.css">
    <link rel="stylesheet" href="global.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <title>User Management</title>
</head>
<body>
<nav class="header">
    <h1>Students</h1>

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
<div class="container4">
    <div class="search-sort">
        <input type="text" id="search2" placeholder="Search...">
        <select id="userFilter" class="filter-attendance">
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
    
    <div class="table-container1">
        <table>
            <thead>
                <tr>
                    <th data-column4="student_id">Student Id<i class='bx bx-sort sort-icon'></i></th>
                    <th data-column4="first_name">First Name<i class='bx bx-sort sort-icon'></i></th>
                    <th data-column4="last_name">Last Name<i class='bx bx-sort sort-icon'></i></th>
                    <th data-column4="department">Department<i class='bx bx-sort sort-icon'></i></th>
                    <th data-column4="year_level">Year Level<i class='bx bx-sort sort-icon'></i></th>
                    <th data-column4="phinmaed_email">Phinma Email<i class='bx bx-sort sort-icon'></i></th>
                    <th data-column4="contact_number">Contact Number</th>
                    <th data-column4="status">Status<i class='bx bx-sort sort-icon'></i></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                <?php
                $result = $conn->query("SELECT * FROM user ORDER BY user_id DESC");
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $status = isset($row['status']) && $row['status'] == 1 ? 'Active' : 'Deactivated';
                        $toggleStatus = $row['status'] == 1 ? 'Deactivate' : 'Activate';
                        $toggleClass = $row['status'] == 1 ? 'deactivate-btn' : 'activate-btn';
                        $toggleIcon = $row['status'] == 1 ? 'bx-user-x' : 'bx-user-check';
                        $statusClass = $row['status'] == 1 ? 'status-active' : 'status-deactivated';
                        echo "<tr>
                                <td>{$row['student_id']}</td>
                                <td>{$row['first_name']}</td>
                                <td>{$row['last_name']}</td>
                                <td>{$row['department']}</td>
                                <td>{$row['year_level']}</td>
                                <td>{$row['phinmaed_email']}</td>
                                <td>{$row['contact_number']}</td>
                                <td class='{$statusClass}'>{$status}</td>
                                <td>
                                    <form method='POST' class='toggle-status-form'>
                                        <input type='hidden' name='user_id' value='{$row['user_id']}'>
                                        <div class='actions_button'>
                                            <button type='submit' name='toggle_status' class='toggle-status-btn {$toggleClass}' onclick='return confirmToggleStatus(event, \"{$toggleStatus}\")'><i class='bx {$toggleIcon}'></i> {$toggleStatus}</button>
                                            <button type='button' class='edit-btn' onclick='editUser({$row['user_id']})'><i class='bx bx-edit ' ></i></button>
                                            <button type='button' class='three-dot-btn' onclick='seeMore({$row['user_id']})'><i class='bx bx-dots-vertical-rounded'></i></button>
                                            </div>
                                    </form>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='10'>No Registered User.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>




    <!-- Floating Table Container -->
    <div id="floatingTableContainer" class="floating-table-container">
        <span class="close-floating-table" onclick="closeFloatingTable()">×</span>
        <h2>User Details</h2>
        <div id="floatingTableContent"></div>
    </div>

    <!-- Edit User Sliding Form -->
    <div id="editUserContainer" class="edit-user-container">
        <h1>Edit User</h1>
        <form id="editUserForm" method="POST">
            <input type="hidden" name="user_id" id="editUserId">
            <input type="text" name="first_name" placeholder="First Name" id="editFirstName" required>
            <input type="text" name="last_name" placeholder="Last Name" id="editLastName" required>
            <select name="department" id="editCategory" required>
                <option value="" disabled selected>Select Department</option>
                <option value="CITE">CITE</option>
                <option value="CAHS">CAHS</option>
                <option value="CCJE">CCJE</option>
                <option value="CEA">CEA</option>
                <option value="CELA">CELA</option>
                <option value="CMA">CMA</option>
                <option value="COL">COL</option>
                <option value="SHS">SHS</option>
            </select>
            <select name="year_level" id="editYearLevel" required>
                <option value="" disabled selected>Select Year Level</option>
                <option value="Freshmen (1st Year)">Freshmen (1st Year)</option>
                <option value="Sophomore (2nd Year)">Sophomore (2nd Year)</option>
                <option value="Junior (3rd Year)">Junior (3rd Year)</option>
                <option value="Senior (4th Year)">Senior (4th Year)</option>
                <option value="Super Senior (5th Year)">Super Senior (5th Year)</option>
            </select>
            <input type="email" name="phinmaed_email" id="editEmail" required>
            <input type="text" name="contact_number" id="editContactNumber" required>
            <button type="submit" name="update_user" class="update-btn">Update</button>
            <button type="button" id="closeFormButton" onclick="closeEditForm()">Cancel</button>
        </form>
    </div>

                                             <!-- End of Edit User Sliding Form -->

            <!-- SCRIPT -->
    <script>

document.getElementById('editUserForm').addEventListener('submit', function (event) {
    event.preventDefault();

    // Format first name and last name to Title Case
    const firstNameInput = document.getElementById('editFirstName');
    const lastNameInput = document.getElementById('editLastName');
    firstNameInput.value = toTitleCase(firstNameInput.value);
    lastNameInput.value = toTitleCase(lastNameInput.value);

    const formData = new FormData(this);
    fetch('update_user.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('User updated successfully!');
            closeEditForm();

            // Update UI dynamically
            const row = document.querySelector(`input[value='${formData.get("user_id")}']`).closest('tr');
            row.cells[1].textContent = formData.get("first_name");
            row.cells[2].textContent = formData.get("last_name");
            row.cells[3].textContent = formData.get("department");
            row.cells[4].textContent = formData.get("year_level");
            row.cells[5].textContent = formData.get("phinmaed_email");
        } else {
            alert('Error updating user: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
});

function toTitleCase(str) {
    return str.replace(/\w\S*/g, function (txt) {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    });
}



document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.toggle-status-btn').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            const userId = this.closest('form').querySelector('input[name="user_id"]').value;
            const action = this.textContent.trim();
            if (confirmToggleStatus(event, action)) {
                toggleUserStatus(userId, action);
            }
        });
    });

    function toggleUserStatus(userId, action) {
        fetch('toggle_status.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ user_id: userId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const row = document.querySelector(`input[value='${userId}']`).closest('tr');
                const statusCell = row.cells[7]; // Status column
                const button = row.querySelector('.toggle-status-btn');
                const icon = button.querySelector('i');

                if (data.new_status === 1) {
                    statusCell.textContent = 'Active';
                    statusCell.classList.add('status-active');
                    statusCell.classList.remove('status-deactivated');
                    button.innerHTML = '<i class="bx bx-user-x"></i> Deactivate';
                    button.classList.remove('activate-btn');
                    button.classList.add('deactivate-btn');
                } else {
                    statusCell.textContent = 'Deactivated';
                    statusCell.classList.add('status-deactivated');
                    statusCell.classList.remove('status-active');
                    button.innerHTML = '<i class="bx bx-user-check"></i> Activate';
                    button.classList.remove('deactivate-btn');
                    button.classList.add('activate-btn');
                }
            } else {
                alert('Error toggling status: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function confirmToggleStatus(event, action) {
        if (!confirm(`Are you sure you want to ${action.toLowerCase()} this user?`)) {
            event.preventDefault();
            return false;
        }
        return true;
    }
});


    document.addEventListener('DOMContentLoaded', function() { 

        const userTableBody = document.getElementById('userTableBody');
        const userTableHeaders = document.querySelectorAll('th[data-column4]');

        userTableHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const column = header.getAttribute('data-column4');
            let order = header.getAttribute('data-order');

            order = order === 'asc' ? 'desc' : 'asc';
            header.setAttribute('data-order', order);

            user_sortTable(column, order);
        });
    });

    function  user_sortTable(column, order) {
        const rows = Array.from(userTableBody.querySelectorAll('tr'));
        const columnIndex = user_getColumnIndex(column);

        rows.sort((a, b) => {
            const cellA = a.cells[columnIndex].textContent.trim().toLowerCase();
            const cellB = b.cells[columnIndex].textContent.trim().toLowerCase();

            if (order === 'asc') {
                return cellA.localeCompare(cellB);
            } else {
                return cellB.localeCompare(cellA);
            }
        });

        rows.forEach(row =>  userTableBody.appendChild(row));
    }

    function user_getColumnIndex(column) {
        const columnOrder = {
            'student_id': 0,
            'first_name': 1,
            'last_name': 2,
            'department': 3,
            'year_level': 4,
            'phinmaed_email': 5,
            'status': 7
        };
        return columnOrder[column];
    }


    });
    



    function confirmAction() {
        return confirm('Are you sure you want to perform this action?');
    }

    function editUser(userId) {
        // Fetch user data and populate the form
        fetch(`get_user.php?user_id=${userId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('editUserId').value = data.user_id;
                document.getElementById('editFirstName').value = data.first_name;
                document.getElementById('editLastName').value = data.last_name;
                document.getElementById('editCategory').value = data.department;
                document.getElementById('editYearLevel').value = data.year_level;
                document.getElementById('editEmail').value = data.phinmaed_email;
                document.getElementById('editContactNumber').value = data.contact_number;

                // Set the selected department
                const departmentSelect = document.getElementById('editCategory');
                for (let i = 0; i < departmentSelect.options.length; i++) {
                    if (departmentSelect.options[i].value === data.department) {
                        departmentSelect.selectedIndex = i;
                        break;
                    }
                }

                // Set the selected year level
                const yearLevelSelect = document.getElementById('editYearLevel');
                for (let i = 0; i < yearLevelSelect.options.length; i++) {
                    if (yearLevelSelect.options[i].value === data.year_level) {
                        yearLevelSelect.selectedIndex = i;
                        break;
                    }
                }

                document.getElementById('editUserContainer').classList.add('active');
                document.querySelector('.container4').classList.add('shifted');
            });
    }

    function closeEditForm() {
        document.getElementById('editUserContainer').classList.remove('active');
        document.querySelector('.container4').classList.remove('shifted');
    }

    function filterTable() {
        const searchInput = document.getElementById('search2');
        const userFilter = document.getElementById('userFilter');
        const filter = searchInput.value.toLowerCase();
        const department = userFilter.value.toLowerCase();
        const rows = document.getElementById('userTableBody').getElementsByTagName('tr');

        Array.from(rows).forEach(row => {
            const student_id = row.cells[0].textContent.toLowerCase();
            const first_name = row.cells[1].textContent.toLowerCase();
            const last_name = row.cells[2].textContent.toLowerCase();
            const row_department = row.cells[3].textContent.toLowerCase();
            const year_level = row.cells[4].textContent.toLowerCase();
            const phinmaed_email = row.cells[5].textContent.toLowerCase();
            const contact_number = row.cells[6].textContent.toLowerCase();

            const matchesSearch = student_id.includes(filter) || first_name.includes(filter) || last_name.includes(filter) || year_level.includes(filter) || phinmaed_email.includes(filter) || contact_number.includes(filter);
            const matchesDepartment = department === "" || row_department === department;

            if (matchesSearch && matchesDepartment) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    document.getElementById('search2').addEventListener('input', filterTable);
    document.getElementById('userFilter').addEventListener('change', filterTable);
    
    function seeMore(userId) {
        fetch(`get_user_details.php?user_id=${userId}`)
            .then(response => response.json())
            .then(data => {
                let content = `
                    <table class="userdetail" style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <th style="text-align: left; padding: 8px;">Student ID</th>
                            <td style="padding: 8px;">${data.user.student_id}</td>
                        </tr>
                        <tr>
                            <th style="text-align: left; padding: 8px;">Name</th>
                            <td style="padding: 8px;">${data.user.first_name} ${data.user.last_name}</td>
                        </tr>
                        <tr>
                            <th style="text-align: left; padding: 8px;">Department</th>
                            <td style="padding: 8px;">${data.user.department}</td>
                        </tr>
                        <tr>
                            <th style="text-align: left; padding: 8px;">Year Level</th>
                            <td style="padding: 8px;">${data.user.year_level}</td>
                        </tr>
                        <tr>
                            <th style="text-align: left; padding: 8px;">Email</th>
                            <td style="padding: 8px;">${data.user.phinmaed_email}</td>
                        </tr>
                        <tr>
                            <th style="text-align: left; padding: 8px;">Contact Number</th>
                            <td style="padding: 8px;">${data.user.contact_number}</td>
                        </tr>
                    </table>
                    `;


                content += '<h3>Attendance</h3>';
                content += '<table><thead><tr><th>Date</th><th>Entry Time</th></tr></thead><tbody>';
                data.attendance.forEach(att => {
                    content += `<tr><td>${att.date}</td><td>${att.entry_time}</td></tr>`;
                });
                content += '</tbody></table>';

                content += '<h3>Borrowed Books</h3>';
                content += '<table><thead><tr><th>Book Title</th><th>Borrowed Date</th><th>Return Date</th><th>Status</th></tr></thead><tbody>';
                data.borrow.forEach(book => {
                    content += `<tr><td>${book.title}</td><td>${book.borrowed_date}</td><td>${book.return_date}</td><td>${book.status}</td></tr>`;
                });
                content += '</tbody></table>';

                document.getElementById('floatingTableContent').innerHTML = content;
                document.getElementById('floatingTableContainer').classList.add('active');
            });
    }

    function closeFloatingTable() {
        document.getElementById('floatingTableContainer').classList.remove('active');
    }
    </script>
</body>
<?php
ob_end_flush();
?>
</html>
