<?php
header('Content-Type: application/json');

$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$dbase_name = "libtrack";

try {
    $connect = mysqli_connect($db_server, $db_user, $db_pass, $dbase_name);

    if ($connect) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            $firstName = $data['firstName'];
            $lastName = $data['lastName'];
            $studentId = $data['studentId'];
            $password = $data['password'];
            $yearLevel = $data['yearLevel'];
            $program = $data['program'];
            $schoolEmail = $data['schoolEmail'];
            $contactNumber = $data['contactNumber'];

            $stmt = $connect->prepare("INSERT INTO user (
                first_name, last_name, student_id, password, year_level, department, phinmaed_email, contact_number) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssss", $firstName, $lastName, $studentId, $password, $yearLevel, $program, $schoolEmail, $contactNumber);

            if ($stmt->execute()) {
                $response = array("status" => "User inserted successfully");
                echo json_encode($response);
            } else {
                http_response_code(500);
                $error_message = $stmt->error;
                $response = array("status" => "error", "message" => "Database insertion failed: " . $error_message);
                echo json_encode($response);
            }

            $stmt->close();
        } else {
            http_response_code(400); // Bad Request
            $response = array("status" => "error", "message" => "Invalid request method");
            echo json_encode($response);
        }
    } else {
        http_response_code(500);
        $response = array("status" => "error", "message" => "Database connection failed");
        echo json_encode($response);
    }

} catch (Exception $e) {
    http_response_code(500);
    $response = array("status" => "error", "message" => "An error occurred: " . $e->getMessage());
    echo json_encode($response);
}

mysqli_close($connect);
?>