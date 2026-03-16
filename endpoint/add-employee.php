<?php
include('../conn/conn.php'); // Include the database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $name = trim($_POST['name']);
    $birthday = trim($_POST['birthday']);
    $gender = trim($_POST['gender']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $tbl_designation_id = trim($_POST['tbl_designation_id']);
    $tbl_department_id = trim($_POST['tbl_department_id']);
    $salary = trim($_POST['salary']);
    $start_date = trim($_POST['start_date']);
    $employee_type = trim($_POST['employee_status']);

    // Validate required fields
    if (empty($name) || empty($birthday) || empty($gender) || empty($email) || empty($phone) || empty($address) || empty($tbl_designation_id) || empty($tbl_department_id) || empty($salary) || empty($start_date) || empty($employee_type)) {
        echo "
        <script>
            alert('Please fill in all the required fields.');
            window.location.href = 'http://localhost/employee-management-system/employee.php';
        </script>";
        exit;
    }

    try {
        // Insert employee data into the database
        $stmt = $conn->prepare("INSERT INTO `tbl_employee` 
            (`tbl_department_id`, `tbl_designation_id`, `name`, `birthday`, `gender`, `phone`, `email`, `address`, `salary`, `start_date`, `employee_type`, `status`) 
            VALUES 
            (:departmentId, :designationId, :name, :birthday, :gender, :phone, :email, :address, :salary, :startDate, :employeeType, 1)");
        
        $stmt->execute([
            'departmentId' => $tbl_department_id,
            'designationId' => $tbl_designation_id,
            'name' => $name,
            'birthday' => $birthday,
            'gender' => $gender,
            'phone' => $phone,
            'address' => $address,
            'email' => $email,
            'salary' => $salary,
            'startDate' => $start_date,
            'employeeType' => $employee_type
        ]);

        echo "
        <script>
            alert('Employee added successfully.');
            window.location.href = 'http://localhost/employee-management-system/employee.php';
        </script>";
    } catch (PDOException $e) {
        // Log and display the error
        error_log("Error: " . $e->getMessage());
        echo "
        <script>
            alert('An error occurred while adding the employee. Please try again.');
            window.location.href = 'http://localhost/employee-management-system/employee.php';
        </script>";
    }
} else {
    // Redirect if accessed without POST request
    echo "
    <script>
        alert('Invalid request method.');
        window.location.href = 'http://localhost/employee-management-system/employee.php';
    </script>";
}
?>
