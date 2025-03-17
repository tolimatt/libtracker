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
            $department = $data['department'];

            // Check for duplicate student ID
            $stmt_check = $connect->prepare("SELECT student_id FROM user WHERE student_id = ?");
            $stmt_check->bind_param("s", $studentId);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows > 0) {
                http_response_code(409); // Conflict (duplicate)
                $response = array("status" => "error", "message" => "Student ID already exists");
            } else {
                $stmt = $connect->prepare("INSERT INTO user (first_name, last_name, student_id, password, year_level, program, phinmaed_email, contact_number, department) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssssss", $firstName, $lastName, $studentId, $password, $yearLevel, $program, $schoolEmail, $contactNumber, $department);

                if ($stmt->execute()) {
                    $response = array("status" => "success");
                } else {
                    http_response_code(500);
                    $error_message = $stmt->error;
                    $response = array("status" => "error", "message" => "Database error: " . $error_message);
                }
                $stmt->close();
            }
            $stmt_check->close();
        } else {
            http_response_code(400);
            $response = array("status" => "error", "message" => "Invalid request method");
        }
    } else {
        http_response_code(500);
        $response = array("status" => "error", "message" => "Database connection failed");
    }
} catch (Exception $e) {
    http_response_code(500);
    $response = array("status" => "error", "message" => "An error occurred: " . $e->getMessage());
} finally {
    echo json_encode($response);
    mysqli_close($connect);
}
?>