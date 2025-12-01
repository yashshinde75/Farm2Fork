<?php
session_start();
require_once '../config.php';

// BLOCK if not logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit;
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin-login.php");
    exit;
}

// Fetch contacts
$db = getDBConnection();
$result = pg_query($db, "SELECT * FROM contacts ORDER BY created_at DESC");
$contacts = pg_fetch_all($result);
pg_close($db);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Messages — Farm2Fork Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body>

<!-- ✅ RESPONSIVE NAVBAR -->
<nav class="navbar navbar-light bg-light shadow-sm">
    <div class="container d-flex align-items-center justify-content-between">
        <a class="navbar-brand text-danger fw-bold" href="dashboard.php">
            Farm2Fork Admin
        </a>

        <div class="d-flex gap-2">
            <a href="dashboard.php" class="btn btn-outline-danger btn-sm">
                Dashboard
            </a>
            <a href="admin-login.php?logout=1" class="btn btn-outline-danger btn-sm">
                Logout
            </a>
        </div>
    </div>
</nav>

<main class="container py-4">

    <h3 class="text-danger fw-bold mb-4 text-center text-md-start">
        All Contact Submissions
    </h3>

    <?php if (!$contacts): ?>

        <p class="text-muted text-center">No messages found.</p>

    <?php else: ?>

    <!-- ✅ RESPONSIVE TABLE -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle small">
            <thead class="table-danger">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Message</th>
                    <th>Submitted At</th>
                </tr>
            </thead>

            <tbody>
            <?php foreach ($contacts as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c['id']); ?></td>
                    <td><?= htmlspecialchars($c['name']); ?></td>
                    <td><?= htmlspecialchars($c['phone']); ?></td>
                    <td><?= htmlspecialchars($c['email']); ?></td>
                    <td><?= htmlspecialchars($c['type']); ?></td>
                    <td style="max-width:240px; white-space:normal;">
                        <?= htmlspecialchars($c['message']); ?>
                    </td>
                    <td class="text-nowrap">
                        <?= htmlspecialchars($c['created_at']); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>

        </table>
    </div>

    <?php endif; ?>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
