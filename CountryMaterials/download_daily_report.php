<?php
$conn = new mysqli("localhost","root","","country_materials");
if ($conn->connect_error) die("DB error");

$session = $_GET['session'];

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=daily_report_$session.csv");

$output = fopen("php://output", "w");

fputcsv($output, ["Customer Name","Contact Name","Contact Person","Email","Phone","Summary","Remarks","Date"]);

$stmt = $conn->prepare("
    SELECT customer_name, contact_name, contact_person, email, phone, summary, remarks, created_at
    FROM daily_reports WHERE report_session=?
");
$stmt->bind_param("s",$session);
$stmt->execute();
$res = $stmt->get_result();

while($row = $res->fetch_assoc()){
    fputcsv($output, $row);
}
fclose($output);
exit;
