<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Data Table -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }
        nav {
            width: 100%;
        }
        .container {
            background: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
            height: 600px;
            margin-top: 3%;
            width: 95%;
            margin-bottom: 20px;
        }
        .header-container {
            display: flex;
            justify-content: space-between;
            width: 100%;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .header-container > h3 {
            font-weight: 500;
        }
        .patient-container {
            position: relative;
            width: 100%;
            height: 80%;
            overflow-y: auto;
        }
        .action-button {
            display: flex;
            justify-content: center;
        }
        .action-button > button {
            width: 25px;
            height: 25px;
            font-size: 17px;
            display: flex !important;
            justify-content: center;
            align-items: center;
            margin: 0px 2px;
        }
        .month-filter {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .month-filter .form-select {
            width: 200px;
        }
        /* Notification styles */
        .notification-dropdown {
            max-height: 400px;
            overflow-y: auto;
            width: 350px;
            padding: 0;
        }
        .notification-item {
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .notification-item:hover {
            background-color: #f8f9fa;
        }
        .notification-icon {
            margin-right: 10px;
            font-size: 1.2rem;
            min-width: 20px;
        }
        .appointment-notification .notification-icon {
            color: #0d6efd;
        }
        .birthday-notification .notification-icon {
            color: #dc3545;
        }
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.7rem;
        }
        .dropdown-header {
            position: sticky;
            top: 0;
            background: white;
            z-index: 1;
            padding-bottom: 10px;
            margin-bottom: 5px;
            border-bottom: 1px solid #dee2e6;
        }
        .notification-time {
            font-size: 0.8rem;
            color: #6c757d;
        }
        .no-notifications {
            padding: 15px;
            text-align: center;
            color: #6c757d;
        }
        .today-appointment {
            background-color: #e7f1ff;
            border-left: 3px solid #0d6efd;
        }
        .notification-item.unread {
            background-color: #f8f9fa;
        }
        .notification-item.read {
            opacity: 0.8;
        }
        .clear-notifications-btn {
            font-size: 0.8rem;
            padding: 0;
        }
        .notification-container {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark p-3">
        <a class="navbar-brand" href="employee.php" style="margin-left: 20px;">Patient Management System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarScroll">
            <ul class="navbar-nav mr-auto my-2 my-lg-0 navbar-nav-scroll">
                <li class="nav-item">
                    <a class="nav-link active" href="employee.php">Patients</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" href="appointments.php">Appointments</a>
                </li> -->
            </ul>
            <ul class="navbar-nav mr-auto my-2 my-lg-0 navbar-nav-scroll" style="max-height: 100px; margin-left: auto;">
                <!-- Appointments Notification Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="appointmentDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-calendar-check"></i>
                        <span class="position-relative">
                            <span id="appointmentBadge" class="badge bg-primary notification-badge d-none">0</span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="appointmentDropdown">
                        <li>
                            <div class="d-flex justify-content-between dropdown-header">
                                <h6>This Month's Appointments</h6>
                                <button class="btn btn-sm btn-link text-danger clear-notifications-btn" id="clearAppointmentNotifications">
                                    Clear All
                                </button>
                            </div>
                        </li>
                        <div class="notification-container" id="appointmentNotifications">
                            <li class="text-center py-3">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </li>
                        </div>
                        <li><hr class="dropdown-divider"></li>
                        <!-- <li><a class="dropdown-item text-center" href="appointments.php">View All Appointments</a></li> -->
                    </ul>
                </li>
                
                <!-- Birthdays Notification Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="birthdayDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-birthday-cake"></i>
                        <span class="position-relative">
                            <span id="birthdayBadge" class="badge bg-danger notification-badge d-none">0</span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="birthdayDropdown">
                        <li>
                            <div class="d-flex justify-content-between dropdown-header">
                                <h6>Today's Birthdays</h6>
                                <button class="btn btn-sm btn-link text-danger clear-notifications-btn" id="clearBirthdayNotifications">
                                    Clear All
                                </button>
                            </div>
                        </li>
                        <div class="notification-container" id="birthdayNotifications">
                            <li class="text-center py-3">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </li>
                        </div>
                        <li><hr class="dropdown-divider"></li>
                        <!-- <li><a class="dropdown-item text-center" href="employee.php">View All Patients</a></li> -->
                    </ul>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <main class="container">
        <div class="header-container">
            <h3>Patient Records</h3>
            <div class="header-buttons d-flex align-items-center gap-2">
            <div class="btn-group">
    <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-file-pdf"></i> Export PDF
    </button>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item"  id="exportPdfBtn">Export by Appointment Month</a></li>
        <li><a class="dropdown-item" id="exportBirthdaysBtn">Export by Birthday Month</a></li>
    </ul>
</div>
                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addPatientModal">
                    Add Patient
                </button>

                <div class="month-filter">
                    <label for="monthFilter" class="form-label">Filter by Month:</label>
                    <select class="form-select" id="monthFilter">
                        <option value="all">All Months</option>
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </div>
                
    <div class="month-filter">
        <label for="birthdayMonthFilter" class="form-label">Birthday Month:</label>
        <select class="form-select" id="birthdayMonthFilter">
            <option value="all">All Months</option>
            <option value="1">January</option>
            <option value="2">February</option>
            <option value="3">March</option>
            <option value="4">April</option>
            <option value="5">May</option>
            <option value="6">June</option>
            <option value="7">July</option>
            <option value="8">August</option>
            <option value="9">September</option>
            <option value="10">October</option>
            <option value="11">November</option>
            <option value="12">December</option>
        </select>
    </div>
</div>
            </div>
        </div>

        <div class="patient-container">
            <table class="table table-striped table-hover table-sm" id="patientTable">
                <thead>
                    <tr>
                        <th scope="col">Account Number</th>
                        <th scope="col">Full Name</th>
                        <th scope="col">Contact</th>
                        <th scope="col">WhatsApp</th>
                        <th scope="col">Next Appointment</th>
                        <th scope="col">Date of Birth</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>    
                <tbody>
                    <?php
                    include('./conn/conn.php');

                    $stmt = $conn->prepare("
                        SELECT 
                            tbl_patient_id AS patientId,
                            address AS address,
                            name AS fullName, 
                            phone,
                            whatsapp_number AS whatsapp,
                            next_appointment,
                            birthday,
                            date_added AS dateAdded
                        FROM tbl_patient
                        ORDER BY next_appointment DESC
                    ");
                    $stmt->execute();
                    $result = $stmt->fetchAll();

                    foreach ($result as $row) {
                        $address = $row['address'];
                        $patientId = $row['patientId'];
                        $fullName = $row['fullName'];
                        $phone = $row['phone'] ?? 'N/A';
                        $whatsapp = $row['whatsapp'] ?? 'N/A';
                        $nextAppointment = $row['next_appointment'] ? date('d-M-Y', strtotime($row['next_appointment'])) : 'Not Scheduled';
                        $birthday = $row['birthday'] ? date('d-M-Y', strtotime($row['birthday'])) : 'N/A';
                        $appointmentMonth = $row['next_appointment'] ? date('n', strtotime($row['next_appointment'])) : '';
                        ?>
                        <tr data-appointment-month="<?= $appointmentMonth ?>" data-patient-id="<?= $patientId ?>">
                            <td><?= $address ?></td>
                            <td><?= htmlspecialchars($fullName) ?></td>
                            <td><?= htmlspecialchars($phone) ?></td>
                            <td><?= htmlspecialchars($whatsapp) ?></td>
                            <td><?= $nextAppointment ?></td>
                            <td><?= $birthday ?></td>
                            <td>
                                <div class="action-button">
                                    <button class="btn btn-primary" onclick="viewPatient(<?= $patientId ?>)">&#128065;</button>
                                    <button class="btn btn-success" onclick="updatePatient(<?= $patientId ?>)">&#128393;</button>
                                    <button class="btn btn-danger" onclick="deletePatient(<?= $patientId ?>)">X</button>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Add Patient Modal -->
    <div class="modal fade" id="addPatientModal" tabindex="-1" aria-labelledby="addPatientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content mt-5">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPatientModalLabel">Add New Patient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/employee-management-system/endpoint/add-patient.php" method="POST" id="patientForm">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Full Name:*</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group row mb-3">
                            <div class="col-md-4">
                                <label for="birthday" class="form-label">Date of Birth:</label>
                                <input type="date" class="form-control" id="birthday" name="birthday">
                            </div>
                            <div class="col-md-4">
                                <label for="gender" class="form-label">Gender:</label>
                                <select class="form-control" id="gender" name="gender">
                                    <option value="">Select</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="address" class="form-label">Account Number:</label>
                                <input class="form-control" rows="2" name="address" id="address">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number:</label>
                                <input type="tel" class="form-control" id="phone" name="phone" >
                            </div>
                            <div class="col-md-6">
                                <label for="whatsapp" class="form-label">WhatsApp Number:*</label>
                                <input type="tel" class="form-control" id="whatsapp" name="whatsapp_number" required>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email Address:</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <!-- <div class="form-group mb-3">
                            <label for="address" class="form-label">Account Number:</label>
                            <textarea class="form-control" rows="2" name="address" id="address"></textarea>
                        </div> -->
                        <div class="form-group row mb-3">
                            <div class="col-md-6">
                                <label for="nextAppointment" class="form-label">Next Appointment:</label>
                                <input type="date" class="form-control" id="nextAppointment" name="next_appointment">
                            </div>
                            <div class="col-md-6">
                                <label for="medicalNotes" class="form-label">Notes:</label>
                                <textarea class="form-control" rows="1" name="medical_notes" id="medicalNotes"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add Patient</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Patient Details Modal -->
    <div class="modal fade" id="patientDetailsModal" tabindex="-1" aria-labelledby="patientDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="patientDetailsModalLabel">Patient Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th width="30%">Account Number</th>
                                <td id="modal-address"></td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td id="modal-name"></td>
                            </tr>
                            <tr>
                                <th>Date of Birth</th>
                                <td id="modal-birthday"></td>
                            </tr>
                            <tr>
                                <th>Gender</th>
                                <td id="modal-gender"></td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td id="modal-phone"></td>
                            </tr>
                            <tr>
                                <th>WhatsApp</th>
                                <td id="modal-whatsapp"></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td id="modal-email"></td>
                            </tr>

                            <tr>
                                <th>Date Added</th>
                                <td id="modal-date-added"></td>
                            </tr>
                            <tr>
                                <th>Next Appointment</th>
                                <td id="modal-next-appointment"></td>
                            </tr>
                            <tr>
                                <th>Notes</th>
                                <td id="modal-medical-notes"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Patient Modal -->
    <div class="modal fade" id="updatePatientModal" tabindex="-1" aria-labelledby="updatePatientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content mt-5">
                <div class="modal-header">
                    <h5 class="modal-title" id="updatePatientModalLabel">Update Patient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/employee-management-system/endpoint/update-patient.php" method="POST" id="updatePatientForm">
                        <input type="hidden" id="updatePatientId" name="patient_id">
                        
                        <div class="form-group mb-3">
                            <label for="updateName" class="form-label">Full Name:*</label>
                            <input type="text" class="form-control" id="updateName" name="name" required>
                        </div>
                        
                        <div class="form-group row mb-3">
                            <div class="col-md-4">
                                <label for="updateBirthday" class="form-label">Date of Birth:</label>
                                <input type="date" class="form-control" id="updateBirthday" name="birthday">
                            </div>
                            <div class="col-md-4">
                                <label for="updateGender" class="form-label">Gender:</label>
                                <select class="form-control" id="updateGender" name="gender">
                                    <option value="">Select</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <!-- <div class="col-md-4">
                                <label for="updateBloodType" class="form-label">Blood Type:</label>
                                <select class="form-control" id="updateBloodType" name="blood_type">
                                    <option value="">Unknown</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                </select>
                            </div> -->
                            <div class="col-md-4">
                            <label for="updateAddress" class="form-label">Account Number:</label>
                            <input class="form-control" rows="2" name="address" id="updateAddress">
                            </div>
                        </div>
                        
                        <div class="form-group row mb-3">
                            <div class="col-md-6">
                                <label for="updatePhone" class="form-label">Phone Number:*</label>
                                <input type="tel" class="form-control" id="updatePhone" name="phone" required>
                            </div>
                            <div class="col-md-6">
                                <label for="updateWhatsapp" class="form-label">WhatsApp Number:</label>
                                <input type="tel" class="form-control" id="updateWhatsapp" name="whatsapp_number">
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="updateEmail" class="form-label">Email Address:</label>
                            <input type="email" class="form-control" id="updateEmail" name="email">
                        </div>
                        
                        <!-- <div class="form-group mb-3">
                            <label for="updateAddress" class="form-label">Account Number:</label>
                            <textarea class="form-control" rows="2" name="address" id="updateAddress"></textarea>
                        </div> -->
                        
                        <div class="form-group row mb-3">
                            <div class="col-md-6">
                                <label for="updateNextAppointment" class="form-label">Next Appointment:</label>
                                <input type="date" class="form-control" id="updateNextAppointment" name="next_appointment">
                            </div>
                            <div class="col-md-6">
                                <label for="updateMedicalNotes" class="form-label">Notes:</label>
                                <textarea class="form-control" rows="1" name="medical_notes" id="updateMedicalNotes"></textarea>
                            </div>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Patient</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Data Table -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>

    <script>
    // Notification storage in localStorage
    const NOTIFICATION_STORAGE_KEY = 'patientManagementNotifications';

    // Initialize notification storage
    function initNotificationStorage() {
        let storage = localStorage.getItem(NOTIFICATION_STORAGE_KEY);
        
        if (!storage) {
            storage = {
                clearedAppointments: [],
                clearedBirthdays: []
            };
            localStorage.setItem(NOTIFICATION_STORAGE_KEY, JSON.stringify(storage));
            return storage;
        }
        
        try {
            storage = JSON.parse(storage);
            // Ensure the structure is correct
            if (!Array.isArray(storage.clearedAppointments)) {
                storage.clearedAppointments = [];
            }
            if (!Array.isArray(storage.clearedBirthdays)) {
                storage.clearedBirthdays = [];
            }
            localStorage.setItem(NOTIFICATION_STORAGE_KEY, JSON.stringify(storage));
            return storage;
        } catch (e) {
            console.error('Error parsing notification storage, resetting:', e);
            storage = {
                clearedAppointments: [],
                clearedBirthdays: []
            };
            localStorage.setItem(NOTIFICATION_STORAGE_KEY, JSON.stringify(storage));
            return storage;
        }
    }

    // Check if notification is cleared
    function isNotificationCleared(type, id) {
        const storage = JSON.parse(localStorage.getItem(NOTIFICATION_STORAGE_KEY));
        return storage[`cleared${type}`].includes(id.toString());
    }

    // Clear a notification
    function clearNotification(type, id) {
        const storage = JSON.parse(localStorage.getItem(NOTIFICATION_STORAGE_KEY));
        const idStr = id.toString();
        if (!storage[`cleared${type}`].includes(idStr)) {
            storage[`cleared${type}`].push(idStr);
            localStorage.setItem(NOTIFICATION_STORAGE_KEY, JSON.stringify(storage));
            
            // Update the UI immediately
            if (type === 'Appointments') {
                loadAppointmentNotifications();
            } else {
                loadBirthdayNotifications();
            }
        }
    }

    // Clear all notifications of a type
    function clearAllNotifications(type) {
        const storage = JSON.parse(localStorage.getItem(NOTIFICATION_STORAGE_KEY));
        storage[`cleared${type}`] = [];
        localStorage.setItem(NOTIFICATION_STORAGE_KEY, JSON.stringify(storage));
        
        // Update UI
        const container = type === 'Appointments' ? 
            $('#appointmentNotifications') : $('#birthdayNotifications');
        const badge = type === 'Appointments' ? 
            $('#appointmentBadge') : $('#birthdayBadge');
        
        container.html('<div class="no-notifications">No notifications</div>');
        badge.addClass('d-none');
        
        // Send request to server to mark all as read
        $.ajax({
            url: `/employee-management-system/endpoint/clear-all-notifications.php?type=${type.toLowerCase()}`,
            method: 'POST'
        });
    }

    // Load appointment notifications with timezone adjustment
    function loadAppointmentNotifications() {
        $.ajax({
            url: '/employee-management-system/endpoint/get-upcoming-appointments.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                const container = $('#appointmentNotifications');
                const badge = $('#appointmentBadge');
                
                if (data.length === 0) {
                    container.html('<div class="no-notifications">No appointments this month</div>');
                    badge.addClass('d-none');
                } else {
                    let html = '';
                    let unreadCount = 0;
                    let todayAppointments = 0;
                    
                    data.forEach(item => {
                        const isCleared = isNotificationCleared('Appointments', item.id);
                        if (!isCleared) unreadCount++;
                        
                        // Adjust for UTC+2 timezone
                        const date = new Date(item.date);
                        date.setHours(date.getHours() + 2); // Add 2 hours for Harare/Pretoria time
                        
                        const formattedDate = date.toLocaleDateString('en-US', {
                            month: 'short',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit',
                            timeZone: 'Africa/Johannesburg'
                        });
                        
                        const isToday = item.is_today;
                        if (isToday) todayAppointments++;
                        
                        html += `
                            <li class="notification-item appointment-notification ${isToday ? 'today-appointment' : ''} ${isCleared ? 'read' : 'unread'}" 
                                data-patient-id="${item.id}" data-type="appointment">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar-day notification-icon"></i>
                                    <div>
                                        <div class="d-flex align-items-center">
                                            <strong>${item.name}</strong>
                                            <span class="notification-time">${formattedDate}</span>
                                        </div>
                                        <div class="text-muted small">${item.phone || 'No phone'}</div>
                                        ${isToday ? '<span class="badge bg-primary">Today</span>' : ''}
                                    </div>
                                </div>
                            </li>
                        `;
                    });
                    
                    container.html(html || '<div class="no-notifications">No unread appointments</div>');
                    
                    if (unreadCount > 0) {
                        badge.text(unreadCount).removeClass('d-none');
                    } else {
                        badge.addClass('d-none');
                    }
                    
                    if (todayAppointments > 0) {
                        showBrowserNotification(
                            'Appointments Today', 
                            `You have ${todayAppointments} appointment(s) scheduled for today`
                        );
                    }
                }
            },
            error: function() {
                $('#appointmentNotifications').html('<div class="no-notifications text-danger">Failed to load appointments</div>');
            }
        });
    }

    // Load birthday notifications with timezone adjustment
    function loadBirthdayNotifications() {
        $.ajax({
            url: '/employee-management-system/endpoint/get-upcoming-birthdays.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                const container = $('#birthdayNotifications');
                const badge = $('#birthdayBadge');
                
                if (data.length === 0) {
                    container.html('<div class="no-notifications">No birthdays today</div>');
                    badge.addClass('d-none');
                } else {
                    let html = '';
                    let unreadCount = 0;
                    
                    data.forEach(item => {
                        const isCleared = isNotificationCleared('Birthdays', item.id);
                        if (!isCleared) unreadCount++;
                        
                        // Adjust for UTC+2 timezone
                        const birthday = new Date(item.date);
                        birthday.setHours(birthday.getHours() + 2); // Add 2 hours for Harare/Pretoria time
                        
                        const formattedDate = birthday.toLocaleDateString('en-US', {
                            month: 'long',
                            day: 'numeric',
                            timeZone: 'Africa/Johannesburg'
                        });
                        
                        html += `
                            <li class="notification-item birthday-notification ${isCleared ? 'read' : 'unread'}" 
                                data-patient-id="${item.id}" data-type="birthday">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-birthday-cake notification-icon"></i>
                                    <div>
                                        <strong>${item.name}</strong>
                                        <div class="text-muted small">Birthday: ${formattedDate}</div>
                                        <div class="text-muted small">Turning ${item.age}</div>
                                        <div class="text-muted small">${item.phone || 'No phone'}</div>
                                    </div>
                                </div>
                            </li>
                        `;
                    });
                    
                    container.html(html || '<div class="no-notifications">No unread birthdays</div>');
                    
                    if (unreadCount > 0) {
                        badge.text(unreadCount).removeClass('d-none');
                        
                        // Show browser notification for birthdays
                        showBrowserNotification(
                            'Birthdays Today', 
                            `You have ${unreadCount} patient(s) with birthdays today`
                        );
                    } else {
                        badge.addClass('d-none');
                    }
                }
            },
            error: function() {
                $('#birthdayNotifications').html('<div class="no-notifications text-danger">Failed to load birthdays</div>');
            }
        });
    }

    // Show browser notification
    function showBrowserNotification(title, message) {
        if (!("Notification" in window)) {
            console.log("This browser does not support desktop notification");
            return;
        }
        
        if (Notification.permission === "granted") {
            new Notification(title, { body: message });
        } 
        else if (Notification.permission !== "denied") {
            Notification.requestPermission().then(function (permission) {
                if (permission === "granted") {
                    new Notification(title, { body: message });
                }
            });
        }
    }

    // View patient details
function viewPatient(patientId) {
    fetch(`/employee-management-system/endpoint/get-patient.php?id=${patientId}`)
        .then(response => response.json())
        .then(data => {
            // Format dates as yyyy-mm-dd without time
            const formatDate = (dateString) => {
                if (!dateString) return 'N/A';
                const date = new Date(dateString);
                // Adjust for timezone offset
                date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
                return date.toISOString().split('T')[0];
            };

            const birthday = formatDate(data.birthday);
            const dateAdded = formatDate(data.date_added);
            const nextAppointment = data.next_appointment ? formatDate(data.next_appointment) : 'Not Scheduled';
            
            // Populate modal
            document.getElementById('modal-address').textContent = data.address || 'N/A';
            document.getElementById('modal-name').textContent = data.name;
            document.getElementById('modal-birthday').textContent = birthday;
            document.getElementById('modal-gender').textContent = data.gender || 'N/A';
            document.getElementById('modal-phone').textContent = data.phone || 'N/A';
            document.getElementById('modal-whatsapp').textContent = data.whatsapp_number || 'N/A';
            document.getElementById('modal-email').textContent = data.email || 'N/A';
            document.getElementById('modal-date-added').textContent = dateAdded;
            document.getElementById('modal-next-appointment').textContent = nextAppointment;
            document.getElementById('modal-medical-notes').textContent = data.medical_notes || 'N/A';

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('patientDetailsModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to fetch patient details');
        });
}

    // Update patient - fetch data and open modal
    function updatePatient(patientId) {
        fetch(`/employee-management-system/endpoint/get-patient.php?id=${patientId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('updatePatientId').value = data.tbl_patient_id;
                document.getElementById('updateName').value = data.name || '';
                document.getElementById('updateBirthday').value = data.birthday || '';
                document.getElementById('updateGender').value = data.gender || '';
                // document.getElementById('updateBloodType').value = data.blood_type || '';
                document.getElementById('updatePhone').value = data.phone || '';
                document.getElementById('updateWhatsapp').value = data.whatsapp_number || '';
                document.getElementById('updateEmail').value = data.email || '';
                document.getElementById('updateAddress').value = data.address || '';
                
                // Adjust datetime-local value for timezone
                if (data.next_appointment) {
                    const appointmentDate = new Date(data.next_appointment);
                    appointmentDate.setHours(appointmentDate.getHours() + 2); // UTC+2 adjustment
                    document.getElementById('updateNextAppointment').value = 
                        appointmentDate.toISOString().slice(0, 16);
                } else {
                    document.getElementById('updateNextAppointment').value = '';
                }
                
                document.getElementById('updateMedicalNotes').value = data.medical_notes || '';
                
                const modal = new bootstrap.Modal(document.getElementById('updatePatientModal'));
                modal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to fetch patient details for update');
            });
    }

    // Initialize the page
    $(document).ready(function() {
        // Initialize notification storage
        initNotificationStorage();
        
        // Initialize DataTable
        var table = $('#patientTable').DataTable({
            "pageLength": 10,
            "responsive": true,
            "columnDefs": [
                { "targets": 4, "type": "date" },
                { "targets": 5, "type": "date" }
            ]
        });

        // Month filter handler
        $('#monthFilter').change(function() {
            var month = $(this).val();
            
            if (month === 'all') {
                table.columns().search('').draw();
            } else {
                $.fn.dataTable.ext.search.push(
                    function(settings, data, dataIndex) {
                        var appointmentDate = data[4];
                        if (!appointmentDate || appointmentDate === 'Not Scheduled') return false;
                        
                        var date = new Date(appointmentDate);
                        var rowMonth = date.getMonth() + 1;
                        
                        return rowMonth == month;
                    }
                );
                
                table.draw();
                $.fn.dataTable.ext.search.pop();
            }
        });

        // Birthday month filter handler
    $('#birthdayMonthFilter').change(function() {
        var month = $(this).val();
        
        if (month === 'all') {
            table.columns().search('').draw();
        } else {
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var birthdayDate = data[5];
                    if (!birthdayDate || birthdayDate === 'N/A') return false;
                    
                    var date = new Date(birthdayDate);
                    var rowMonth = date.getMonth() + 1;
                    
                    return rowMonth == month;
                }
            );
            
            table.draw();
            $.fn.dataTable.ext.search.pop();
        }
    });

        // Clear appointment notifications
        $('#clearAppointmentNotifications').click(function(e) {
            e.stopPropagation();
            clearAllNotifications('Appointments');
        });

        // Clear birthday notifications
        $('#clearBirthdayNotifications').click(function(e) {
            e.stopPropagation();
            clearAllNotifications('Birthdays');
        });

        // Add click handler for appointment notifications
        $(document).on('click', '.notification-item[data-type="appointment"]', function() {
            const patientId = $(this).data('patient-id');
            clearNotification('Appointments', patientId);
            viewPatient(patientId);
        });

        // Add click handler for birthday notifications
        $(document).on('click', '.notification-item[data-type="birthday"]', function() {
            const patientId = $(this).data('patient-id');
            clearNotification('Birthdays', patientId);
            viewPatient(patientId);
        });

        // Request notification permission
        if ("Notification" in window) {
            Notification.requestPermission();
        }
        
        // Load initial notifications
        loadAppointmentNotifications();
        loadBirthdayNotifications();
        
        // Refresh notifications every 5 minutes
        setInterval(function() {
            loadAppointmentNotifications();
            loadBirthdayNotifications();
        }, 300000); // 5 minutes in milliseconds

        // Export functionality
            document.getElementById("exportPdfBtn").addEventListener("click", () => {
                const selectedMonth = $('#monthFilter').val();
                window.location.href = `/employee-management-system/endpoint/export-patients-pdf.php?month=${selectedMonth}`;
            });

            // Export by Birthday Month
document.getElementById('exportBirthdaysBtn').addEventListener('click', function(e) {
    e.preventDefault();
    
    // Get the selected birthday month from the filter
    const selectedBirthdayMonth = document.getElementById('birthdayMonthFilter').value;
    
    // Create a form to submit the request
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/employee-management-system/endpoint/export-birthdays-pdf.php';
    
    // Add the month parameter
    const monthInput = document.createElement('input');
    monthInput.type = 'hidden';
    monthInput.name = 'month';
    monthInput.value = selectedBirthdayMonth;
    form.appendChild(monthInput);
    
    // Submit the form
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
});

        // Delete patient
        window.deletePatient = function(id) {
            if (confirm("Are you sure you want to delete this patient record?")) {
                window.location = "/employee-management-system/endpoint/delete-patient.php?patient_id=" + id;
            }
        }
    });
</script>
</body>
</html>
