<?php
include('../conn/conn.php'); // Database connection

// Check if employee ID is passed as a GET parameter
if (isset($_GET['employee']) && !empty($_GET['employee'])) {
    $employeeId = trim($_GET['employee']);

    try {
        // Check if the employee exists in the database
        $stmt = $conn->prepare("SELECT `employee` FROM `tbl_employee` WHERE `tbl_employee_id` = :employeeId");
        $stmt->execute(['employeeId' => $employeeId]);
        $employeeExists = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($employeeExists) {
            // Delete the employee from the database
            $deleteStmt = $conn->prepare("DELETE FROM `tbl_employee` WHERE `tbl_employee_id` = :employeeId");
            $deleteStmt->execute(['employeeId' => $employeeId]);

            echo "
            <script>
                alert('Employee deleted successfully.');
                window.location.href = 'http://localhost/employee-management-system/employee.php';
            </script>";
        } else {
            // If the employee ID does not exist
            echo "
            <script>
                alert('Employee ID not found.');
                window.location.href = 'http://localhost/employee-management-system/employee.php';
            </script>";
        }
    } catch (PDOException $e) {
        // Log and handle errors
        error_log("Error: " . $e->getMessage());
        echo "
        <script>
            alert('An error occurred. Please try again later.');
            window.location.href = 'http://localhost/employee-management-system/employee.php';
        </script>";
    }
} else {
    // If no employee ID is passed
    echo "
    <script>
        alert('Invalid employee ID.');
        window.location.href = 'http://localhost/employee-management-system/employee.php';
    </script>";
}
?>
