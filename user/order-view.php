<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("Invalid order ID");
}

$order_id = intval($_GET['id']);
$user_id  = intval($_SESSION['user_id']);

$db = getDBConnection();

// ✅ Fetch order
$sql = "SELECT * FROM orders WHERE id = $1 AND user_id = $2 LIMIT 1";
$res = pg_query_params($db, $sql, [$order_id, $user_id]);
$order = pg_fetch_assoc($res);

if (!$order) {
    die("Order not found");
}

// ✅ Fetch order items
$items_res = pg_query_params($db, "SELECT * FROM order_items WHERE order_id = $1", [$order_id]);
$items = $items_res ? pg_fetch_all($items_res) : [];

pg_close($db);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Order #<?= $order_id ?> — Farm2Fork</title>
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
      Order #<?= $order_id ?>
    </h3>

    <!-- ✅ ORDER SUMMARY -->
    <div class="card p-4 shadow-sm mb-4">

        <div class="row">
            
            <div class="col-md-6">
                <p><strong>Order Date:</strong> <?= htmlspecialchars($order['order_date']) ?></p>
                <p><strong>Total Amount:</strong> ₹ <?= htmlspecialchars($order['total_amount']) ?></p>
            </div>

            <div class="col-md-6">
                <p><strong>Payment:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>

                <p>
                    <strong>Status:</strong>

                    <?php if ($order['status'] === "Delivered"): ?>
                        <span class="badge bg-success">Delivered</span>

                    <?php elseif ($order['status'] === "Cancelled"): ?>
                        <span class="badge bg-danger">Cancelled</span>

                    <?php else: ?>
                        <span class="badge bg-warning text-dark">Pending</span>
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <p>
          <strong>Delivery Address:</strong><br>
          <?= nl2br(htmlspecialchars($order['address'])) ?>
        </p>

    </div>

    <!-- ✅ ORDER ITEMS -->
    <div class="card p-4 shadow-sm">

        <h5 class="fw-bold mb-3">Items in this Order</h5>

        <div class="table-responsive">
          <table class="table table-bordered">
              <thead class="table-danger">
                  <tr>
                      <th>Product</th>
                      <th>Price (₹)</th>
                      <th>Qty (KG)</th>
                      <th>Subtotal (₹)</th>
                  </tr>
              </thead>

              <tbody>
                  <?php foreach ($items as $item): ?>
                  <tr>
                      <td><?= htmlspecialchars($item['product_name']) ?></td>
                      <td>₹ <?= htmlspecialchars($item['price']) ?></td>
                      <td><?= htmlspecialchars($item['quantity']) ?></td>
                      <td class="fw-bold">₹ <?= htmlspecialchars($item['subtotal']) ?></td>
                  </tr>
                  <?php endforeach; ?>
              </tbody>
          </table>
        </div>

        <div class="text-end fw-bold fs-5 mt-3">
            Total: ₹ <?= htmlspecialchars($order['total_amount']) ?>
        </div>

    </div>

    <!-- ✅ BUTTONS -->
    <div class="mt-4 d-flex flex-column flex-sm-row justify-content-between gap-2">

        <a href="my-orders.php" class="btn btn-outline-danger">
          Back to My Orders
        </a>

        <?php if ($order['status'] === "Pending"): ?>
        <a href="cancel-order.php?id=<?= $order_id ?>" 
           class="btn btn-danger"
           onclick="return confirm('Are you sure you want to cancel this order?');">
           Cancel Order
        </a>
        <?php endif; ?>

    </div>

</div>

<!-- ✅ SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/pwa.js"></script>

</body>
</html>
