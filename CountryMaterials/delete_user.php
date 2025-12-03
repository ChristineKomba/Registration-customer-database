<?php
$conn = new mysqli("localhost", "root", "", "test");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM registration WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect to admin_view.php with a success query parameter
        header("Location: admin_view.php?status=deleted");
        exit;
    } else {
        echo "Error deleting user: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid user ID.";
}

$conn->close();
?>
