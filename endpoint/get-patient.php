<?php
include('../conn/conn.php');

$patientId = $_GET['id'];

try {
    $stmt = $conn->prepare("
        SELECT * FROM tbl_patient 
        WHERE tbl_patient_id = :patient_id
    ");
    $stmt->bindParam(':patient_id', $patientId);
    $stmt->execute();
    
    $patient = $stmt->fetch(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($patient);
} catch(PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
?>