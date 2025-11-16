<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$db = getDBConnection();
$user_id = intval($_SESSION['user_id']);

// fetch user's orders
$res = pg_query_params($db, "SELECT * FROM orders WHERE user_id = $1 ORDER BY order_date DESC", [$user_id]);
$orders = $res ? pg_fetch_all($res) : [];

pg_close($db);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>My Orders — Farm2Fork</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

<!-- NAVBAR -->
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

            <!-- Account Icon -->
            <li class="nav-item">
                <a class="nav-link p-0" href="account.php">
                    <i class="bi bi-person-circle"
                       style="font-size: 2rem; margin-left: 10px; color:#b42a14;"></i>
                </a>
            </li>

        </ul>
    </div>
</div>
</nav>



<!-- PAGE CONTENT -->
<div class="container" style="margin-top:110px; max-width:900px;">

  <h3 class="text-danger fw-bold mb-4">My Orders</h3>

  <?php if (empty($orders)): ?>
    <div class="alert alert-warning">You have no orders yet.</div>
    <a href="../products.php" class="btn btn-danger">Browse Products</a>

  <?php else: ?>
    <div class="list-group">
      <?php foreach ($orders as $o): ?>
        <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
           href="order-view.php?id=<?= $o['id'] ?>">

          <div>
            <div><strong>Order #<?= $o['id'] ?></strong> — <?= htmlspecialchars($o['order_date']) ?></div>
            <div class="text-muted">Status: <?= htmlspecialchars($o['status'] ?? 'Pending') ?></div>
          </div>

          <div class="text-danger fw-bold">₹ <?= htmlspecialchars($o['total_amount']) ?></div>

        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
