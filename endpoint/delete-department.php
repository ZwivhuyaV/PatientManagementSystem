<?php
include('../conn/conn.php'); // Database connection

// Check if department ID is passed as a GET parameter
if (isset($_GET['department']) && !empty($_GET['department'])) {
    $departmentId = trim($_GET['department']);

    try {
        // Check if the department exists in the database
        $stmt = $conn->prepare("SELECT `department` FROM `tbl_department` WHERE `tbl_department_id` = :departmentId");
        $stmt->execute(['departmentId' => $departmentId]);
        $departmentExists = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($departmentExists) {
            // Delete the department from the database
            $deleteStmt = $conn->prepare("DELETE FROM `tbl_department` WHERE `tbl_department_id` = :departmentId");
            $deleteStmt->execute(['departmentId' => $departmentId]);

            echo "
            <script>
                alert('Department deleted successfully.');
                window.location.href = 'http://localhost/employee-management-system/department.php';
            </script>";
        } else {
            // If the department ID does not exist
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
    // If no department ID is passed
    echo "
    <script>
        alert('Invalid department ID.');
        window.location.href = 'http://localhost/employee-management-system/department.php';
    </script>";
}
?>
