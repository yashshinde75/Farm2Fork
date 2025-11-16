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

// Handle status update and delete from details page
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

// fetch order & items
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
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{font-family:Poppins,Arial;background:#f9f9f9}
.title{color:#b42a14}
.btn-farm{background:#b42a14;color:#fff}
</style>
</head>
<body>

<nav class="navbar navbar-light bg-white shadow-sm px-4">
  <span class="navbar-brand fw-bold text-danger">Farm2Fork Admin</span>
  <div>
    <a href="orders.php" class="btn btn-outline-danger">Back</a>
    <a href="login.php?logout=1" class="btn btn-outline-danger">Logout</a>
  </div>
</nav>

<div class="container mt-4">
  <h2 class="title">Order #<?= esc($order_id) ?></h2>

  <div class="card p-3 mb-3">
    <h5 class="fw-bold text-danger">Customer</h5>
    <p><strong>Name:</strong> <?= esc($order['name']) ?></p>
    <p><strong>Phone:</strong> <?= esc($order['phone']) ?></p>
    <p><strong>Address:</strong><br><?= nl2br(esc($order['address'])) ?></p>
    <p><strong>Payment:</strong> <?= esc($order['payment_method']) ?></p>
    <p class="fw-bold text-danger"><strong>Total:</strong> ₹<?= esc($order['total_amount']) ?></p>
    <p><strong>Date:</strong> <?= esc($order['order_date']) ?></p>

    <div class="mt-3">
      <form method="post" class="d-flex gap-2 align-items-center">
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

    <form method="post" onsubmit="return confirm('Delete this order?');" class="mt-2">
      <input type="hidden" name="action" value="delete_order">
      <button class="btn btn-danger">Delete Order</button>
    </form>
  </div>

  <div class="card p-3">
    <h5 class="fw-bold text-danger mb-3">Order Items</h5>
    <?php if (!$items): ?>
      <div class="alert alert-warning">No items found.</div>
    <?php else: ?>
      <table class="table table-bordered">
        <thead class="table-danger"><tr><th>Product</th><th>Price</th><th>Qty (KG)</th><th>Subtotal</th></tr></thead>
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
    <?php endif; ?>
  </div>
</div>

</body>
</html>
