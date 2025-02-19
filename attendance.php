<?php include 'db_config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="attendance1.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <title>Attendance Management</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
</head>
<body>

<div class="container1">
    <div class="search-sort">
        <h1>Attendance</h1>
        <input type="text" id="search" placeholder="Search...">
        <button class="filter-btn"><i class='bx bx-filter-alt'></i> Filter</button>
        <button class="sort-btn"><i class='bx bx-sort'></i> Sort</button>
    </div>
    <button id="startScanner" class="add-btn">Start Scanner</button>
    
    <div id="scanner-container">
        <div id="scanner"></div>
        <button id="stopScanner" class="add-btn">Stop Scanner</button>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Student Id</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Year Level</th>
                    <th>Entry Time</th>
                    <th>Exit Time</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT user.student_id, user.name, user.department, user.year_level, 
                                 attendance.entry_time, attendance.exit_time 
                          FROM attendance 
                          INNER JOIN user ON attendance.student_id = user.student_id 
                          ORDER BY attendance.entry_time DESC";

                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['student_id']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['department']}</td>
                                <td>{$row['year_level']}</td>
                                <td>{$row['entry_time']}</td>
                                <td>" . ($row['exit_time'] ? $row['exit_time'] : 'Still Inside') . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No Attendance Records Found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startScannerButton = document.getElementById('startScanner');
    const stopScannerButton = document.getElementById('stopScanner');
    const scannerContainer = document.getElementById('scanner-container');
    const container = document.querySelector('.container1');
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
                    width: 300,
                    height: 300,
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
                alert('Attendance marked successfully!');
                location.reload(); // Reload the page to update the attendance table
            } else {
                alert('Error marking attendance: ' + data.message);
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