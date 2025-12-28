<?php
session_start();
$user_id = $_SESSION['user_id'] ?? null;

require_once 'config.php';

$prefill_name = '';
$prefill_phone = '';
$prefill_address = '';

if ($user_id !== null) {
    $db = getDBConnection();
    $res = pg_query_params(
        $db,
        "SELECT name, phone, address FROM orders WHERE user_id = $1 ORDER BY order_date DESC LIMIT 1",
        [(int)$user_id]
    );
    $last_order = $res ? pg_fetch_assoc($res) : null;
    pg_close($db);

    if ($last_order) {
        $prefill_name = (string)($last_order['name'] ?? '');
        $prefill_phone = (string)($last_order['phone'] ?? '');
        $prefill_address = (string)($last_order['address'] ?? '');
    }
}
$cart = $_SESSION['cart'] ?? [];

// If cart is empty → return to products
if(empty($cart)){
    header("Location: products.php");
    exit;
}

$total = 0;
foreach($cart as $item){
    $total += $item['price'] * $item['quantity'];
}
$minOrderQty = 200;
$totalKg = 0;

foreach ($cart as $item) {
    $totalKg += $item['quantity'];
}

$qtyError = '';
if ($totalKg < $minOrderQty) {
    $qtyError = "Minimum order quantity is {$minOrderQty} KG. Your current total is {$totalKg} KG.";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Checkout - Farm2Fork</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#b42a14">
<link rel="stylesheet" href="assets/css/style.css">

<style>
  @media (max-width: 576px) {
    .checkout-title {
      font-size: 1.4rem;
      text-align: center;
    }
  }
</style>
</head>

<body>

<!-- ✅ NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm fixed-top">
  <div class="container">

    <a class="navbar-brand fw-bold text-danger" href="index.php">
      Farm2Fork
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">

        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="how-it-works.php">How It Works</a></li>
        <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
        <li class="nav-item"><a class="nav-link active fw-bold text-danger" href="cart.php">Cart</a></li>

        <li class="nav-item">
          <a class="nav-link btn btn-danger text-white ms-2" href="contact.php">
            Partner With Us
          </a>
        </li>
      </ul>
    </div>

  </div>
</nav>

<!-- ✅ CHECKOUT CONTENT -->
<div class="container py-5" style="margin-top: 90px;">

  <h2 class="text-danger fw-bold mb-4 checkout-title">
    🧾 Checkout
  </h2>

  <div class="row g-4">

    <!-- ✅ LEFT: CUSTOMER FORM -->
    <div class="col-12 col-md-7">
      <div class="card shadow-sm p-4">

        <h4 class="fw-bold mb-3">Customer Details</h4>
        <?php if ($qtyError): ?>
          <div class="alert alert-danger">
            <?= htmlspecialchars($qtyError) ?>
          </div>
        <?php endif; ?>
        <form action="place-order.php" method="POST">

          <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text"
                   name="name"
                   class="form-control"
                   placeholder="Enter your full name"
                   value="<?= htmlspecialchars($prefill_name) ?>"
                   required>
          </div>

          <!-- ✅ ✅ ✅ FIXED MOBILE NUMBER INPUT (10 DIGITS ONLY) -->
          <div class="mb-3">
            <label class="form-label">Phone Number</label>
            <input type="tel"
                   name="phone"
                   class="form-control"
                   placeholder="10-digit mobile number"
                   value="<?= htmlspecialchars($prefill_phone) ?>"
                   maxlength="10"
                   pattern="[0-9]{10}"
                   inputmode="numeric"
                   oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);"
                   required>
          </div>
          <!-- ✅ ✅ ✅ -->

          <div class="mb-3">
            <label class="form-label">Full Address</label>
            <textarea name="address"
                      class="form-control"
                      rows="4"
                      placeholder="House no, Street, Village, Taluka, District"
                      required><?= htmlspecialchars($prefill_address) ?></textarea>
          </div>

          <h5 class="fw-bold mt-4 mb-2">Payment Method</h5>

          <div class="form-check">
            <input class="form-check-input" type="radio" name="payment" value="COD" checked>
            <label class="form-check-label">Cash On Delivery (COD)</label>
          </div>

          <div class="form-check mt-2">
            <input class="form-check-input" type="radio" disabled>
            <label class="form-check-label text-muted">Online Payment (Coming Soon)</label>
          </div>

          <button type="submit"
        class="btn btn-danger w-100 btn-lg mt-4"
        <?= $qtyError ? 'disabled' : '' ?>>
  Place Order
</button>


        </form>
      </div>
    </div>

    <!-- ✅ RIGHT: ORDER SUMMARY -->
    <div class="col-12 col-md-5">
      <div class="card shadow-sm p-4">

        <h4 class="fw-bold mb-3">Order Summary</h4>

        <?php foreach($cart as $item): ?>
          <div class="d-flex justify-content-between mb-2">
            <span>
              <?= htmlspecialchars($item['name']) ?> (<?= $item['quantity'] ?> KG)
            </span>
            <span>
              ₹<?= $item['price'] * $item['quantity'] ?>
            </span>
          </div>
        <?php endforeach; ?>

        <hr>

        <h4 class="fw-bold text-end text-danger">
          Total: ₹<?= $total ?>
        </h4>

      </div>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
