<?php  
$conn = new mysqli("localhost", "root", "", "test");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all registration data
$result = $conn->query("SELECT * FROM registration ORDER BY created_at DESC");

// Searchable fields
$searchFields = ['firstname','lastname','company','location'];

// Display fields 
$fields = ['firstname','lastname','company','registration','postalAddress','location','email','phone','physicalAddress','created_at'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Registered Users</title>
    <link rel="stylesheet" href="admin_view.css">
    <link rel="icon" type="image/jpeg" href="favicon.jpg">

</head>
<body>
<div class="container">
    <h2>Registered Users</h2>

    <!-- SEARCH BAR -->
    <div class="search-container">
        <?php foreach($searchFields as $index => $f): ?>
            <div>
                <label><?php echo ucfirst(str_replace('_',' ',$f)); ?></label>
                <input type="text" placeholder="Search <?php echo ucfirst($f); ?>" data-column="<?php echo $index; ?>" onkeyup="filterTable()">
            </div>
        <?php endforeach; ?>
    </div>

    <!-- TOP ACTION BAR -->
    <div class="top-action-bar" id="topActionBar">
        <span class="count" id="selectedCount">0 selected</span>
        <button class="delete-selected" id="btnDeleteSelected">Delete Selected</button>
    </div>

    <!-- TABLE -->
    <div class="table-container">
        <table id="userTable">
            <thead>
            <tr>
                <th></th>
                <?php foreach($fields as $f): ?>
                    <th><?php echo ucfirst(str_replace('_',' ',$f)); ?></th>
                <?php endforeach; ?>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $result->data_seek(0);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo "<tr data-id='".$row['id']."'>";
                    echo "<td style='position:relative;'><input type='checkbox' class='row-checkbox' data-id='".$row['id']."'></td>";
                    foreach($fields as $field){
                        if($field == 'created_at'){
                            $date = isset($row[$field]) ? date("d-m-Y", strtotime($row[$field])) : '';
                            echo "<td>$date</td>";
                        } elseif($field == 'location'){
                            $loc = trim($row[$field]) !== '' ? htmlspecialchars($row[$field]) : '-';
                            echo "<td>$loc</td>";
                        } else {
                            echo "<td>".(isset($row[$field]) ? htmlspecialchars($row[$field]) : '')."</td>";
                        }
                    }
                    echo "<td class='menu-cell' style='position:relative;'>
                            <div class='action-menu'>
                                <span class='dots' title='Actions'>⋮</span>
                                <div class='dropdown-content'>
                                    <a href='edit_user.php?id=".$row['id']."'>Edit</a>
                                    <a href='delete_user.php?id=".$row['id']."' onclick=\"return confirm('Are you sure you want to delete this user?');\">Delete</a>
                                </div>
                            </div>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='".(count($fields)+2)."'>No users found</td></tr>";
            }
            $conn->close();
            ?>
            </tbody>
        </table>
    </div>

    <a href="country_materials.php" class="go-back">Go Back</a>
</div>

<script>
// 3 DOTS DROPDOWN MENU
document.querySelectorAll(".action-menu").forEach(menu => {
    const dots = menu.querySelector(".dots");
    const drop = menu.querySelector(".dropdown-content");

    dots.addEventListener("click", (e) => {
        e.stopPropagation();
        document.querySelectorAll(".dropdown-content").forEach(d => {
            if (d !== drop) d.style.display = "none";
        });
        drop.style.display = drop.style.display === "block" ? "none" : "block";
    });
});
document.addEventListener("click", () => {
    document.querySelectorAll(".dropdown-content").forEach(d => d.style.display = "none");
});

// MULTIPLE SELECTION
const checkboxes = document.querySelectorAll(".row-checkbox");
const topBar = document.getElementById("topActionBar");
const countDisplay = document.getElementById("selectedCount");
const deleteBtn = document.getElementById("btnDeleteSelected");

function updateSelectedCount() {
    const selected = document.querySelectorAll(".row-checkbox:checked").length;
    if (selected > 0) {
        topBar.style.display = "flex";
        countDisplay.textContent = selected + " selected";
    } else {
        topBar.style.display = "none";
    }
}
checkboxes.forEach(cb => cb.addEventListener("change", updateSelectedCount));

deleteBtn.addEventListener("click", () => {
    const ids = [...document.querySelectorAll(".row-checkbox:checked")].map(cb => cb.dataset.id);
    if (ids.length === 0) return;
    if (!confirm("Are you sure you want to delete selected users?")) return;
    window.location.href = "delete_user.php?ids=" + ids.join(",");
});

// SEARCH FILTER
function filterTable() {
    const inputs = document.querySelectorAll(".search-container input");
    const rows = document.querySelectorAll("#userTable tbody tr");

    // Map search input index -> table column index
    const columnMap = { 0: 1, 1: 2, 2: 3, 3: 6 }; 
    rows.forEach(row => {
        let showRow = true;

        inputs.forEach((input, i) => {
            const value = input.value.toLowerCase().trim();
            const colIndex = columnMap[i];
            const cell = row.children[colIndex];

            if (value && cell) {
                let cellText = cell.textContent.toLowerCase().trim();
                if (!cellText.includes(value)) {
                    showRow = false;
                }
            }
        });

        row.style.display = showRow ? "" : "none";
    });
}
</script>

</body>
</html>
