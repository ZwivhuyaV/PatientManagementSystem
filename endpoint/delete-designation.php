<?php
include('../conn/conn.php'); // Database connection

// Check if designation ID is passed as a GET parameter
if (isset($_GET['designation']) && !empty($_GET['designation'])) {
    $designationId = trim($_GET['designation']);

    try {
        // Check if the designation exists in the database
        $stmt = $conn->prepare("SELECT `designation` FROM `tbl_designation` WHERE `tbl_designation_id` = :designationId");
        $stmt->execute(['designationId' => $designationId]);
        $designationExists = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($designationExists) {
            // Delete the designation from the database
            $deleteStmt = $conn->prepare("DELETE FROM `tbl_designation` WHERE `tbl_designation_id` = :designationId");
            $deleteStmt->execute(['designationId' => $designationId]);

            echo "
            <script>
                alert('Designation deleted successfully.');
                window.location.href = 'http://localhost/employee-management-system/designation.php';
            </script>";
        } else {
            // If the designation ID does not exist
            echo "
            <script>
                alert('Designation ID not found.');
                window.location.href = 'http://localhost/employee-management-system/designation.php';
            </script>";
        }
    } catch (PDOException $e) {
        // Log and handle errors
        error_log("Error: " . $e->getMessage());
        echo "
        <script>
            alert('An error occurred. Please try again later.');
            window.location.href = 'http://localhost/employee-management-system/designation.php';
        </script>";
    }
} else {
    // If no designation ID is passed
    echo "
    <script>
        alert('Invalid designation ID.');
        window.location.href = 'http://localhost/employee-management-system/designation.php';
    </script>";
}
?>
