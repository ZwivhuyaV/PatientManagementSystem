<?php
header('Content-Type: application/json');
include('../conn/conn.php');

// Get start and end dates for current month
$firstDayOfMonth = date('Y-m-01');
$lastDayOfMonth = date('Y-m-t');
$today = date('Y-m-d');

$stmt = $conn->prepare("
    SELECT 
        p.tbl_patient_id as id,
        p.name,
        p.phone,
        p.whatsapp_number as whatsapp,
        p.next_appointment as date
    FROM tbl_patient p
    WHERE 
        p.next_appointment BETWEEN :firstDay AND :lastDay
    ORDER BY p.next_appointment ASC
");

$stmt->execute([
    ':firstDay' => $firstDayOfMonth,
    ':lastDay' => $lastDayOfMonth
]);

$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mark today's appointments
foreach ($appointments as &$appt) {
    $apptDate = date('Y-m-d', strtotime($appt['date']));
    $appt['is_today'] = ($apptDate === $today);
}

echo json_encode($appointments);
?>