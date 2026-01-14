<?php
// Database connection
$conn = new mysqli("localhost","root","","country_materials");
if($conn->connect_error) die("Database connection failed");

// Fetch users
$users = $conn->query("SELECT id, firstname, lastname, email, company, registration, location, status FROM registration ORDER BY id DESC");

// Fetch activity logs if table exists
$logs = [];
$logCheck = $conn->query("SHOW TABLES LIKE 'system_logs'");
if($logCheck->num_rows>0){
    $logRes = $conn->query("SELECT user, action, page, created_at FROM system_logs ORDER BY created_at DESC LIMIT 50");
    while($row = $logRes->fetch_assoc()) $logs[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Management Dashboard</title>
<link rel="stylesheet" href="admin_style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="container">
    <h1>Admin Management Dashboard</h1>

    <!-- USERS TABLE -->
    <section class="section">
        <h2>System Users</h2>
        <div class="table-wrapper">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Company</th>
                        <th>Registration</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($users->num_rows>0): 
                        while($u=$users->fetch_assoc()): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><?= htmlspecialchars($u['firstname']." ".$u['lastname']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= htmlspecialchars($u['company']) ?></td>
                        <td><?= htmlspecialchars($u['registration']) ?></td>
                        <td><?= htmlspecialchars($u['location'] ?: '-') ?></td>
                        <td>
                            <span class="status-badge <?= $u['status']=='Active'?'active':'inactive' ?>">
                                <?= $u['status'] ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn edit" onclick="location.href='edit_user.php?id=<?= $u['id'] ?>'"><i class="fas fa-edit"></i></button>
                            <button class="btn toggle" onclick="alert('Toggle status for <?= $u['firstname'] ?>')"><i class="fas fa-toggle-on"></i></button>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr><td colspan="8">No users found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- ACTIVITY LOGS -->
    <section class="section">
        <h2>Activity Logs</h2>
        <?php if(count($logs)>0): ?>
        <div class="table-wrapper">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Page</th>
                        <th>Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($logs as $l): ?>
                    <tr>
                        <td><?= htmlspecialchars($l['user']) ?></td>
                        <td><?= htmlspecialchars($l['action']) ?></td>
                        <td><?= htmlspecialchars($l['page']) ?></td>
                        <td><?= $l['created_at'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <p class="no-logs">No activity logs found.</p>
        <?php endif; ?>
    </section>
</div>

</body>
</html>
