<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>About — Farm2Fork Onions</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css" />

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
        <li class="nav-item"><a class="nav-link active fw-bold text-danger" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="how-it-works.php">How It Works</a></li>
        <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
        <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>

        <li class="nav-item">
          <a class="nav-link btn btn-danger text-white ms-2" href="contact.php">Partner With Us</a>
        </li>

        <li class="nav-item">
            <!-- If logged in → account page; else → login -->
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

<!-- Page header -->
<header class="page-header d-flex align-items-center"
        style="min-height:40vh; background: linear-gradient(90deg, rgba(180,42,20,0.05), rgba(47,79,79,0.03));">
  <div class="container text-center py-5">
    <h1 class="display-5 fw-bold text-danger">Our Story</h1>
    <p class="lead">Building a fair, direct link between Maharashtra farmers and local kitchens.</p>
  </div>
</header>

<!-- About content -->
<section class="py-5">
  <div class="container">
    <div class="row align-items-center g-5">

      <div class="col-md-6">
        <img src="assets/img/farmer.jpeg" alt="Farmer" class="img-fluid rounded-4 shadow-sm" />
      </div>

      <div class="col-md-6">
        <h3 class="fw-bold text-danger">Why we started</h3>
        <p>
          Most small farmers sell through long chains and receive low returns.
          Restaurants spend time and money buying inconsistent produce.
          We bridge the gap — fair prices for farmers and reliable, fresh supply for restaurants.
        </p>

        <h5 class="mt-4">Our principles</h5>
        <ul>
          <li><strong>Fairness:</strong> Quick payments & transparent pricing.</li>
          <li><strong>Freshness:</strong> Direct sourcing → less time in transit.</li>
          <li><strong>Reliability:</strong> Daily / alternate-day delivery schedules you can depend on.</li>
        </ul>

        <a href="contact.php" class="btn btn-danger mt-3">Partner With Us</a>
      </div>

    </div>
  </div>
</section>

<!-- Team section -->
<section class="py-5 bg-light">
  <div class="container text-center">

    <h4 class="fw-bold text-danger mb-4">Meet the hands behind the food</h4>
    <p class="mb-4">Supporting local farmers across Nashik, Pune, Satara — starting small, thinking big.</p>

    <div class="row g-3 justify-content-center">

      <div class="col-6 col-md-3">
        <div class="card p-3 h-100">
          <img src="assets/img/farmer1.jpg" class="card-img-top rounded-3" alt="Farmer 1">
          <div class="card-body text-start">
            <h6 class="card-title mb-0">Vinay</h6>
            <small class="text-muted">Nashik grower</small>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-3">
        <div class="card p-3 h-100">
          <img src="assets/img/farmer2.jpg" class="card-img-top rounded-3" alt="Farmer 2">
          <div class="card-body text-start">
            <h6 class="card-title mb-0">Suman</h6>
            <small class="text-muted">Satara farm</small>
          </div>
        </div>
      </div>

    </div>

  </div>
</section>

<!-- Footer -->
<footer class="py-4 text-center text-muted bg-light">
  <div class="container">
    <p class="mb-1">© 2025 Farm2Fork Onions | Made with ❤️ in Maharashtra</p>
    <small>Follow us on Instagram & WhatsApp for updates.</small>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
