<?php
$conn = new mysqli("localhost","root","","country_materials");

$file_id = (int)$_GET['id'];

$file = $conn->query(
    "SELECT * FROM uploaded_files WHERE id=$file_id"
)->fetch_assoc();

$reports = $conn->query(
    "SELECT * FROM waste_reports WHERE file_id=$file_id"
);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Report Details</title>
<link rel="stylesheet" href="reports.css">
</head>
<body>

<div class="top-bar">
    <h2 style="flex:1; text-align:center;"><?= htmlspecialchars($file['file_name']) ?></h2>
    <div>
        <a href="view_reports.php" class="back-btn">⬅ Go Back</a>
        <a href="#" onclick="window.print()" class="btn">🖨 Print</a>
        <a href="download_report.php?id=<?= $file_id ?>" class="download-btn">⬇ Download</a>
    </div>
</div>

<table class="table">
<tr>
    <th>Date</th>
    <th>Customer</th>
    <th>Phone</th>
    <th>Factory</th>
    <th>Vehicle</th>
    <th>Material</th>
    <th>Kg</th>
    <th>Buy</th>
    <th>Sell</th>
    <th>Margin</th>
    <th>Profit</th>
    <th>Driver</th>
    <th>Location</th>
    <th>Status</th>
</tr>

<?php while($r = $reports->fetch_assoc()){ ?>
<tr>
    <td><?= $r['report_date'] ?></td>
    <td><?= $r['customer_name'] ?></td>
    <td><?= $r['phone'] ?></td>
    <td><?= $r['factory'] ?></td>
    <td><?= $r['vehicle_no'] ?></td>
    <td><?= $r['material_type'] ?></td>
    <td><?= $r['quantity_kg'] ?></td>
    <td><?= number_format($r['buying_price']) ?></td>
    <td><?= number_format($r['selling_price']) ?></td>
    <td><?= number_format($r['margin']) ?></td>
    <td><?= number_format($r['profit']) ?></td>
    <td><?= $r['driver_name'] ?></td>
    <td><?= $r['client_location'] ?></td>
    <td><?= $r['payment_status'] ?></td>
</tr>
<?php } ?>
</table>

</body>
</html>
