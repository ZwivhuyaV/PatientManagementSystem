<?php
include('../conn/conn.php'); // Database connection

// Ensure the request is coming via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $designationId = trim($_POST['designationId']);
    $designation = trim($_POST['designation']);

    // Validate inputs
    if (empty($designationId) || empty($designation)) {
        echo "
        <script>
            alert('Both Designation ID and Name are required.');
            window.location.href = 'http://localhost/employee-management-system/designation.php';
        </script>";
        exit;
    }

    try {
        // Check if the designation exists in the database
        $stmt = $conn->prepare("SELECT `designation` FROM `tbl_designation` WHERE `tbl_designation_id` = :designationId");
        $stmt->execute(['designationId' => $designationId]);
        $designationExists = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($designationExists) {
            // Update the designation
            $updateStmt = $conn->prepare("
                UPDATE `tbl_designation`
                SET `designation` = :designation, `date_added` = DATE_ADD(NOW(), INTERVAL 1 HOUR)
                WHERE `tbl_designation_id` = :designationId
            ");
            $updateStmt->execute([
                'designation' => $designation,
                'designationId' => $designationId,
            ]);

            echo "
            <script>
                alert('Designation updated successfully.');
                window.location.href = 'http://localhost/employee-management-system/designation.php';
            </script>";
        } else {
            // Show error if designation ID is not found
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
    // Handle incorrect request method
    echo "
    <script>
        alert('Invalid request method.');
        window.location.href = 'http://localhost/employee-management-system/designation.php';
    </script>";
}
?>
