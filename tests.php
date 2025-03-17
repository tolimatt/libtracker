<?php
header('Content-Type: application/json');

$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$dbase_name = "libtrack";

$connect = mysqli_connect($db_server, $db_user, $db_pass, $dbase_name);

if ($connect->connect_error) {
    die(json_encode(array('error' => 'Database connection failed')));
}

$studentId = $_GET['student_id']; // Retrieve student_id from GET parameters

$sql = "SELECT first_name, department, status FROM user WHERE student_id = ?";
$stmt = $connect->prepare($sql);
$stmt->bind_param("s", $studentId); // Use "s" for string
$stmt->execute();
$result = $stmt->get_result();

$response = array();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $response['firstName'] = $row['first_name'];
    $response['department'] = $row['department'];
    $response['status'] = $row['status'];
} else {
    $response['error'] = 'Account not found';
}

echo json_encode($response);

$stmt->close();
$connect->close();
?>