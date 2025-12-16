<?php
$status = isset($_GET['status']) ? $_GET['status'] : '';
$message = '';
$messageColor = '';

if($status == 'success'){
    $message = 'Registered Successfully ✅';
    $messageColor = 'green';
} elseif($status == 'exists'){
    $message = 'This email is already registered. Please use another email.';
    $messageColor = 'red';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Registration Status</title>
<link rel="stylesheet" href="view_page.css">


</head>
<body>

<div class="container">
    <h2><?php echo $message; ?></h2>
    <!-- Updated Go Back button -->
    <a href="country_materials.php" class="btn" style="padding:8px 20px; font-size:14px;">Go Back</a>
</div>

</body>
</html>
