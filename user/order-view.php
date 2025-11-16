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
$user_id = intval($_SESSION['user_id']);

$db = getDBConnection();

// Fetch order
$sql = "SELECT * FROM orders WHERE id = $1 AND user_id = $2 LIMIT 1";
$res = pg_query_params($db, $sql, [$order_id, $user_id]);
$order = pg_fetch_assoc($res);

if (!$order) {
    die("Order not found");
}

// Fetch order items
$items_res = pg_query_params($db, "SELECT * FROM order_items WHERE order_id = $1", [$order_id]);
$items = $items_res ? pg_fetch_all($items_res) : [];

pg_close($db);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Order #<?= $order_id ?> — Farm2Fork</title>

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
            <li class="nav-item">
              <a class="nav-link btn btn-danger text-white ms-2" href="contact.php">
                Partner With Us
              </a>
          </li>

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

    <h3 class="text-danger fw-bold mb-4">Order #<?= $order_id ?></h3>

    <!-- Order summary -->
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

        <p><strong>Delivery Address:</strong><br><?= nl2br(htmlspecialchars($order['address'])) ?></p>

    </div>

    <!-- Order items -->
    <div class="card p-4 shadow-sm">

        <h5 class="fw-bold mb-3">Items in this Order</h5>

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

        <div class="text-end fw-bold fs-5 mt-3">
            Total: ₹ <?= htmlspecialchars($order['total_amount']) ?>
        </div>

    </div>

    <!-- Buttons -->
    <div class="mt-4 d-flex justify-content-between align-items-center">

        <a href="my-orders.php" class="btn btn-outline-danger">Back to My Orders</a>

        <!-- Cancel Button (only if pending) -->
        <?php if ($order['status'] === "Pending"): ?>
        <a href="cancel-order.php?id=<?= $order_id ?>" 
           class="btn btn-danger"
           onclick="return confirm('Are you sure you want to cancel this order?');">
           Cancel Order
        </a>
        <?php endif; ?>

    </div>

</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
