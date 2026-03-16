<?php
include('../conn/conn.php');

// Get the month filter if provided
$monthFilter = isset($_GET['month']) ? (int)$_GET['month'] : null;

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=patients_export_' . date('Y-m-d') . '.csv');

$output = fopen('php://output', 'w');

// CSV headers - only the requested columns
fputcsv($output, array(
    'Account Number', 
    'Full Name', 
    'Contact', 
    'WhatsApp', 
    'Next Appointment',
    'Date of Birth'
));

try {
    $sql = "SELECT 
                address AS account_number,
                name AS full_name,
                phone AS contact,
                whatsapp_number AS whatsapp,
                next_appointment,
                birthday
            FROM tbl_patient";
    
    // Add month filter if specified
    if ($monthFilter && $monthFilter >= 1 && $monthFilter <= 12) {
        $sql .= " WHERE MONTH(next_appointment) = :month";
    }
    
    $sql .= " ORDER BY next_appointment DESC";
    
    $stmt = $conn->prepare($sql);
    
    if ($monthFilter && $monthFilter >= 1 && $monthFilter <= 12) {
        $stmt->bindParam(':month', $monthFilter);
    }
    
    $stmt->execute();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Format dates
        $row['next_appointment'] = $row['next_appointment'] ? date('M d, Y', strtotime($row['next_appointment'])) : 'Not Scheduled';
        $row['birthday'] = $row['birthday'] ? date('M d, Y', strtotime($row['birthday'])) : 'N/A';
        
        // Only include the requested columns in the output
        $exportRow = [
            $row['account_number'],
            $row['full_name'],
            $row['contact'],
            $row['whatsapp'],
            $row['next_appointment'],
            $row['birthday']
        ];
        
        fputcsv($output, $exportRow);
    }
} catch(PDOException $e) {
    fputcsv($output, array('Error:', $e->getMessage()));
}

fclose($output);
exit;
?>