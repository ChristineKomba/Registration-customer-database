<?php
require __DIR__ . '/../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

/* =======================
   DATABASE CONNECTION
======================= */
$conn = new mysqli("localhost", "root", "", "country_materials");
if ($conn->connect_error) {
    die("Database connection failed.");
}

/* =======================
   SUCCESS MESSAGE (GET)
======================= */
$message = "";
$messageType = "";

if (isset($_GET['success'])) {
    $message = "✅ Successfully imported ".$_GET['success']." records.";
    $messageType = "success";
}


/* =======================
   HANDLE UPLOAD
======================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {

    if (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] !== 0) {
        $message = "❌ Please upload a valid Excel file.";
        goto page;
    }

    $fileName = $_FILES['excel_file']['name'];
    $fileTmp  = $_FILES['excel_file']['tmp_name'];
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($ext, ['csv', 'xls', 'xlsx'])) {
        $message = "❌ Invalid file type. Use CSV, XLS or XLSX.";
        goto page;
    }

    try {
        $spreadsheet = IOFactory::load($fileTmp);
        $rows = $spreadsheet->getActiveSheet()->toArray();

        /* =======================
           VALIDATE TEMPLATE
        ======================= */
        if (count($rows) < 2 || count($rows[0]) !== 13) {
            $message = " Invalid Excel format. Use the correct Waste Report template.";
            goto page;
        }

        $stmt = $conn->prepare(
            "INSERT INTO waste_reports 
            (customer_name, phone, factory, vehicle_no, material_type,
             quantity_kg, buying_price, selling_price, margin, profit,
             driver_name, client_location, payment_status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $successCount = 0;

        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // skip header

            // Skip empty rows
            if (empty(array_filter($row))) continue;

            $stmt->bind_param(
                "sssssddddssss",
                $row[0], // customer_name
                $row[1], // phone
                $row[2], // factory
                $row[3], // vehicle_no
                $row[4], // material_type
                $row[5], // quantity_kg
                $row[6], // buying_price
                $row[7], // selling_price
                $row[8], // margin
                $row[9], // profit
                $row[10], // driver_name
                $row[11], // client_location
                $row[12]  // payment_status
            );

            if ($stmt->execute()) {
                $successCount++;
            }
        }

        $stmt->close();
        $conn->close();

        /* =======================
           POST → REDIRECT → GET
        ======================= */
        header("Location: upload_waste_report.php?success=".$successCount);
        exit;

    } catch (Exception $e) {
        $message = " Error reading Excel file.";
    }
}

page:
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Upload Waste Report</title>
    <link rel="stylesheet" href="upload_waste.css">
</head>
<body>

<div id="toast" class="toast <?php echo $messageType; ?>">
    <?php echo htmlspecialchars($message); ?>
</div>

<script>
const toast = document.getElementById("toast");

if (toast && toast.textContent.trim() !== "") {
    toast.classList.add("show");

    // Auto hide after 10 seconds
    setTimeout(() => {
        toast.classList.remove("show");
    }, 10000);
}
</script>

<div class="container">
    <h2>Upload Waste Report</h2>

    <?php echo $message; ?>

    <form method="post" enctype="multipart/form-data">
        <input type="file" name="excel_file" accept=".csv,.xls,.xlsx" required>
        <br><br>
        <button type="submit" name="upload" class="btn">Upload File</button>
    </form>

    <div style="margin-top:20px;">
        <a href="country_materials.php" class="back-btn">Go Back</a>
    </div>
    <div style="margin-top: 20px;">
    <a href="view_reports.php" class="btn">
        View Uploaded Reports
    </a>
</div>

</div>

</body>
</html>
