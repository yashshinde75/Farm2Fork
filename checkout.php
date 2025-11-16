<?php
session_start();
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Checkout - Farm2Fork</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm fixed-top">
<div class="container">
    <a class="navbar-brand fw-bold text-danger" href="index.html">Farm2Fork</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="about.html">About</a></li>
            <li class="nav-item"><a class="nav-link" href="how-it-works.html">How It Works</a></li>
            <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
            <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
            <li class="nav-item">
                <a class="nav-link btn btn-danger text-white ms-2" href="contact.php">Partner With Us</a>
            </li>
        </ul>
    </div>
</div>
</nav>


<!-- Checkout Content -->
<div class="container py-5" style="margin-top: 90px;">
    <h2 class="text-danger fw-bold mb-4">🧾 Checkout</h2>

    <div class="row">
        <!-- Left Side: Customer Form -->
        <div class="col-md-7">
            <div class="card shadow-sm p-4 mb-4">
                <h4 class="fw-bold mb-3">Customer Details</h4>

                <form action="place-order.php" method="POST">

                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter your full name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control" maxlength="10" placeholder="10-digit mobile number" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Full Address</label>
                        <textarea name="address" class="form-control" rows="4" placeholder="House no, Street, Village, Taluka, District" required></textarea>
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

                    <button type="submit" class="btn btn-danger w-100 btn-lg mt-4">
                        Place Order
                    </button>

                </form>
            </div>
        </div>

        <!-- Right Side: Order Summary -->
        <div class="col-md-5">
            <div class="card shadow-sm p-4">

                <h4 class="fw-bold mb-3">Order Summary</h4>

                <?php foreach($cart as $item): ?>
                    <div class="d-flex justify-content-between mb-2">
                        <span><?= htmlspecialchars($item['name']) ?> (<?= $item['quantity'] ?> KG)</span>
                        <span>₹<?= $item['price'] * $item['quantity'] ?></span>
                    </div>
                <?php endforeach; ?>

                <hr>

                <h4 class="fw-bold text-end">Total: ₹<?= $total ?></h4>

            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
