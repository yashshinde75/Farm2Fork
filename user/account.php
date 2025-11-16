<?php
session_start();
require_once '../config.php';

// If user not logged in → redirect
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$db = getDBConnection();
$user_id = intval($_SESSION['user_id']);

// FIXED: Use name instead of full_name
$res = pg_query_params($db, "SELECT id, name, phone, created_at FROM users WHERE id=$1", [$user_id]);
$user = pg_fetch_assoc($res);

pg_close($db);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Account — Farm2Fork</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

<!-- Navbar (Same as website) -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm fixed-top">
<div class="container">
    <a class="navbar-brand fw-bold text-danger" href="../index.php">Farm2Fork</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">

            <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="../about.php">About</a></li>
            <li class="nav-item"><a class="nav-link" href="../how-it-works.php">How It Works</a></li>
            <li class="nav-item"><a class="nav-link" href="../products.php">Products</a></li>
            <li class="nav-item"><a class="nav-link" href="../cart.php">Cart</a></li>
            <li class="nav-item">
              <a class="nav-link btn btn-danger text-white ms-2" href="../contact.php">
                Partner With Us
              </a>
          </li>
            <li class="nav-item">
                <a class="nav-link p-0 active" href="account.php">
                    <i class="bi bi-person-circle" style="font-size: 2rem; margin-left: 10px; color:#b42a14;"></i>
                </a>
            </li>

        </ul>
    </div>
</div>
</nav>

<!-- Main Content -->
<div class="container" style="margin-top:110px; max-width:720px;">
    <div class="card p-4 shadow-sm">

        <h3 class="text-danger mb-3">My Account</h3>

        <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p>
        <p class="text-muted">
            Member since: <?= date("F j, Y", strtotime($user['created_at'])) ?>
        </p>

        <div class="mt-4">
            <a href="my-orders.php" class="btn btn-outline-danger">My Orders</a>
            <a href="logout.php" class="btn btn-danger ms-2">Logout</a>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
