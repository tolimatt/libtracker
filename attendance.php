<?php include 'db_config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="attendance1.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <title>Attendance Management</title>
</head>
<body>

<div class="container2">
    <div class="search-sort">
        <h1>Attendance</h1>
        <input type="text" id="search" placeholder="Search...">
        <button class="filter-btn"><i class='bx bx-filter-alt'></i> Filter</button>
        <button class="sort-btn"><i class='bx bx-sort'></i> Sort</button>
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
                    <th>Student Id</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Department</th>
                    <th>Year Level</th>
                    <th>Entry Time</th>
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
});
</script>

</body>
</html>