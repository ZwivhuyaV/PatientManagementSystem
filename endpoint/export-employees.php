<?php
include('../conn/conn.php'); // Database connection

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=employees.csv');

$output = fopen('php://output', 'w');

// Add the header row
fputcsv($output, [
    'Employee ID', 'Name', 'Birthday', 'Gender', 'Email', 
    'Phone', 'Address', 'Designation', 'Department', 
    'Salary', 'Start Date', 'Employee Type'
]);

try {
    // Fetch all employee details
    $query = "
        SELECT 
            e.tbl_employee_id AS employee_id, 
            e.name, 
            e.birthday, 
            e.gender, 
            e.email, 
            e.phone, 
            e.address, 
            d.designation AS designation,
            dp.department AS department, 
            e.salary, 
            e.start_date, 
            e.employee_type
        FROM 
            tbl_employee e
        LEFT JOIN 
            tbl_designation d ON e.tbl_designation_id = d.tbl_designation_id
        LEFT JOIN 
            tbl_department dp ON e.tbl_department_id = dp.tbl_department_id
        ORDER BY 
            e.tbl_employee_id ASC
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    // Write each employee's data to the CSV
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, $row);
    }
} catch (PDOException $e) {
    error_log("Error exporting employees: " . $e->getMessage());
    echo "<script>
        alert('An error occurred while exporting employees.');
        window.location.href = 'http://localhost/employee-management-system/employees.php';
    </script>";
    exit;
}

fclose($output);
?>
