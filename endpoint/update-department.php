<?php
include('../conn/conn.php'); // Database connection

// Ensure the request is coming via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $departmentId = trim($_POST['departmentId']);
    $department = trim($_POST['department']);

    // Validate inputs
    if (empty($departmentId) || empty($department)) {
        echo "
        <script>
            alert('Both department ID and Name are required.');
            window.location.href = 'http://localhost/employee-management-system/department.php';
        </script>";
        exit;
    }

    try {
        // Check if the department exists in the database
        $stmt = $conn->prepare("SELECT `department` FROM `tbl_department` WHERE `tbl_department_id` = :departmentId");
        $stmt->execute(['departmentId' => $departmentId]);
        $departmentExists = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($departmentExists) {
            // Update the department
            $updateStmt = $conn->prepare("
                UPDATE `tbl_department`
                SET `department` = :department, `date_added` = DATE_ADD(NOW(), INTERVAL 1 HOUR)
                WHERE `tbl_department_id` = :departmentId
            ");
            $updateStmt->execute([
                'department' => $department,
                'departmentId' => $departmentId,
            ]);

            echo "
            <script>
                alert('Department updated successfully.');
                window.location.href = 'http://localhost/employee-management-system/department.php';
            </script>";
        } else {
            // Show error if department ID is not found
            echo "
            <script>
                alert('Department ID not found.');
                window.location.href = 'http://localhost/employee-management-system/department.php';
            </script>";
        }
    } catch (PDOException $e) {
        // Log and handle errors
        error_log("Error: " . $e->getMessage());
        echo "
        <script>
            alert('An error occurred. Please try again later.');
            window.location.href = 'http://localhost/employee-management-system/department.php';
        </script>";
    }
} else {
    // Handle incorrect request method
    echo "
    <script>
        alert('Invalid request method.');
        window.location.href = 'http://localhost/employee-management-system/department.php';
    </script>";
}
?>
