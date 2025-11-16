<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>How It Works — Farm2Fork Onions</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">

<style>
    .account-icon {
        font-size: 1.9rem;
        margin-left: 10px;
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
            <li class="nav-item"><a class="nav-link active" href="how-it-works.php">How It Works</a></li>
            <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
            <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>

            <li class="nav-item">
                <a class="nav-link btn btn-danger text-white ms-2" href="contact.php">Partner With Us</a>
            </li>

            <!-- ACCOUNT ICON -->
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

<!-- Page Header -->
<header class="page-header d-flex align-items-center"
style="min-height:40vh; background: linear-gradient(90deg, rgba(180,42,20,0.05), rgba(47,79,79,0.03));">
<div class="container text-center py-5">
    <h1 class="display-5 fw-bold text-danger">How Farm2Fork Works</h1>
    <p class="lead">From farm to your kitchen in 3 simple steps</p>
</div>
</header>

<!-- Steps Section -->
<section class="py-5">
<div class="container">
    <div class="row g-4 text-center">
        
        <div class="col-md-4">
            <div class="card p-4 rounded-4 shadow-sm h-100">
                <div class="display-3 text-danger mb-3">🌾</div>
                <h4 class="fw-bold">Step 1: Sourcing</h4>
                <p>We buy fresh onions directly from Maharashtra farmers to ensure high quality and fair prices.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-4 rounded-4 shadow-sm h-100">
                <div class="display-3 text-danger mb-3">🧺</div>
                <h4 class="fw-bold">Step 2: Sorting & Packing</h4>
                <p>Onions are carefully sorted and packed for safe and efficient delivery to restaurants.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-4 rounded-4 shadow-sm h-100">
                <div class="display-3 text-danger mb-3">🚛</div>
                <h4 class="fw-bold">Step 3: Delivery</h4>
                <p>Timely delivery to your kitchen or restaurant — fresh onions every day.</p>
            </div>
        </div>

    </div>
</div>
</section>

<!-- CTA Section -->
<section class="py-5 text-center text-white bg-danger">
<div class="container">
    <h2 class="fw-bold mb-3">Ready to Partner?</h2>
    <p class="mb-4">Contact us today and get fresh onions delivered straight from the farm.</p>
    <a href="contact.php" class="btn btn-light btn-lg">Get in Touch</a>
</div>
</section>

<!-- Footer -->
<footer class="py-4 text-center text-muted bg-light">
<div class="container">
    <p class="mb-1">© 2025 Farm2Fork Onions</p>
</div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
