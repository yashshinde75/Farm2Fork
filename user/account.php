<?php
// ✅ AUTH FIRST (single source of truth)
require_once __DIR__ . "/../auth.php";
require_login();

// ✅ DB
require_once __DIR__ . "/../config.php";

$db = getDBConnection();
$user_id = (int) $_SESSION['user_id'];

// ✅ SAFE QUERY
$res = pg_query_params(
    $db,
    "SELECT id, name, phone, created_at FROM users WHERE id = $1 LIMIT 1",
    [$user_id]
);

if (!$res || pg_num_rows($res) !== 1) {
    // Session invalid or user deleted → logout safely
    header("Location: logout.php");
    exit;
}

$user = pg_fetch_assoc($res);
pg_close($db);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Account — Farm2Fork</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#b42a14">
</head>

<body>

<!-- ✅ NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm fixed-top">
<div class="container">

    <button class="navbar-toggler d-lg-none me-2"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>

    <a class="navbar-brand fw-bold text-danger" href="../index.php">Farm2Fork</a>

    <!-- MOBILE ICONS -->
    <div class="mobile-icons d-lg-none">
        <a href="../cart.php" title="Cart"><i class="bi bi-cart3"></i></a>
        <a href="/user/account.php" title="My Account">
            <i class="bi bi-person-circle"></i>
        </a>
    </div>

    <!-- DESKTOP MENU -->
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">

            <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="../products.php">Products</a></li>
            <li class="nav-item"><a class="nav-link" href="../cart.php">Cart</a></li>

            <li class="nav-item d-none d-lg-block">
                <a class="nav-link p-0 active" href="/user/account.php">
                    <i class="bi bi-person-circle" style="color:#b42a14;font-size:22px;"></i>
                </a>
            </li>
        </ul>
    </div>
</div>
</nav>

<!-- MAIN CONTENT -->
<div class="container" style="margin-top:110px; max-width:720px;">
    <div class="card p-4 shadow-sm">

        <h3 class="text-danger mb-3">My Account</h3>

        <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p>
        <p class="text-muted">
            Member since: <?= date("F j, Y", strtotime($user['created_at'])) ?>
        </p>

        <div class="mt-4 d-flex gap-3">
            <a href="my-orders.php" class="btn btn-outline-danger px-4">My Orders</a>
            <a href="logout.php" class="btn btn-danger px-4">Logout</a>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
