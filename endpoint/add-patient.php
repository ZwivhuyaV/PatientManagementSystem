<?php
include('../conn/conn.php');

// Get form data
$accountNumber = $_POST['accountNumber'];
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
        INSERT INTO tbl_patient (
            name, 
            birthday, 
            gender, 
            blood_type, 
            phone, 
            whatsapp_number, 
            email, 
            address, 
            next_appointment,
            medical_notes
        ) VALUES (

            :name, 
            :birthday, 
            :gender, 
            :blood_type, 
            :phone, 
            :whatsapp, 
            :email, 
            :address, 
            :next_appointment,
            :medical_notes
        )
    ");
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
    
    header('Location: /employee-management-system/employee.php?success=Patient added successfully');
} catch(PDOException $e) {
    header('Location: /employee-management-system/employee.php?error=' . urlencode($e->getMessage()));
}
?>