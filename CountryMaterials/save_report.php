<?php
// 1. Establish Connection Directly
// Parameters: "host", "username", "password", "database"
$conn = new mysqli("localhost", "root", "", "country_materials");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Create a unique ID for this specific report group
    $report_session = "REP-" . date("Ymd-His");

    // 3. Process the multiple rows
    if (isset($_POST['customer_name'])) {
        foreach ($_POST['customer_name'] as $key => $value) {
            
            // Clean data for security
            $customer = $conn->real_escape_string($_POST['customer_name'][$key]);
            $contact_n = $conn->real_escape_string($_POST['contact_name'][$key]);
            $contact_p = $conn->real_escape_string($_POST['contact_person'][$key]);
            $email = $conn->real_escape_string($_POST['email'][$key]);
            $phone = $conn->real_escape_string($_POST['phone'][$key]);
            $summary = $conn->real_escape_string($_POST['summary'][$key]);
            $remarks = $conn->real_escape_string($_POST['remarks'][$key]);

            // Save only if Customer Name is filled
            if (!empty($customer)) {
                $sql = "INSERT INTO daily_reports (report_session, customer_name, contact_name, contact_person, email, phone, summary, remarks) 
                        VALUES ('$report_session', '$customer', '$contact_n', '$contact_p', '$email', '$phone', '$summary', '$remarks')";
                
                if (!$conn->query($sql)) {
                    echo "Error: " . $conn->error;
                }
            }
        }
        
        // Success feedback and return to main dashboard
        echo "<script>alert('Report Saved Successfully!'); window.location.href='country_materials.php';</script>";
    }
}
$conn->close();
?>