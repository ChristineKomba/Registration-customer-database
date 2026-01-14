<?php
$conn = new mysqli("localhost", "root", "", "country_materials");
if ($conn->connect_error) die("Database connection failed: " . $conn->connect_error);

$session = $_GET['session'] ?? '';
if (!$session) die("Invalid report session.");

// Fetch all rows for this report session
$stmt = $conn->prepare("SELECT * FROM daily_reports WHERE report_session = ? ORDER BY id ASC");
$stmt->bind_param("s", $session);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Daily Report Details</title>
<link rel="stylesheet" href="daily-report.css">
<style>
.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
.top-bar h3 {
    margin: 0;
}
.btn {
    background: #8a8d91;
    color: #fff;
    border: none;
    padding: 8px 14px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
}
.btn:hover { background: #717477; }
table {
    width: 100%;
    border-collapse: collapse;
}
th, td {
    border: 1px solid #d1d1d1;
    padding: 8px;
    text-align: left;
}
th {
    background: #8a8d91;
    color: #fff;
}
</style>
</head>
<body>
<div class="container">
    <div class="top-bar">
        <h3>Daily Report - <?= htmlspecialchars($session) ?></h3>
        <div>
            <a href="view_daily_reports.php" class="btn">⬅ Go Back</a>
            <button onclick="window.print()" class="btn">🖨 Print</button>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Customer</th>
                <th>Contact Name</th>
                <th>Contact Person</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Summary</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['customer_name']) ?></td>
                    <td><?= htmlspecialchars($row['contact_name']) ?></td>
                    <td><?= htmlspecialchars($row['contact_person']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td><?= htmlspecialchars($row['summary']) ?></td>
                    <td><?= htmlspecialchars($row['remarks']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
