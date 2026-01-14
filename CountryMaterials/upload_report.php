<?php
$conn = new mysqli("localhost","root","","country_materials");

if(isset($_POST['upload'])){
    $file = $_FILES['report'];
    $fileName = time().'_'.$file['name'];
    $path = "uploads/".$fileName;

    move_uploaded_file($file['tmp_name'],$path);

    $stmt = $conn->prepare(
        "INSERT INTO uploaded_reports(file_name,file_path) VALUES (?,?)"
    );
    $stmt->bind_param("ss",$fileName,$path);
    $stmt->execute();

    header("Location: view_reports.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="reports.css">
</head>
<body>

<div class="upload-box">
    <h2>Upload Waste Report</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="report" required>
        <button name="upload">Upload</button>
    </form>
</div>

</body>
</html>
