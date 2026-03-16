<?php
include('./conn/conn.php');

header('Content-Type: application/json');

// Get the input data
$input = json_decode(file_get_contents('php://input'), true);
$patientId = $input['patient_id'] ?? null;
$type = $input['type'] ?? null;

if (!$patientId || !$type) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required parameters']);
    exit;
}

try {
    // Mark notification as read
    $stmt = $conn->prepare("
        INSERT INTO tbl_notifications (patient_id, type, is_read, notification_date)
        VALUES (:patient_id, :type, TRUE, NOW())
        ON DUPLICATE KEY UPDATE is_read = TRUE
    ");
    $stmt->bindParam(':patient_id', $patientId);
    $stmt->bindParam(':type', $type);
    $stmt->execute();

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>