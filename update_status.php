<?php
include 'db_config.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$transaction_id = $data['transaction_id'];
$action = $data['action'];

if ($action === 'return' || $action === 'set_returned') {
    $query = "UPDATE borrow SET status = 'Returned' WHERE transaction_id = ?";
} elseif ($action === 'renew') {
    $query = "UPDATE borrow SET status = 'Renewed' WHERE transaction_id = ?";
} else {
    echo json_encode(["success" => false, "message" => "Invalid action"]);
    exit;
}

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $transaction_id);
if ($stmt->execute()) {
    if ($action === 'return') {
        // Increment copies_available in the books table
        $incrementQuery = "UPDATE books SET copies_available = copies_available + 1 WHERE book_code = (SELECT book_code FROM borrow WHERE transaction_id = ?)";
        $incrementStmt = $conn->prepare($incrementQuery);
        $incrementStmt->bind_param("i", $transaction_id);
        $incrementStmt->execute();
    }

    // Fetch the updated row data
    $fetchQuery = "SELECT transaction_id, status FROM borrow WHERE transaction_id = ?";
    $stmt = $conn->prepare($fetchQuery);
    $stmt->bind_param("i", $transaction_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $updatedRow = $result->fetch_assoc();

    echo json_encode(["success" => true, "updatedRow" => $updatedRow]);
} else {
    echo json_encode(["success" => false, "message" => "Database update failed"]);
}

$stmt->close();
$conn->close();
?>
