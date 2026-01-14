<?php
$conn = new mysqli("localhost","root","","country_materials");
if ($conn->connect_error) die("DB error");

$result = $conn->query("
    SELECT report_session, report_name, created_at
    FROM daily_reports
    GROUP BY report_session
    ORDER BY created_at DESC
");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Uploaded Daily Reports</title>
<link rel="stylesheet" href="daily-report.css">
<style>
.file-card { display:flex; justify-content:space-between; align-items:center; background:#fff; padding:15px; margin-bottom:10px; border-radius:8px; cursor:pointer; box-shadow:0 2px 6px rgba(0,0,0,0.05); transition:0.2s;}
.file-card:hover { background:#f1f5f9; }
.btn { background: #8a8d91; color:#fff; border:none; padding:6px 12px; border-radius:5px; cursor:pointer; font-size:14px; text-decoration:none; }
.btn:hover { background:#717477; }
</style>
<script>
function openReport(session){
    window.location="view_daily_report_details.php?session="+session;
}
</script>
</head>
<body>
<div class="container">
    <div class="header-container">
        <h2>Uploaded Daily Reports</h2>
        <a href="daily-report.php" class="back-btn"><i class="fas fa-arrow-left"></i> Go Back</a>
    </div>

    <div class="list">
    <?php while($row=$result->fetch_assoc()): ?>
        <div class="file-card" ondblclick="openReport('<?= $row['report_session'] ?>')">
            <div>
                <strong><?= htmlspecialchars($row['report_name']) ?></strong><br>
                <small><?= date("d M Y, H:i", strtotime($row['created_at'])) ?></small>
            </div>
            <a class="btn" href="download_daily_report.php?session=<?= $row['report_session'] ?>">⬇ Download</a>
        </div>
    <?php endwhile; ?>
    </div>
</div>
</body>
</html>
