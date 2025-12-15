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
    $gender = $_POST['gender'] ?? '';
    $email = $_POST['email'] ?? '';
    $company = $_POST['company'] ?? '';
    $phone = $_POST['phone'] ?? '';

    $update = $conn->prepare("UPDATE registration SET firstname=?, lastname=?, gender=?, email=?, company=?, phone=? WHERE id=?");
    $update->bind_param("ssssssi", $firstname, $lastname, $gender, $email, $company, $phone, $id);

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
</head>
<body>
<div class="container">
    <h2>Edit User</h2>
    <form method="post">
        <label>First Name</label>
        <input type="text" name="firstname" value="<?php echo htmlspecialchars($user['firstname']); ?>" required>

        <label>Last Name</label>
        <input type="text" name="lastname" value="<?php echo htmlspecialchars($user['lastname']); ?>" required>

        <label>Gender</label>
        <select name="gender" required>
            <option value="Male" <?php if($user['gender']=='Male') echo 'selected'; ?>>Male</option>
            <option value="Female" <?php if($user['gender']=='Female') echo 'selected'; ?>>Female</option>
            <option value="Other" <?php if($user['gender']=='Other') echo 'selected'; ?>>Other</option>
        </select>

        <label>Email</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <label>Company</label>
        <input type="text" name="company" value="<?php echo htmlspecialchars($user['company']); ?>">

        <label>Phone</label>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>

        <button type="submit" class="save-btn">Save Changes</button>
    </form>
    <a href="admin_view.php" class="go-back">Go Back</a>
</div>
</body>
</html>
