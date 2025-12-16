<?php
// Show all errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include PhpSpreadsheet
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

if(isset($_POST['import'])){
    $conn = new mysqli("localhost", "root", "", "test");
    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

    // Check uploaded file
    if(!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] != 0){
        echo "<script>alert('Please select a file to import'); window.location.href='admin_view.php';</script>";
        exit();
    }

    $fileType = pathinfo($_FILES['excel_file']['name'], PATHINFO_EXTENSION);
    $allowed = ['xlsx','xls','csv'];
    if(!in_array(strtolower($fileType), $allowed)){
        echo "<script>alert('Invalid file format. Please upload Excel (.xlsx, .xls, .csv)'); window.location.href='admin_view.php';</script>";
        exit();
    }

    $fileName = $_FILES['excel_file']['tmp_name'];

    try {
        $spreadsheet = IOFactory::load($fileName);
    } catch(Exception $e){
        echo "<script>alert('Error reading Excel file: ".$e->getMessage()."'); window.location.href='admin_view.php';</script>";
        exit();
    }

    $sheetData = $spreadsheet->getActiveSheet()->toArray();
    $inserted = 0;

    foreach($sheetData as $key => $row){
        if($key == 0) continue; 
        $firstname       = $conn->real_escape_string($row[0] ?? '');
        $lastname        = $conn->real_escape_string($row[1] ?? '');
        $company         = $conn->real_escape_string($row[2] ?? '');
        $registration    = $conn->real_escape_string($row[3] ?? '');
        $postalAddress   = $conn->real_escape_string($row[4] ?? '');
        $location        = $conn->real_escape_string($row[5] ?? '');
        $email           = $conn->real_escape_string($row[6] ?? '');
        $phone           = $conn->real_escape_string($row[7] ?? '');
        $physicalAddress = $conn->real_escape_string($row[8] ?? '');
        $created_at      = date('Y-m-d H:i:s');

        // Skip if essential fields are empty
        if(empty($firstname) || empty($lastname)) continue;

        $conn->query("INSERT INTO registration (firstname, lastname, company, registration, postalAddress, location, email, phone, physicalAddress, created_at)
                      VALUES ('$firstname','$lastname','$company','$registration','$postalAddress','$location','$email','$phone','$physicalAddress','$created_at')");
        $inserted++;
    }

    $conn->close();

    if($inserted > 0){
        echo "<script>alert('✅ Successfully Imported $inserted user(s)'); window.location.href='admin_view.php';</script>";
    } else {
        echo "<script>alert('No valid data found to import'); window.location.href='admin_view.php';</script>";
    }
}
?>
