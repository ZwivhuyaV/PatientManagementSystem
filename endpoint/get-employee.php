<?php
include('../conn/conn.php');

if (isset($_GET['id'])) {
    $employeeId = intval($_GET['id']);

    $stmt = $conn->prepare("
        SELECT 
            tbl_employee_id, 
            name, 
            birthday, 
            gender, 
            phone, 
            email, 
            address, 
            tbl_designation_id, 
            tbl_department_id, 
            salary, 
            start_date, 
            employee_type 
        FROM tbl_employee 
        WHERE tbl_employee_id = :employeeId
    ");
    $stmt->bindParam(':employeeId', $employeeId, PDO::PARAM_INT);
    $stmt->execute();

    $employee = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($employee) {
        echo json_encode($employee);
    } else {
        echo json_encode(['error' => 'Employee not found']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>
