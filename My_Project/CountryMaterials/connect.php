<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'test');

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName       = isset($_POST['firstName']) ? trim($_POST['firstName']) : '';
    $lastName        = isset($_POST['lastName']) ? trim($_POST['lastName']) : '';
    $company         = isset($_POST['company']) ? trim($_POST['company']) : '';
    $registration    = isset($_POST['registration']) ? trim($_POST['registration']) : '';
    $postalAddress   = isset($_POST['postalAddress']) ? trim($_POST['postalAddress']) : '';
    $location        = isset($_POST['location']) ? trim($_POST['location']) : '';
    $email           = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone           = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $physicalAddress = isset($_POST['physicalAddress']) ? trim($_POST['physicalAddress']) : '';

    // Simple validation
    if (empty($firstName) || empty($lastName) || empty($email)) {
        header("Location: view_page.php?status=empty");
        exit();
    }

    // Check if email already exists
    $checkStmt = $conn->prepare("SELECT * FROM registration WHERE email=?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: view_page.php?status=exists");
        exit();
    } else {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO registration (firstname, lastname, company, registration, postalAddress, location, email, phone, physicalAddress) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $firstName, $lastName, $company, $registration, $postalAddress, $location, $email, $phone, $physicalAddress);

        if ($stmt->execute()) {
            header("Location: view_page.php?status=success");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $checkStmt->close();
}

$conn->close();
?>
