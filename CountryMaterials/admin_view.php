<?php 
$conn = new mysqli("localhost", "root", "", "test");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all registration data
$result = $conn->query("SELECT * FROM registration ORDER BY created_at DESC");

// Searchable fields
$searchFields = ['id','firstname','lastname','company','created_at'];

// Get table fields
$fields = [];
while ($field = $result->fetch_field()) {
    if($field->name !== 'id') {  // Exclude ID
        $fields[] = $field->name;
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Registered Users</title>
    <style>
       /* ====== Modernized Styles ====== */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f5f7fa;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 100vh;
}

.container {
    width: 95%;
    max-width: 1200px;
    margin-top: 40px;
    background: #fff;
    padding: 25px 35px;
    border-radius: 15px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
}

h2 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
    font-weight: 600;
}

.search-container {
    margin-bottom: 16px;
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    justify-content: flex-start;
    background: #f9f9f9;
    padding: 15px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.search-container div {
    flex: 1 1 180px;
    display: flex;
    flex-direction: column;
}

.search-container label {
    font-weight: 600;
    margin-bottom: 6px;
    font-size: 13px;
    color: #555;
}

.search-container input {
    padding: 10px 14px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 14px;
    transition: all 0.2s ease;
}

.search-container input:focus {
    border-color: #4CAF50;
    box-shadow: 0 0 10px rgba(76, 175, 80, 0.2);
    outline: none;
}

@media (max-width: 900px) { 
    .search-container div { flex: 1 1 45%; } 
}
@media (max-width: 500px) { 
    .search-container div { flex: 1 1 100%; } 
}

.top-action-bar {
    display: none;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
    padding: 12px 14px;
    border-radius: 10px;
    background: #fff3f3;
    box-shadow: 0 3px 12px rgba(0,0,0,0.05);
    border: 1px solid #ffdad9;
}

.top-action-bar .count {
    font-weight: 600;
    color: #333;
}

.top-action-bar button {
    padding: 8px 14px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    color: white;
    transition: all 0.2s ease;
}

.top-action-bar .delete-selected {
    background: #f44336;
}

.top-action-bar .delete-selected:hover {
    background: #d32f2f;
}

.table-container {
    max-height: 520px;
    overflow-y: auto;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

table {
    width: 100%;
    border-collapse: collapse;
    min-width: 800px;
}

th, td {
    padding: 12px 14px;
    border-bottom: 1px solid #eee;
    text-align: center;
    position: relative;
    white-space: nowrap;
    font-size: 14px;
}

th {
    position: sticky;
    top: 0;
    background: #4CAF50;
    color: white;
    font-weight: 600;
    z-index: 3;
}

tr {
    transition: background 0.2s;
}

tr:hover {
    background: #fdfdfd;
}

.row-checkbox {
    display: inline-block;
    transform: translateY(-50%);
    position: absolute;
    left: 10px;
    top: 50%;
    visibility: hidden;
    opacity: 0;
    transition: visibility 0s linear 0.08s, opacity 0.08s linear;
    cursor: pointer;
    width: 18px;
    height: 18px;
}

tr:hover .row-checkbox {
    visibility: visible;
    opacity: 1;
    transition-delay: 0s;
}

.row-checkbox:checked {
    visibility: visible !important;
    opacity: 1 !important;
    transition-delay: 0s;
}

.dots {
    visibility: hidden;
    opacity: 0;
    transition: opacity 0.08s, visibility 0.08s;
    padding: 6px 8px;
    border-radius: 4px;
    cursor: pointer;
}

tr:hover .dots {
    visibility: visible;
    opacity: 1;
}

.action-menu {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    top: 32px;
    background: #fff;
    min-width: 140px;
    border-radius: 6px;
    box-shadow: 0 8px 18px rgba(0,0,0,0.12);
    z-index: 50;
}

.dropdown-content a {
    display: block;
    padding: 10px 14px;
    color: #333;
    text-decoration: none;
    font-size: 14px;
    transition: background 0.15s ease;
}

.dropdown-content a:hover {
    background: #f5f5f5;
}

td.menu-cell {
    width: 64px;
}

.go-back {
    display: inline-block;
    margin-top: 20px;
    padding: 12px 24px;
    background: #4CAF50;
    color: white;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    font-size: 15px;
    transition: all 0.2s ease;
}

.go-back:hover {
    background: #45a049;
}

td:first-child, th:first-child {
    width: 40px;
}

@media (max-width: 700px) {
    table { font-size: 13px; }
    .dropdown-content { min-width: 120px; }
}

    </style>
</head>
<body>
<div class="container">
    <h2>Registered Users</h2>

    <!-- SEARCH BAR -->
    <div class="search-container">
        <?php foreach($searchFields as $index => $f): ?>
            <div>
                <label><?php echo ucfirst($f); ?></label>
                <?php if($f == 'created_at'): ?>
                    <input type="date" data-column="<?php echo $index; ?>" oninput="filterTable()">
                <?php else: ?>
                    <input type="text" placeholder="Search <?php echo ucfirst($f); ?>" data-column="<?php echo $index; ?>" onkeyup="filterTable()">
                <?php endif; ?>
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
                    <th><?php echo $f; ?></th>
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
                            echo "<td>".date('Y-m-d', strtotime($row[$field]))."</td>";
                        } else {
                            echo "<td>".htmlspecialchars($row[$field])."</td>";
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
function getRowCheckboxes(){ return Array.from(document.querySelectorAll('.row-checkbox')); }
function updateTopBar(){
    const checked = getRowCheckboxes().filter(cb => cb.checked);
    const topBar = document.getElementById('topActionBar');
    const count = document.getElementById('selectedCount');
    if(checked.length > 0){
        topBar.style.display = 'flex';
        count.innerText = checked.length + (checked.length === 1 ? ' selected' : ' selected');
    } else {
        topBar.style.display = 'none';
        count.innerText = '0 selected';
    }
}
document.addEventListener('DOMContentLoaded', function(){
    getRowCheckboxes().forEach(cb => { cb.addEventListener('change', updateTopBar); });
    document.querySelectorAll('.action-menu').forEach(menu => {
        const dots = menu.querySelector('.dots');
        const dropdown = menu.querySelector('.dropdown-content');
        dots.addEventListener('click', function(e){
            e.stopPropagation();
            document.querySelectorAll('.dropdown-content').forEach(d => { if(d!==dropdown)d.style.display='none'; });
            dropdown.style.display = (dropdown.style.display==='block') ? 'none' : 'block';
        });
    });
    window.addEventListener('click', function(){ document.querySelectorAll('.dropdown-content').forEach(d => d.style.display='none'); });
});

// ✅ Fix: Use delete_multiple.php for bulk delete
document.getElementById('btnDeleteSelected').addEventListener('click', function(){
    const checked = getRowCheckboxes().filter(cb => cb.checked).map(cb => cb.dataset.id);
    if(checked.length===0) return;
    const ok = confirm('Are you sure you want to delete the selected user(s)? This action cannot be undone.');
    if(!ok) return;
    window.location.href = 'delete_multiple.php?ids=' + checked.join(',');
});

// SEARCH / FILTER
function filterTable(){
    const table = document.getElementById("userTable");
    const tr = table.getElementsByTagName("tr");
    const inputs = document.querySelectorAll(".search-container input");
    let filters = [];
    inputs.forEach(input=>filters.push(input.value.toLowerCase()));
    for(let i=1;i<tr.length;i++){
        const td = tr[i].getElementsByTagName("td");
        if(td.length===0) continue;
        let show=true;
        for(let j=0;j<inputs.length;j++){
            if(filters[j]){
                let cellIndex=j+1;
                let cellText = td[cellIndex].innerText.toLowerCase();
                if(inputs[j].type==='date'){
                    if(td[cellIndex].innerText.trim()!==filters[j]){ show=false; break; }
                } else {
                    if(cellText.indexOf(filters[j])===-1){ show=false; break; }
                }
            }
        }
        tr[i].style.display = show ? "" : "none";
    }
}
</script>

</body>
</html>
