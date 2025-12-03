<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'test');

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data safely
    $firstName = isset($_POST['firstName']) ? trim($_POST['firstName']) : '';
    $lastName  = isset($_POST['lastName']) ? trim($_POST['lastName']) : '';
    $gender    = isset($_POST['gender']) ? trim($_POST['gender']) : '';
    $email     = isset($_POST['email']) ? trim($_POST['email']) : '';
    $company   = isset($_POST['company']) ? trim($_POST['company']) : '';
    $phone     = isset($_POST['phone']) ? trim($_POST['phone']) : '';

    // Simple validation
    if (empty($firstName) || empty($lastName) || empty($email)) {
        // Redirect with error (optional)
        header("Location: view_page.php?status=empty");
        exit();
    }

    // Check if email already exists
    $checkStmt = $conn->prepare("SELECT * FROM registration WHERE email=?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        // Email exists
        header("Location: view_page.php?status=exists");
        exit();
    } else {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO registration (firstName, lastName, gender, email, company, phone) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $firstName, $lastName, $gender, $email, $company, $phone);

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
