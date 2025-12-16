<?php
$conn = new mysqli("localhost", "root", "", "test");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure IDs exist
if (!isset($_GET['ids']) || empty($_GET['ids'])) {
    header("Location: admin_view.php");
    exit();
}

$ids = $_GET['ids'];

// Clean input, allow only numbers & commas
$ids = preg_replace('/[^0-9,]/', '', $ids);

// Convert to array
$idArray = explode(",", $ids);

// Validate array contains only numbers
$idArray = array_filter($idArray, "is_numeric");

if (count($idArray) > 0) {

    // Convert back to safe string
    $cleanIds = implode(",", $idArray);

    // Delete all selected users
    $sql = "DELETE FROM registration WHERE id IN ($cleanIds)";

    if ($conn->query($sql) === TRUE) {
        // Redirect back with a success message
        header("Location: admin_view.php?status=deleted_multiple");
        exit();
    } else {
        echo "Error deleting users: " . $conn->error;
    }

} else {
    header("Location: admin_view.php");
    exit();
}

$conn->close();
?>
