<?php
include 'db_config.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['transaction_id'], $data['action'])) {
    $transaction_id = $data['transaction_id'];
    $action = $data['action'];

    if ($action === 'return') {
        $sql = "UPDATE borrowed_books SET return_status = 'Returned', return_date = NOW() WHERE transaction_id = $transaction_id";
    } elseif ($action === 'renew') {
        $sql = "UPDATE borrowed_books SET renewal_status = 'Renewed' WHERE transaction_id = $transaction_id";
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        exit();
    }

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating status: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
}
?>