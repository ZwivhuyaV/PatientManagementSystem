<?php
include('./conn/conn.php');

header('Content-Type: application/json');

$type = $_GET['type'] ?? null;

if (!$type || !in_array($type, ['appointment', 'birthday'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid notification type']);
    exit;
}

try {
    // Mark all notifications of this type as read
    $stmt = $conn->prepare("
        INSERT INTO tbl_notifications (patient_id, type, is_read, notification_date)
        SELECT tbl_patient_id, :type, TRUE, NOW()
        FROM tbl_patient
        WHERE 
            (:type = 'appointment' AND next_appointment IS NOT NULL) OR
            (:type = 'birthday' AND MONTH(birthday) = MONTH(CURDATE()) AND DAY(birthday) = DAY(CURDATE()))
        ON DUPLICATE KEY UPDATE is_read = TRUE
    ");
    $stmt->bindParam(':type', $type);
    $stmt->execute();

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>