<?php
$conn = new mysqli("localhost","root","","country_materials");

$file_id = (int)$_GET['id'];
$reports = $conn->query(
    "SELECT * FROM waste_reports WHERE file_id=$file_id"
);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="Waste_Report_'.$file_id.'.csv"');

$output = fopen('php://output', 'w');

// Column headers
fputcsv($output, [
    "report_date","customer_name","phone","factory","vehicle_no",
    "material_type","quantity_kg","buying_price","selling_price","margin",
    "profit","driver_name","client_location","payment_status"
]);

while($r = $reports->fetch_assoc()){
    fputcsv($output, [
        $r['report_date'],$r['customer_name'],$r['phone'],$r['factory'],
        $r['vehicle_no'],$r['material_type'],$r['quantity_kg'],$r['buying_price'],
        $r['selling_price'],$r['margin'],$r['profit'],$r['driver_name'],
        $r['client_location'],$r['payment_status']
    ]);
}

fclose($output);
exit;
