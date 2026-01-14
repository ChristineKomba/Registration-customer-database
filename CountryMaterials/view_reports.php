<?php
$conn = new mysqli("localhost","root","","country_materials");
$result = $conn->query("SELECT * FROM uploaded_files ORDER BY uploaded_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="reports.css">
<script>
function openReport(id){
    window.location = "view_report_details.php?id="+id;
}
</script>
</head>
<body>

<div class="top-bar">
    <h2 style="flex:1; text-align:center;">Uploaded Reports</h2>
    <a href="upload_waste_report.php" class="back-btn">⬅ Go Back</a>
</div>

<div class="list">
<?php while($row = $result->fetch_assoc()){ ?>
    <div class="file-card" onclick="openReport(<?= $row['id'] ?>)">
        <div class="file-icon">📁</div>
        <div>
            <div class="file-name"><?= htmlspecialchars($row['file_name']) ?></div>
            <div class="file-date"><?= $row['uploaded_at'] ?></div>
        </div>
    </div>
<?php } ?>
</div>

</body>
</html>
