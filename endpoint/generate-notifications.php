<?php
include('./conn/conn.php');

// Generate appointment notifications for the current month
$stmt = $conn->prepare("
    INSERT INTO tbl_notifications (patient_id, type, notification_date)
    SELECT 
        tbl_patient_id,
        'appointment',
        NOW()
    FROM tbl_patient
    WHERE 
        next_appointment IS NOT NULL AND
        MONTH(next_appointment) = MONTH(CURDATE()) AND
        YEAR(next_appointment) = YEAR(CURDATE()) AND
        tbl_patient_id NOT IN (
            SELECT patient_id FROM tbl_notifications 
            WHERE type = 'appointment' AND 
            MONTH(notification_date) = MONTH(CURDATE()) AND
            YEAR(notification_date) = YEAR(CURDATE())
        )
    ON DUPLICATE KEY UPDATE is_read = FALSE
");
$stmt->execute();

// Generate birthday notifications for today
$stmt = $conn->prepare("
    INSERT INTO tbl_notifications (patient_id, type, notification_date)
    SELECT 
        tbl_patient_id,
        'birthday',
        NOW()
    FROM tbl_patient
    WHERE 
        MONTH(birthday) = MONTH(CURDATE()) AND
        DAY(birthday) = DAY(CURDATE()) AND
        tbl_patient_id NOT IN (
            SELECT patient_id FROM tbl_notifications 
            WHERE type = 'birthday' AND 
            DATE(notification_date) = CURDATE()
        )
    ON DUPLICATE KEY UPDATE is_read = FALSE
");
$stmt->execute();

echo "Notifications generated successfully";
?>