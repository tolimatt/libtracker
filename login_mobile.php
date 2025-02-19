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
            
            $studentid = $data['student_id'];
            $password = $data['password'];

            $stmt = $connect->prepare("SELECT * FROM user WHERE student_id = ?"); // Check if user exists
            $stmt->bind_param("s", $studentid);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) { // User found
                $row = $result->fetch_assoc();
                // Verify the password (use password_verify if you're hashing passwords)
                if ($password == $row['password']) { // Replace with password_verify for production
                    $response = array("status" => "success");
                    echo json_encode($response);
                } else {
                    http_response_code(401); // Unauthorized
                    $response = array("status" => "error", "message" => "Incorrect password");
                    echo json_encode($response);
                }
            } else {
                http_response_code(401); // Unauthorized
                $response = array("status" => "error", "message" => "User not found");
                echo json_encode($response);
            }

            $stmt->close();
        } else {
            http_response_code(400); // Bad Request
            $response = array("status" => "error", "message" => "Missing required fields (studentid or password)");
            echo json_encode($response);
            exit; // Important: Stop execution after sending the error
        }
    } else {
        // ... (rest of the PHP code remains the same)
    }

} catch (Exception $e) {
    
}

mysqli_close($connect);
?>