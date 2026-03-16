<?php
include('../conn/conn.php'); // Database connection

// Ensure the request is coming via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $designation = trim($_POST['designation']);

    if (empty($designation)) {
        echo "
        <script>
            alert('Designation cannot be empty.');
            window.location.href = 'http://localhost/employee-management-system/designation.php';
        </script>";
        exit;
    }

    try {
        // Check if the designation already exists in the database
        $stmt = $conn->prepare("SELECT `designation` FROM `tbl_designation` WHERE `designation` = :designation");
        $stmt->execute(['designation' => $designation]);
        $nameExist = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$nameExist) {
            // Start transaction
            $conn->beginTransaction();

            // Insert new designation
            $insertStmt = $conn->prepare("
                INSERT INTO `tbl_designation` 
                (`designation`, `date_added`) 
                VALUES (:designation, DATE_ADD(NOW(), INTERVAL 1 HOUR))
            ");
            $insertStmt->bindParam(':designation', $designation, PDO::PARAM_STR);
            $insertStmt->execute();

            // Commit transaction
            $conn->commit();

            echo "
            <script>
                alert('Designation added successfully.');
                window.location.href = 'http://localhost/employee-management-system/designation.php';
            </script>";
        } else {
            // Show error if designation already exists
            echo "
            <script>
                alert('Designation already exists.');
                window.location.href = 'http://localhost/employee-management-system/designation.php';
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
            window.location.href = 'http://localhost/employee-management-system/designation.php';
        </script>";
    }
} else {
    // Handle incorrect request method
    echo "
    <script>
        alert('Invalid request method.');
        window.location.href = 'http://localhost/employee-management-system/designation.php';
    </script>";
}
?>
