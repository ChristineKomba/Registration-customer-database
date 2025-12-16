<?php
$conn = new mysqli("localhost", "root", "", "test");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user ID is set
if(!isset($_GET['id']) || empty($_GET['id'])){
    die("No user ID provided!");
}

$id = intval($_GET['id']);

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM registration WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows === 0){
    die("User not found!");
}

$user = $result->fetch_assoc();

// Handle form submission
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $company = $_POST['company'] ?? '';
    $registration = $_POST['registration'] ?? '';
    $postalAddress = $_POST['postalAddress'] ?? '';
    $location = $_POST['location'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $physicalAddress = $_POST['physicalAddress'] ?? '';

    $update = $conn->prepare("UPDATE registration SET firstname=?, lastname=?, company=?, registration=?, postalAddress=?, location=?, email=?, phone=?, physicalAddress=? WHERE id=?");
    $update->bind_param("sssssssssi", $firstname, $lastname, $company, $registration, $postalAddress, $location, $email, $phone, $physicalAddress, $id);

    if($update->execute()){
        header("Location: admin_view.php");
        exit();
    } else {
        echo "Error updating user: " . $update->error;
    }
    $update->close();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="edit_user.css">
    <link rel="icon" type="image/jpeg" href="favicon.jpg">

</head>
<body>
<div class="container">
    <h2>Edit User</h2>
    <form method="post">
        <label>First Name</label>
        <input type="text" name="firstname" value="<?php echo htmlspecialchars($user['firstname']); ?>" required>

        <label>Last Name</label>
        <input type="text" name="lastname" value="<?php echo htmlspecialchars($user['lastname']); ?>" required>

        <label>Company</label>
        <input type="text" name="company" value="<?php echo htmlspecialchars($user['company']); ?>">

        <label>Registration</label>
        <input type="text" name="registration" value="<?php echo htmlspecialchars($user['registration']); ?>">

        <label>Postal Address</label>
        <input type="text" name="postalAddress" value="<?php echo htmlspecialchars($user['postalAddress']); ?>">

        <label>Location</label>
        <input type="text" name="location" value="<?php echo htmlspecialchars($user['location']); ?>">

        <label>Email</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <label>Phone</label>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>

        <label>Physical Address</label>
        <input type="text" name="physicalAddress" value="<?php echo htmlspecialchars($user['physicalAddress']); ?>">

        <label>Created At</label>
        <input type="text" value="<?php echo date("d-m-Y H:i:s", strtotime($user['created_at'])); ?>" readonly>

        <button type="submit" class="save-btn">Save Changes</button>
    </form>
    <a href="admin_view.php" class="go-back">Go Back</a>
</div>
</body>
</html>
