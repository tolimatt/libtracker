<?php include 'db_config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="global.css">
    <link rel="stylesheet" href="attendance1.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <title>Attendance Management</title>
</head>
<body>
<nav class="header">
    <h1>Attendance</h1>

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
<div class="container2">
    <div class="search-sort">
    <button id="startScanner" class="add-btn">Start Scanner</button>
        <input type="text" id="search1" placeholder="Search...">
        <select id="departmentFilter" class="filter-attendance">
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
    
    
    <div id="scanner-container1">
        <div id="scanner">
            <h1 class="barcode_scanner">Barcode Scanner</h1>
        </div>
        <button id="stopScanner" class="add-btn">Stop Scanner</button>
    </div>

    <div class="table-container3">
        <table>
            <thead>
                <tr>
                    <th data-column3="student_id" data-order="asc">Student Id<i class='bx bx-sort sort-icon'></i></th>
                    <th data-column3="first_name" data-order="asc">First Name<i class='bx bx-sort sort-icon'></i></th>
                    <th data-column3="last_name" data-order="asc">Last Name<i class='bx bx-sort sort-icon'></i></th>
                    <th data-column3="department" data-order="asc">Department<i class='bx bx-sort sort-icon'></i></th>
                    <th data-column3="year_level" data-order="asc">Year Level<i class='bx bx-sort sort-icon'></i></th>
                    <th data-column3="entry_time" data-order="asc">Entry Time<i class='bx bx-sort sort-icon'></i></th>
                </tr>
            </thead>
            <tbody id="attendanceTableBody">
                <?php
                date_default_timezone_set('Asia/Manila');
                $query = "SELECT user.student_id, user.first_name, user.last_name, user.department, user.year_level, entry_time 
                          FROM attendance 
                          INNER JOIN user ON attendance.student_id = user.student_id 
                          ORDER BY attendance.entry_time DESC";

                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['student_id']}</td>
                                <td>{$row['first_name']}</td>
                                <td>{$row['last_name']}</td>
                                <td>{$row['department']}</td>
                                <td>{$row['year_level']}</td>
                                <td>{$row['entry_time']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No Attendance Records Found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add the audio element for the scanner sound -->
<audio id="scannerSound" src="scanner-beep.mp3" preload="auto"></audio>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startScannerButton = document.getElementById('startScanner');
    const stopScannerButton = document.getElementById('stopScanner');
    const scannerContainer = document.getElementById('scanner-container1');
    const container = document.querySelector('.container2');
    const scannerSound = document.getElementById('scannerSound');
    const attendanceTableBody = document.getElementById('attendanceTableBody');
    const searchInput = document.getElementById('search1');
    const departmentFilter = document.getElementById('departmentFilter');
    const attendance_tableHeaders = document.querySelectorAll('th[data-column3]');

    attendance_tableHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const column = header.getAttribute('data-column3');
            let order = header.getAttribute('data-order');

            order = order === 'asc' ? 'desc' : 'asc';
            header.setAttribute('data-order', order);

            attendance_sortTable(column, order);
        });
    });

    function attendance_sortTable(column, order) {
        const rows = Array.from(attendanceTableBody.querySelectorAll('tr'));
        const columnIndex = attendance_getColumnIndex(column);

        rows.sort((a, b) => {
            const cellA = a.cells[columnIndex].textContent.trim().toLowerCase();
            const cellB = b.cells[columnIndex].textContent.trim().toLowerCase();

            if (order === 'asc') {
                return cellA.localeCompare(cellB);
            } else {
                return cellB.localeCompare(cellA);
            }
        });

        rows.forEach(row => attendanceTableBody.appendChild(row));
    }

    function attendance_getColumnIndex(column) {
        const columnOrder = {
            'student_id': 0,
            'first_name': 1,
            'last_name': 2,
            'department': 3,
            'year_level': 4,
            'entry_time': 5,
        };
        return columnOrder[column];
    }

    let lastScannedCode = null;
    let lastScannedTime = 0;

    startScannerButton.addEventListener('click', function() {
        scannerContainer.classList.add('active');
        container.classList.add('shifted');
        startScanner();
    });

    stopScannerButton.addEventListener('click', function() {
        scannerContainer.classList.remove('active');
        container.classList.remove('shifted');
        stopScanner();
    });

    function startScanner() {
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector('#scanner'),
                constraints: {
                    width: 460,
                    height: 400,
                    facingMode: "environment"
                },
            },
            decoder: {
                readers: ["code_128_reader"]
            },
        }, function(err) {
            if (err) {
                console.log(err);
                return;
            }
            console.log("Initialization finished. Ready to start");
            Quagga.start();
        });

        Quagga.onDetected(onDetected);
    }

    function stopScanner() {
        Quagga.offDetected(onDetected);
        Quagga.stop();
    }

    function onDetected(data) {
        const code = data.codeResult.code;
        const currentTime = new Date().getTime();

        if (code !== lastScannedCode || (currentTime - lastScannedTime) > 5000) {
            lastScannedCode = code;
            lastScannedTime = currentTime;
            console.log("Barcode detected and processed : [" + code + "]", data);
            scannerSound.play().catch(error => {
                console.error('Error playing sound:', error);
            });
            markAttendance(code);
        }
    }

    function markAttendance(studentId) {
        fetch('mark_attendance.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ student_id: studentId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                scannerSound.play().catch(error => {
                    console.error('Error playing sound:', error);
                });
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td>${data.student_id}</td>
                    <td>${data.first_name}</td>
                    <td>${data.last_name}</td>
                    <td>${data.department}</td>
                    <td>${data.year_level}</td>
                    <td>${data.entry_time}</td>
                `;
                attendanceTableBody.prepend(newRow);
            } else {
                alert('Error marking attendance: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function attendance_filterTable() {
        const filter = searchInput.value.toLowerCase();
        const department = departmentFilter.value.toLowerCase();
        const rows = attendanceTableBody.getElementsByTagName('tr');

        Array.from(rows).forEach(row => {
            const student_id = row.cells[0].textContent.toLowerCase();
            const first_name = row.cells[1].textContent.toLowerCase();
            const last_name = row.cells[2].textContent.toLowerCase();
            const row_department = row.cells[3].textContent.toLowerCase();
            const year_level = row.cells[4].textContent.toLowerCase();

            const matchesSearch = student_id.includes(filter) || first_name.includes(filter) || last_name.includes(filter) || year_level.includes(filter);
            const matchesDepartment = department === "" || row_department === department;

            if (matchesSearch && matchesDepartment) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', attendance_filterTable);
    departmentFilter.addEventListener('change', attendance_filterTable);

});

</script>

</body>
</html>