<?php
include('../conn/conn.php');

$patientId = $_GET['patient_id'];

try {
    $stmt = $conn->prepare("
        DELETE FROM tbl_patient 
        WHERE tbl_patient_id = :patient_id
    ");
    $stmt->bindParam(':patient_id', $patientId);
    $stmt->execute();
    
    header('Location: /employee-management-system/employee.php?success=Patient deleted successfully');
} catch(PDOException $e) {
    header('Location: /employee-management-system/employeephp?error=' . urlencode($e->getMessage()));
}
?>