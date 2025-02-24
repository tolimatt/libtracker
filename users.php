<?php
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['toggle_status'])) {
        $user_id = $_POST['user_id'];
        $result = $conn->query("SELECT status FROM user WHERE user_id = $user_id");
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $new_status = $row['status'] == 1 ? 0 : 1;
            $conn->query("UPDATE user SET status = $new_status WHERE user_id = $user_id");
        }
        
    }

    if (isset($_POST['update_user'])) {
        $user_id = $_POST['user_id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $department = $_POST['department'];
        $year_level = $_POST['year_level'];
        $phinmaed_email = $_POST['phinmaed_email'];
        $contact_number = $_POST['contact_number'];

        if ($conn->query("UPDATE user SET first_name = '$first_name', last_name = '$last_name', department = '$department', year_level = '$year_level', phinmaed_email = '$phinmaed_email', contact_number = '$contact_number' WHERE user_id = $user_id") === TRUE) {
            
        } else {
            echo 'Error: ' . $conn->error;
        }
        
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="users_css.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <title>User Management</title>
</head>
<body>
<div class="container4">
    <div class="search-sort">
        <h1>Users</h1>
        <input type="text" id="search" placeholder="Search...">
        <button class="filter-btn"><i class='bx bx-filter-alt'></i> Filter</button>
        <button class="sort-btn"><i class='bx bx-sort'></i> Sort</button>
    </div>
    
    <div class="table-container1">
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>Student Id</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Department</th>
                    <th>Year Level</th>
                    <th>Phinma Email</th>
                    <th>Contact Number</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM user ORDER BY user_id DESC");
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $status = isset($row['status']) && $row['status'] == 1 ? 'Active' : 'Deactivated';
                        $toggleStatus = $row['status'] == 1 ? 'Deactivate' : 'Activate';
                        $toggleIcon = $row['status'] == 1 ? 'bx-user-x' : 'bx-user-check';
                        echo "<tr>
                                <td><input type='checkbox' class='select-item'></td>
                                <td>{$row['student_id']}</td>
                                <td>{$row['first_name']}</td>
                                <td>{$row['last_name']}</td>
                                <td>{$row['department']}</td>
                                <td>{$row['year_level']}</td>
                                <td>{$row['phinmaed_email']}</td>
                                <td>{$row['contact_number']}</td>
                                <td>{$status}</td>
                                <td>
                                    <form method='POST' class='toggle-status-form'>
                                        <input type='hidden' name='user_id' value='{$row['user_id']}'>
                                        <button type='submit' name='toggle_status' class='toggle-status-btn' onclick='return confirmToggleStatus(event, \"{$toggleStatus}\")'><i class='bx {$toggleIcon}'></i> {$toggleStatus}</button>
                                        <button type='button' class='edit-btn' onclick='editUser({$row['user_id']})'><i class='bx bx-edit'></i></button>
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


    <!-- Edit User Sliding Form -->
    <div id="editUserContainer" class="edit-user-container">
        <h1>Edit User</h1>
        <form id="editUserForm" method="POST">
            <input type="hidden" name="user_id" id="editUserId">
            <input type="text" name="first_name" placeholder="First Name" id="editFirstName" required>
            <input type="text" name="last_name" placeholder="Last Name" id="editLastName" required>
            <input type="text" name="department" id="editDepartment" required>
            <input type="text" name="year_level" id="editYearLevel" required>
            <input type="email" name="phinmaed_email" id="editEmail" required>
            <input type="text" name="contact_number" id="editContactNumber" required>
            <button type="submit" name="update_user" class="update-btn">Update</button>
            <button type="button" id="closeFormButton" onclick="closeEditForm()">Cancel</button>
        </form>
    </div>





    <!-- SCRIPT -->
    <script>
    function confirmAction() {
        return confirm('Are you sure you want to perform this action?');
    }

    function confirmToggleStatus(event, action) {
        if (!confirm(`Are you sure you want to ${action.toLowerCase()} this user?`)) {
            event.preventDefault();
            return false;
        }
        return true;
    }

    function editUser(userId) {
        // Fetch user data and populate the form
        fetch(`get_user.php?user_id=${userId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('editUserId').value = data.user_id;
                document.getElementById('editFirstName').value = data.first_name;
                document.getElementById('editLastName').value = data.last_name;
                document.getElementById('editDepartment').value = data.department;
                document.getElementById('editYearLevel').value = data.year_level;
                document.getElementById('editEmail').value = data.phinmaed_email;
                document.getElementById('editContactNumber').value = data.contact_number;
                document.getElementById('editUserContainer').classList.add('active');
                document.querySelector('.container4').classList.add('shifted');
            });
    }

    function closeEditForm() {
        document.getElementById('editUserContainer').classList.remove('active');
        document.querySelector('.container4').classList.remove('shifted');
    }

    </script>
</body>
</html>
