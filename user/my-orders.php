<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$db = getDBConnection();
$user_id = intval($_SESSION['user_id']);

// ✅ Fetch user's orders
$res = pg_query_params($db, "SELECT * FROM orders WHERE user_id = $1 ORDER BY order_date DESC", [$user_id]);
$orders = $res ? pg_fetch_all($res) : [];

pg_close($db);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>My Orders — Farm2Fork</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#b42a14">

  <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

<!-- ✅ FINAL UPDATED NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm fixed-top">
  <div class="container">

    <!-- ✅ HAMBURGER -->
    <button class="navbar-toggler d-lg-none me-2" 
            type="button" 
            data-bs-toggle="collapse" 
            data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- ✅ LOGO -->
    <a class="navbar-brand fw-bold text-danger" href="../index.php">
      Farm2Fork
    </a>

    <!-- ✅ MOBILE ICONS -->
    <div class="mobile-icons d-lg-none">

      <!-- ✅ ADD TO HOME -->
      <button type="button" title="Add to Home" onclick="showA2HS()">
        <i class="bi bi-house-add"></i>
      </button>

      <!-- ✅ CART -->
      <a href="../cart.php" title="Cart">
        <i class="bi bi-cart3"></i>
      </a>

      <!-- ✅ ACCOUNT (BLACK) -->
      <a href="account.php" title="My Account">
        <i class="bi bi-person-circle"></i>
      </a>

    </div>

    <!-- ✅ DESKTOP MENU -->
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

        <!-- ✅ DESKTOP ACCOUNT ICON (BLACK) -->
        <li class="nav-item d-none d-lg-block">
          <a class="nav-link p-0" href="account.php">
            <i class="bi bi-person-circle account-icon"></i>
          </a>
        </li>

      </ul>
    </div>
  </div>
</nav>

<!-- ✅ PAGE CONTENT -->
<div class="container" style="margin-top:120px; max-width:900px;">

  <h3 class="text-danger fw-bold mb-4 text-center text-md-start">
    My Orders
  </h3>

  <?php if (empty($orders)): ?>

    <div class="alert alert-warning text-center">
      You have no orders yet.
    </div>

    <div class="text-center">
      <a href="../products.php" class="btn btn-danger">
        Browse Products
      </a>
    </div>

  <?php else: ?>

    <div class="list-group">

      <?php foreach ($orders as $o): ?>

        <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
           href="order-view.php?id=<?= $o['id'] ?>">

          <div>
            <div>
              <strong>Order #<?= $o['id'] ?></strong>
              — <?= htmlspecialchars($o['order_date']) ?>
            </div>

            <div class="text-muted">
              Status: <?= htmlspecialchars($o['status'] ?? 'Pending') ?>
            </div>
          </div>

          <div class="text-danger fw-bold">
            ₹ <?= htmlspecialchars($o['total_amount']) ?>
          </div>

        </a>

      <?php endforeach; ?>

    </div>

  <?php endif; ?>

</div>

<!-- ✅ SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/pwa.js"></script>

</body>
</html>
