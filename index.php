<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Farm2Fork Onions | Fresh from Maharashtra Farms</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">

  <style>
      .account-icon {
          font-size: 1.9rem;
          margin-left: 12px;
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

          <li class="nav-item"><a class="nav-link active fw-bold text-danger" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
          <li class="nav-item"><a class="nav-link" href="how-it-works.php">How It Works</a></li>
          <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
          <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>

          <li class="nav-item">
              <a class="nav-link btn btn-danger text-white ms-2" href="contact.php">
                Partner With Us
              </a>
          </li>

          <!-- ACCOUNT ICON -->
          <li class="nav-item">
              <?php if(isset($_SESSION['user_logged_in'])): ?>
                  <!-- Logged-in user goes to My Account -->
                  <a class="nav-link p-0" href="user/account.php">
                      <i class="bi bi-person-circle account-icon"></i>
                  </a>
              <?php else: ?>
                  <!-- Not logged in → Login page -->
                  <a class="nav-link p-0" href="user/login.php">
                      <i class="bi bi-person-circle account-icon"></i>
                  </a>
              <?php endif; ?>
          </li>

        </ul>
      </div>
    </div>
  </nav>


  <!-- Hero Section -->
  <section class="hero d-flex align-items-center text-center text-white">
    <div class="container">
      <h1 class="fw-bold display-4 mb-3">Fresh Onions, Direct from Farms</h1>
      <p class="lead mb-4">Connecting Maharashtra farmers with restaurants and kitchens — fair prices, fresh produce, every day.</p>
      <a href="contact.php" class="btn btn-danger btn-lg shadow">Partner With Us</a>
    </div>
  </section>

  <!-- Why Choose Us -->
  <section class="py-5 bg-light text-center">
    <div class="container">
      <h2 class="fw-bold mb-4 text-danger">Why Choose Farm2Fork?</h2>
      <div class="row g-4">

        <div class="col-md-4">
          <div class="p-4 border rounded-4 bg-white h-100 shadow-sm">
            <h4 class="text-danger">🌾 Fresh & Local</h4>
            <p>We buy directly from Maharashtra farmers to ensure freshness and quality every single day.</p>
          </div>
        </div>

        <div class="col-md-4">
          <div class="p-4 border rounded-4 bg-white h-100 shadow-sm">
            <h4 class="text-danger">💰 Fair Pricing</h4>
            <p>Farmers get better value, restaurants get better rates — everyone wins.</p>
          </div>
        </div>

        <div class="col-md-4">
          <div class="p-4 border rounded-4 bg-white h-100 shadow-sm">
            <h4 class="text-danger">🚛 Reliable Delivery</h4>
            <p>Timely delivery for restaurants and hotels — no delays, no excuses.</p>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="py-5 text-center text-white bg-danger">
    <div class="container">
      <h2 class="fw-bold mb-3">Join Our Network</h2>
      <p class="mb-4">Partner with us today and get fresh onions delivered directly from farms to your kitchen.</p>
      <a href="contact.php" class="btn btn-light btn-lg">Get in Touch</a>
    </div>
  </section>

  <!-- Footer -->
  <footer class="py-4 text-center text-muted bg-light">
    <div class="container">
      <p class="mb-1">© 2025 Farm2Fork Onions | Made with ❤️ in Maharashtra</p>
      <small>Follow us on Instagram & WhatsApp for updates.</small>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
