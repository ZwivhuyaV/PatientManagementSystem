<?php
include('../conn/conn.php');

if (isset($_GET['employee_id'])) {
    $employeeId = intval($_GET['employee_id']);

    $stmt = $conn->prepare("
        SELECT 
            e.tbl_employee_id, 
            e.name, 
            e.birthday, 
            e.gender, 
            e.phone, 
            e.email, 
            e.address, 
            d.department, 
            des.designation, 
            e.salary, 
            e.start_date, 
            e.employee_type, 
            e.status
        FROM tbl_employee e
        JOIN tbl_department d ON e.tbl_department_id = d.tbl_department_id
        JOIN tbl_designation des ON e.tbl_designation_id = des.tbl_designation_id
        WHERE e.tbl_employee_id = :employee_id
    ");
    $stmt->bindParam(':employee_id', $employeeId);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(['error' => 'Employee not found']);
    }
}
?>
