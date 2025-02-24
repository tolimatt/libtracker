<?php
include 'db_config.php';

if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']); // Convert to integer for security
    $query = "SELECT * FROM user WHERE user_id = $user_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc()); // Convert result to JSON
    } else {
        echo json_encode(["error" => "User not found"]);
    }
} else {
    echo json_encode(["error" => "Invalid request"]);
}
?>