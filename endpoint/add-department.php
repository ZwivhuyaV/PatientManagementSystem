<?php
include('../conn/conn.php'); // Database connection

// Ensure the request is coming via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $department = trim($_POST['department']);

    if (empty($department)) {
        echo "
        <script>
            alert('Department cannot be empty.');
            window.location.href = 'http://localhost/employee-management-system/department.php';
        </script>";
        exit;
    }

    try {
        // Check if the department already exists in the database
        $stmt = $conn->prepare("SELECT `department` FROM `tbl_department` WHERE `department` = :department");
        $stmt->execute(['department' => $department]);
        $nameExist = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$nameExist) {
            // Start transaction
            $conn->beginTransaction();

            // Insert new department
            $insertStmt = $conn->prepare("
                INSERT INTO `tbl_department` 
                (`department`, `date_added`) 
                VALUES (:department, DATE_ADD(NOW(), INTERVAL 1 HOUR))
            ");
            $insertStmt->bindParam(':department', $department, PDO::PARAM_STR);
            $insertStmt->execute();

            // Commit transaction
            $conn->commit();

            echo "
            <script>
                alert('Department added successfully.');
                window.location.href = 'http://localhost/employee-management-system/department.php';
            </script>";
        } else {
            // Show error if department already exists
            echo "
            <script>
                alert('Department already exists.');
                window.location.href = 'http://localhost/employee-management-system/department.php';
            </script>";
        }
    } catch (PDOException $e) {
        // Roll back transaction in case of error
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }

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
