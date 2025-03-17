<?php
header('Content-Type: application/json');

$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$dbase_name = "libtrack";

$connect = mysqli_connect($db_server, $db_user, $db_pass, $dbase_name);

if ($connect) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);
        
        $studentId = $data['studentId'];
        $password = $data['password'];

        $stmt = $connect->prepare("SELECT * FROM user WHERE student_id = ?");
        $stmt->bind_param("s", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if ($password == $row['password']) {
                echo json_encode(array("status" => "success"));
            } else {
                // Incorrect password
                echo json_encode(array("status" => "incorrect_password"));
            }
        } else {
            // Student ID not found
            echo json_encode(array("status" => "account_not_found"));
        }

        $stmt->close();
    } else {
        echo json_encode(array("status" => "invalid_request"));
    }
} else {
    echo json_encode(array("status" => "database_error"));
}

mysqli_close($connect);
?>