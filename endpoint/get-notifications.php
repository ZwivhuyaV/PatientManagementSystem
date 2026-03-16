<?php
include('./conn/conn.php');

header('Content-Type: application/json');

$type = $_GET['type'] ?? null;

try {
    if ($type === 'appointment') {
        // Get upcoming appointments (within current month)
        $stmt = $conn->prepare("
            SELECT 
                p.tbl_patient_id as id,
                p.name,
                p.phone,
                p.next_appointment as date,
                IFNULL(n.is_read, 0) as is_read,
                DATE(p.next_appointment) = CURDATE() as is_today
            FROM tbl_patient p
            LEFT JOIN tbl_notifications n ON p.tbl_patient_id = n.patient_id AND n.type = 'appointment'
            WHERE 
                p.next_appointment IS NOT NULL AND
                MONTH(p.next_appointment) = MONTH(CURDATE()) AND
                YEAR(p.next_appointment) = YEAR(CURDATE())
            ORDER BY p.next_appointment ASC
        ");
    } else {
        // Get today's birthdays
        $stmt = $conn->prepare("
            SELECT 
                p.tbl_patient_id as id,
                p.name,
                p.phone,
                p.birthday as date,
                IFNULL(n.is_read, 0) as is_read,
                (YEAR(CURDATE()) - YEAR(p.birthday)) as age
            FROM tbl_patient p
            LEFT JOIN tbl_notifications n ON p.tbl_patient_id = n.patient_id AND n.type = 'birthday'
            WHERE 
                MONTH(p.birthday) = MONTH(CURDATE()) AND
                DAY(p.birthday) = DAY(CURDATE())
            ORDER BY p.name ASC
        ");
    }
    
    $stmt->execute();
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($notifications);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>