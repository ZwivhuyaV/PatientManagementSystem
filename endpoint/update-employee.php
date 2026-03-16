<?php
include('../conn/conn.php'); // Database connection

// Ensure the request is coming via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employeeId = trim($_POST['employeeId']);
    $name = trim($_POST['name']);
    $birthday = trim($_POST['birthday']);
    $gender = trim($_POST['gender']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $designationId = trim($_POST['tbl_designation_id']);
    $departmentId = trim($_POST['tbl_department_id']);
    $salary = trim($_POST['salary']);
    $startDate = trim($_POST['start_date']);
    $employeeStatus = trim($_POST['employee_status']);

    // Validate inputs
    if (empty($employeeId) || empty($name) || empty($birthday) || empty($gender) || empty($email) || empty($phone)) {
        echo "
        <script>
            alert('All required fields must be filled.');
            window.location.href = 'http://localhost/employee-management-system/employees.php';
        </script>";
        exit;
    }

    try {
        // Check if the employee exists in the database
        $stmt = $conn->prepare("SELECT `name` FROM `tbl_employee` WHERE `tbl_employee_id` = :employeeId");
        $stmt->execute(['employeeId' => $employeeId]);
        $employeeExists = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($employeeExists) {
            // Update the employee
            $updateStmt = $conn->prepare("
                UPDATE `tbl_employee`
                SET 
                    `name` = :name,
                    `birthday` = :birthday,
                    `gender` = :gender,
                    `email` = :email,
                    `phone` = :phone,
                    `address` = :address,
                    `tbl_designation_id` = :designationId,
                    `tbl_department_id` = :departmentId,
                    `salary` = :salary,
                    `start_date` = :startDate,
                    `employee_type` = :employeeStatus
                WHERE `tbl_employee_id` = :employeeId
            ");
            $updateStmt->execute([
                'name' => $name,
                'birthday' => $birthday,
                'gender' => $gender,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'designationId' => $designationId,
                'departmentId' => $departmentId,
                'salary' => $salary,
                'startDate' => $startDate,
                'employeeStatus' => $employeeStatus,
                'employeeId' => $employeeId
            ]);

            echo "
            <script>
                alert('Employee updated successfully.');
                window.location.href = 'http://localhost/employee-management-system/employees.php';
            </script>";
        } else {
            // Show error if employee ID is not found
            echo "
            <script>
                alert('Employee ID not found.');
                window.location.href = 'http://localhost/employee-management-system/employees.php';
            </script>";
        }
    } catch (PDOException $e) {
        // Log and handle errors
        error_log("Error: " . $e->getMessage());
        echo "
        <script>
            alert('An error occurred. Please try again later.');
            window.location.href = 'http://localhost/employee-management-system/employees.php';
        </script>";
    }
} else {
    // Handle incorrect request method
    echo "
    <script>
        alert('Invalid request method.');
        window.location.href = 'http://localhost/employee-management-system/employees.php';
    </script>";
}
?>
