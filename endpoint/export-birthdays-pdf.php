<?php
require_once('../conn/conn.php');
require_once('../vendor/tecnickcom/tcpdf/tcpdf.php'); // Include TCPDF library

// Get the birthday month filter if provided
$monthFilter = isset($_GET['month']) ? (int)$_GET['month'] : null;

try {
    // Fetch data from the database
    $sql = "SELECT 
                address AS account_number,
                name AS full_name,
                phone AS contact,
                whatsapp_number AS whatsapp,
                next_appointment,
                birthday
            FROM tbl_patient";

    if ($monthFilter && $monthFilter >= 1 && $monthFilter <= 12) {
        $sql .= " WHERE MONTH(birthday) = :month";
    }

    $sql .= " ORDER BY birthday ASC";

    $stmt = $conn->prepare($sql);

    if ($monthFilter && $monthFilter >= 1 && $monthFilter <= 12) {
        $stmt->bindParam(':month', $monthFilter);
    }

    $stmt->execute();
    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Create new PDF document in Landscape mode
    $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('Birthday Records Export');
    $pdf->SetSubject('Patient Birthdays');
    $pdf->SetKeywords('TCPDF, PDF, Patients, Birthday, Export');

    // Remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // Set margins
    $pdf->SetMargins(10, 10, 10);
    $pdf->SetAutoPageBreak(TRUE, 10);

    // Add a page
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('helvetica', '', 10);

    // Title
    $pdf->Cell(0, 10, 'Patient Birthday Records Export', 0, 1, 'C');
    $pdf->Ln(5);

    // Date and filter info
    $pdf->Cell(0, 10, 'Generated on: ' . date('d-M-Y H:i:s'), 0, 1, 'C');
    
    if ($monthFilter) {
        $monthName = date('F', mktime(0, 0, 0, $monthFilter, 1));
        $pdf->Cell(0, 10, 'Filtered by Birth Month: ' . $monthName, 0, 1, 'C');
    }

    $pdf->Ln(5);

    // Table header
    $header = array('Account Number', 'Full Name', 'Contact', 'WhatsApp', 'Next Appointment', 'Date of Birth');
    $pdf->SetFillColor(240, 240, 240);
    $pdf->SetFont('helvetica', 'B', 10);

    foreach ($header as $col) {
        $pdf->Cell(40, 7, $col, 1, 0, 'C', 1);
    }
    $pdf->Ln();

    // Data rows
    $pdf->SetFont('helvetica', '', 9);
    foreach ($patients as $row) {
        $nextAppointment = $row['next_appointment'] ? 
            date('d-M-Y', strtotime($row['next_appointment'])) : 'Not Scheduled';
        $birthday = $row['birthday'] ? 
            date('d-M-Y', strtotime($row['birthday'])) : 'N/A';

        $pdf->Cell(40, 6, $row['account_number'], 'LR', 0, 'L');
        $pdf->Cell(40, 6, $row['full_name'], 'LR', 0, 'L');
        $pdf->Cell(40, 6, $row['contact'], 'LR', 0, 'L');
        $pdf->Cell(40, 6, $row['whatsapp'], 'LR', 0, 'L');
        $pdf->Cell(40, 6, $nextAppointment, 'LR', 0, 'L');
        $pdf->Cell(40, 6, $birthday, 'LR', 0, 'L');
        $pdf->Ln();
    }

    // Output PDF
    $pdf->Output('birthdays_export_' . date('Y-m-d') . '.pdf', 'D');

} catch(PDOException $e) {
    die('Database error: ' . $e->getMessage());
} catch(Exception $e) {
    die('Export error: ' . $e->getMessage());
}
?>
