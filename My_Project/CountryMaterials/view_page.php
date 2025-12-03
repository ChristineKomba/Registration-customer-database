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
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f0f2f5;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    .container {
        text-align: center;
        background: #fff;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 4px 25px rgba(0,0,0,0.1);
        width: 400px;
    }
    h2 {
        color: <?php echo $messageColor; ?>;
        margin-bottom: 20px;
    }
    .btn {
        display: inline-block;
        margin: 10px 0;
        padding: 10px 20px;
        background: #4CAF50;
        color: white;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        font-size: 16px;
        transition: all 0.2s ease;
    }
    .btn:hover {
        background: #45a049;
    }
</style>
</head>
<body>

<div class="container">
    <h2><?php echo $message; ?></h2>
    <!-- Updated Go Back button -->
    <a href="country_materials.php" class="btn" style="padding:8px 20px; font-size:14px;">Go Back</a>
</div>

</body>
</html>
