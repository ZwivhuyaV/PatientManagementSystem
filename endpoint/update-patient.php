<?php
include('../conn/conn.php');

// Get form data
$patientId = $_POST['patient_id'];
$name = $_POST['name'];
$birthday = $_POST['birthday'] ?: null;
$gender = $_POST['gender'] ?: null;
$bloodType = $_POST['blood_type'] ?: null;
$phone = $_POST['phone'];
$whatsapp = $_POST['whatsapp_number'] ?: null;
$email = $_POST['email'] ?: null;
$address = $_POST['address'] ?: null;
$nextAppointment = $_POST['next_appointment'] ?: null;
$medicalNotes = $_POST['medical_notes'] ?: null;

try {
    $stmt = $conn->prepare("
        UPDATE tbl_patient SET
            name = :name,
            birthday = :birthday,
            gender = :gender,
            blood_type = :blood_type,
            phone = :phone,
            whatsapp_number = :whatsapp,
            email = :email,
            address = :address,
            next_appointment = :next_appointment,
            medical_notes = :medical_notes
        WHERE tbl_patient_id = :patient_id
    ");
    
    $stmt->bindParam(':patient_id', $patientId);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':birthday', $birthday);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':blood_type', $bloodType);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':whatsapp', $whatsapp);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':next_appointment', $nextAppointment);
    $stmt->bindParam(':medical_notes', $medicalNotes);
    
    $stmt->execute();
    
    header('Location: /employee-management-system/employee.php?success=Patient updated successfully');
} catch(PDOException $e) {
    header('Location: /employee-management-system/employee.php?error=' . urlencode($e->getMessage()));
}
?>