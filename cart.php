<?php
session_start();
$cart = $_SESSION['cart'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Your Cart - Farm2Fork</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
<style>
  .account-icon { font-size: 1.9rem; margin-left: 10px; }
  /* small tweak so the table doesn't hide under fixed navbar on small screens */
  @media (max-width: 576px) {
    .container { margin-top: 120px; }
  }
</style>
</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm fixed-top">
<div class="container">
    <a class="navbar-brand fw-bold text-danger" href="index.php">Farm2Fork</a>
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
                <a class="nav-link btn btn-danger text-white ms-2" href="contact.php">Partner With Us</a>
            </li>

            <li class="nav-item">
                <?php if(isset($_SESSION['user_logged_in'])): ?>
                    <a class="nav-link p-0" href="user/account.php">
                        <i class="bi bi-person-circle account-icon"></i>
                    </a>
                <?php else: ?>
                    <a class="nav-link p-0" href="user/login.php">
                        <i class="bi bi-person-circle account-icon"></i>
                    </a>
                <?php endif; ?>
            </li>
        </ul>
    </div>
</div>
</nav>

<div class="container" style="margin-top: 100px;">

    <h2 class="text-danger fw-bold mb-4">🛒 Your Cart</h2>

    <?php if(empty($cart)): ?>
        <div class="alert alert-warning">Your cart is empty!</div>
        <a href="products.php" class="btn btn-danger">Continue Shopping</a>

    <?php else: ?>

    <div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead class="table-danger">
            <tr>
                <th>Product</th>
                <th>Name</th>
                <th>Price (₹)</th>
                <th>Quantity (KG)</th>
                <th>Subtotal (₹)</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
        <?php 
        $total = 0;
        foreach($cart as $item): 
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
        ?>
            <tr>
                <td style="width:110px;">
                    <img src="<?= htmlspecialchars($item['image'] ? 'uploads/' . $item['image'] : 'assets/img/placeholder.png') ?>" width="80" alt="<?= htmlspecialchars($item['name']) ?>">
                </td>
                <td class="fw-bold"><?= htmlspecialchars($item['name']) ?></td>
                <td>₹<?= number_format($item['price'], 2) ?></td>

                <!-- Quantity Controls -->
                <td>
                    <a href="update-cart.php?id=<?= urlencode($item['id']) ?>&action=minus" class="btn btn-sm btn-outline-danger">-</a>

                    <span class="mx-2 fw-bold"><?= htmlspecialchars($item['quantity']) ?></span>

                    <a href="update-cart.php?id=<?= urlencode($item['id']) ?>&action=plus" class="btn btn-sm btn-outline-danger">+</a>
                </td>

                <td class="fw-bold">₹<?= number_format($subtotal, 2) ?></td>

                <td>
                    <a href="remove-cart.php?id=<?= urlencode($item['id']) ?>" 
                       class="btn btn-sm btn-danger">
                       Remove
                    </a>
                </td>
            </tr>

        <?php endforeach; ?>
        </tbody>
    </table>
    </div>

    <h3 class="text-end fw-bold">Total: ₹<?= number_format($total, 2) ?></h3>

    <div class="d-flex justify-content-end mt-3">
        <a href="checkout.php" class="btn btn-danger btn-lg">Proceed to Checkout</a>
    </div>

    <?php endif; ?>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
