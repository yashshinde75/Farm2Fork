<?php
session_start();
require_once "../config.php";
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
if (!isset($_GET['id'])) { echo "Order ID missing."; exit; }
$order_id = intval($_GET['id']);
$db = getDBConnection();

// Handle status update and delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action']==='update_status') {
        $status = $_POST['status'];
        $allowed = ['Pending','Out for Delivery','Delivered'];
        if (in_array($status, $allowed, true)) {
            pg_query_params($db, "UPDATE orders SET status=$1 WHERE id=$2", [$status, $order_id]);
        }
        header("Location: order-details.php?id=".$order_id);
        exit;
    }
    if (isset($_POST['action']) && $_POST['action']==='delete_order') {
        pg_query_params($db, "DELETE FROM order_items WHERE order_id=$1", [$order_id]);
        pg_query_params($db, "DELETE FROM orders WHERE id=$1", [$order_id]);
        header("Location: orders.php");
        exit;
    }
}

// Fetch order & items
$order_res = pg_query_params($db, "SELECT * FROM orders WHERE id=$1", [$order_id]);
$order = pg_fetch_assoc($order_res);
$items_res = pg_query_params($db, "SELECT * FROM order_items WHERE order_id=$1", [$order_id]);
$items = pg_fetch_all($items_res);
pg_close($db);
function esc($s){ return htmlspecialchars($s); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Order #<?= esc($order_id) ?> Details</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    font-family:Poppins,Arial;
    background:#f9f9f9
}

.title{
    color:#b42a14;
    font-weight:700
}

.btn-farm{
    background:#b42a14;
    color:#fff;
    padding:6px 14px;
    font-size:14px;
}

.btn-farm:hover{
    background:#a22310;
}

/* ✅ Prevent buttons from becoming huge on mobile */
@media (max-width: 576px){
  .btn,
  .btn-farm{
    font-size:13px !important;
    padding:6px 12px !important;
  }

  .status-form{
    flex-direction:column;
    align-items:stretch;
  }

  .status-form select,
  .status-form button{
    width:100%;
  }
}
</style>
</head>
<body>

<!-- ✅ NAVBAR -->
<nav class="navbar navbar-light bg-white shadow-sm px-3 px-md-4 d-flex justify-content-between align-items-center">
  <span class="navbar-brand fw-bold text-danger">Farm2Fork Admin</span>
  <div class="d-flex gap-2">
    <a href="orders.php" class="btn btn-outline-danger btn-sm">Back</a>
    <a href="login.php?logout=1" class="btn btn-outline-danger btn-sm">Logout</a>
  </div>
</nav>

<div class="container mt-4">

  <h2 class="title mb-3">Order #<?= esc($order_id) ?></h2>

  <!-- ✅ CUSTOMER DETAILS -->
  <div class="card p-3 mb-3">

    <h5 class="fw-bold text-danger">Customer</h5>

    <div class="row g-2">
      <div class="col-12 col-md-6"><strong>Name:</strong> <?= esc($order['name']) ?></div>
      <div class="col-12 col-md-6"><strong>Phone:</strong> <?= esc($order['phone']) ?></div>

      <div class="col-12">
        <strong>Address:</strong><br>
        <?= nl2br(esc($order['address'])) ?>
      </div>

      <div class="col-12 col-md-6"><strong>Payment:</strong> <?= esc($order['payment_method']) ?></div>
      <div class="col-12 col-md-6 fw-bold text-danger">
        <strong>Total:</strong> ₹<?= esc($order['total_amount']) ?>
      </div>

      <div class="col-12"><strong>Date:</strong> <?= esc($order['order_date']) ?></div>
    </div>

    <!-- ✅ STATUS UPDATE -->
    <div class="mt-3">
      <form method="post" class="d-flex gap-2 align-items-center status-form">
        <input type="hidden" name="action" value="update_status">

        <label class="form-label mb-0">Status:</label>

        <select name="status" class="form-select w-auto">
          <option<?= $order['status']==='Pending' ? ' selected':'' ?>>Pending</option>
          <option<?= $order['status']==='Out for Delivery' ? ' selected':'' ?>>Out for Delivery</option>
          <option<?= $order['status']==='Delivered' ? ' selected':'' ?>>Delivered</option>
        </select>

        <button class="btn btn-farm">Update</button>
      </form>
    </div>

    <!-- ✅ DELETE ORDER -->
    <form method="post" onsubmit="return confirm('Delete this order?');" class="mt-2">
      <input type="hidden" name="action" value="delete_order">
      <button class="btn btn-danger btn-sm">Delete Order</button>
    </form>

  </div>

  <!-- ✅ ORDER ITEMS -->
  <div class="card p-3">

    <h5 class="fw-bold text-danger mb-3">Order Items</h5>

    <?php if (!$items): ?>
      <div class="alert alert-warning">No items found.</div>
    <?php else: ?>

    <div class="table-responsive">
      <table class="table table-bordered table-sm">
        <thead class="table-danger">
          <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Qty (KG)</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($items as $it): ?>
          <tr>
            <td><?= esc($it['product_name']) ?></td>
            <td>₹<?= esc($it['price']) ?></td>
            <td><?= esc($it['quantity']) ?></td>
            <td class="fw-bold text-danger">₹<?= esc($it['subtotal']) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <?php endif; ?>

  </div>

</div>

</body>
</html>
