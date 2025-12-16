<?php
$conn = new mysqli("localhost", "root", "", "test");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_POST['import'])) {
    if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] == 0) {
        $spreadsheet = IOFactory::load($_FILES['excel_file']['tmp_name']);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        array_shift($rows);

        foreach ($rows as $row) {
            $firstName       = $conn->real_escape_string($row[0]);
            $lastName        = $conn->real_escape_string($row[1]);
            $company         = $conn->real_escape_string($row[2]);
            $registration    = $conn->real_escape_string($row[3]);
            $postalAddress   = $conn->real_escape_string($row[4]);
            $location        = $conn->real_escape_string($row[5]);
            $email           = $conn->real_escape_string($row[6]);
            $phone           = $conn->real_escape_string($row[7]);
            $physicalAddress = $conn->real_escape_string($row[8]);
            $created_at      = date('Y-m-d H:i:s');

            if ($firstName == "" && $lastName == "") continue;

            // Check if email exists
            $check = $conn->query("SELECT id FROM registration WHERE email='$email'");
            if ($check->num_rows == 0) {
                $conn->query("INSERT INTO registration 
                    (firstname, lastname, company, registration, postalAddress, location, email, phone, physicalAddress, created_at)
                    VALUES 
                    ('$firstName','$lastName','$company','$registration','$postalAddress','$location','$email','$phone','$physicalAddress','$created_at')
                ");
            }
        }

        echo "<script>
                alert('Successfully Imported');
                setTimeout(function(){
                    window.location.href = 'country_materials.php';
                }, 1500);
              </script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Import Users</title>
    <link rel="stylesheet" href="import_page.css">
    <link rel="icon" type="image/jpeg" href="favicon.jpg">

</head>
<body>
<div class="container">
    <h2>Import Users</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="excel_file" accept=".xlsx,.xls"><br><br>
        <button type="submit" name="import" class="btn">Import</button><br>
        <a href="country_materials.php" class="back-btn">Go Back</a>
    </form>
</div>
</body>
</html>
