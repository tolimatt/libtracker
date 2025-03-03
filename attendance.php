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

<div class="container2">
    <div class="search-sort">
        <h1>Attendance</h1>
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
    <button id="startScanner" class="add-btn">Start Scanner</button>
    
    <div id="scanner-container1">
        <div id="scanner"></div>
        <button id="stopScanner" class="add-btn">Stop Scanner</button>
    </div>

    <div class="table-container3">
        <table>
            <thead>
                <tr>
                    <th>Student Id<i class='bx bx-sort sort-icon'></i></th>
                    <th>First Name<i class='bx bx-sort sort-icon'></i></th>
                    <th>Last Name<i class='bx bx-sort sort-icon'></i></th>
                    <th>Department<i class='bx bx-sort sort-icon'></i></th>
                    <th>Year Level<i class='bx bx-sort sort-icon'></i></th>
                    <th>Entry Time<i class='bx bx-sort sort-icon'></i></th>
                </tr>
            </thead>
            <tbody id="attendanceTableBody">
                <?php
                date_default_timezone_set('Asia/Manila');
                $query = "SELECT user.student_id, user.first_name, user.last_name, user.department, user.year_level, 
                                 CONVERT_TZ(attendance.entry_time, '+00:00', '+07:00') AS entry_time 
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
                readers: ["code_128_reader"] // Specify the barcode type you want to scan
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

        if (code !== lastScannedCode || (currentTime - lastScannedTime) > 3000) { // 3 seconds debounce
            lastScannedCode = code;
            lastScannedTime = currentTime;
            console.log("Barcode detected and processed : [" + code + "]", data);
            // Play the scanner sound
            scannerSound.play().catch(error => {
                console.error('Error playing sound:', error);
            });
            // Process the scanned barcode data (e.g., mark attendance)
            markAttendance(code);
        }
    }

    function markAttendance(studentId) {
        // Send the scanned student ID to the server to mark attendance
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
                // Play the scanner sound again to confirm attendance
                scannerSound.play().catch(error => {
                    console.error('Error playing sound:', error);
                });
                // Update the attendance table without reloading the page
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td>${data.student_id}</td>
                    <td>${data.first_name}</td>
                    <td>${data.last_name}</td>
                    <td>${data.department}</td>
                    <td>${data.year_level}</td>
                    <td>${data.entry_time}</td>
                `;
                document.getElementById('attendanceTableBody').prepend(newRow);
            } else {
                alert('Error marking attendance: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function filterTable() {
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

    searchInput.addEventListener('input', filterTable);
    departmentFilter.addEventListener('change', filterTable);
});
</script>

</body>
</html>