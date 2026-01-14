<?php 
$conn = new mysqli("localhost", "root", "", "country_materials");
if ($conn->connect_error) die("Database connection failed: " . $conn->connect_error);

// =======================
// HANDLE FORM SUBMISSION
// =======================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {

    $reportSession = "REP-" . date("Ymd-His");
    $reportName = $_POST['report_name'];

    $stmt = $conn->prepare(
        "INSERT INTO daily_reports
        (report_session, report_name, customer_name, contact_name, contact_person,
         email, phone, summary, remarks)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    foreach ($_POST['customer_name'] as $i => $customer) {
        $stmt->bind_param(
            "sssssssss",
            $reportSession,
            $reportName,
            $_POST['customer_name'][$i],
            $_POST['contact_name'][$i],
            $_POST['contact_person'][$i],
            $_POST['email'][$i],
            $_POST['phone'][$i],
            $_POST['summary'][$i],
            $_POST['remarks'][$i]
        );
        $stmt->execute();
    }

    $stmt->close();
    header("Location: daily-report.php?success=1");
    exit;
}

// =======================
// SUCCESS MESSAGE
// =======================
$message = "";
if (isset($_GET['success'])) $message = "✅ Daily Report Saved Successfully!";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Daily Customer Report</title>
<link rel="stylesheet" href="daily-report.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<?php if($message): ?>
<div class="toast show"><?= $message ?></div>
<script>
setTimeout(()=>{document.querySelector('.toast').classList.remove('show');},4000);
</script>
<?php endif; ?>

<div class="container">
    <div class="header-container">
        <div class="header-text">
            <h2>Daily Customer Report</h2>
            <p>Enter daily customer interactions and updates below.</p>
        </div>
        <div>
            <a href="view_daily_reports.php" class="view-btn"><i class="fas fa-eye"></i> View Uploaded Reports</a>
            <a href="country_materials.php" class="back-btn"><i class="fas fa-arrow-left"></i> Go Back</a>
        </div>
    </div>

    <!-- Report Name Input -->
    <div class="report-name-input">
        <label>Report Name</label>
        <input type="text" name="report_name" placeholder="e.g. DSM Customer Follow-up – Jan 10" required>
    </div>

    <form method="POST">
        <div class="table-responsive">
            <table id="reportTable">
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Contact Name</th>
                        <th>Contact Person</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Summary</th>
                        <th>Remarks</th>
                        <th style="text-align:center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" name="customer_name[]" required></td>
                        <td><input type="text" name="contact_name[]"></td>
                        <td><input type="text" name="contact_person[]"></td>
                        <td><input type="email" name="email[]"></td>
                        <td><input type="tel" name="phone[]"></td>
                        <td><input type="text" name="summary[]"></td>
                        <td><input type="text" name="remarks[]"></td>
                        <td style="text-align:center;"><small style="color:#ccc;">Default</small></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="margin-top:20px;">
            <button type="button" class="add-btn-top" onclick="addRow()"><i class="fas fa-plus"></i> Add Row</button>
        </div>

        <div style="margin-top:20px;">
            <button type="submit" name="save" class="save-btn">Save Report</button>
        </div>
    </form>
</div>

<script>
function addRow(){
    const table = document.getElementById("reportTable").getElementsByTagName('tbody')[0];
    const newRow = table.insertRow();
    newRow.innerHTML = `
        <td><input type="text" name="customer_name[]" required></td>
        <td><input type="text" name="contact_name[]"></td>
        <td><input type="text" name="contact_person[]"></td>
        <td><input type="email" name="email[]"></td>
        <td><input type="tel" name="phone[]"></td>
        <td><input type="text" name="summary[]"></td>
        <td><input type="text" name="remarks[]"></td>
        <td style="text-align:center;">
            <button type="button" class="remove-btn" onclick="removeRow(this)"><i class="fas fa-trash"></i></button>
        </td>
    `;
}
function removeRow(btn){ btn.closest('tr').remove(); }
</script>

</body>
</html>
