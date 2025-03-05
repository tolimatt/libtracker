<?php
include 'db_config.php';

$departments = ['CAHS', 'CCJE', 'CEA', 'CELA', 'CITE', 'CMA', 'COL', 'SHS'];
$attendanceData = [];

foreach ($departments as $department) {
    $query = "SELECT COUNT(*) as attendance_count 
              FROM attendance 
              JOIN user ON attendance.student_id = user.id 
              WHERE user.department = '$department'";
    $result = $conn->query($query);
    $attendance_count = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $attendance_count = $row['attendance_count'];
    }
    $attendanceData[] = [
        'department' => $department,
        'attendance_count' => $attendance_count
    ];
}

header('Content-Type: application/json');
echo json_encode($attendanceData);
?>