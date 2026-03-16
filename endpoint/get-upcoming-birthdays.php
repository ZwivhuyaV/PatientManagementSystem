<?php
header('Content-Type: application/json');
include('../conn/conn.php');

// Get birthdays happening today
$today = date('m-d');

$stmt = $conn->prepare("
    SELECT 
        tbl_patient_id as id,
        name,
        phone,
        whatsapp_number as whatsapp,
        birthday as date
    FROM tbl_patient
    WHERE 
        DATE_FORMAT(birthday, '%m-%d') = :today
    ORDER BY name ASC
");

$stmt->execute([':today' => $today]);
$birthdays = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate ages
foreach ($birthdays as &$bday) {
    $birthday = new DateTime($bday['date']);
    $today = new DateTime();
    $age = $today->diff($birthday)->y;
    $bday['age'] = $age; 
}

echo json_encode($birthdays);
?>