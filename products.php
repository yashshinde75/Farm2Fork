<?php
session_start();
require_once 'config.php';

$db = getDBConnection();

// Fetch all products
$result = pg_query($db, "SELECT * FROM products ORDER BY created_at DESC");
$products = pg_fetch_all($result);

pg_close($db);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Products — Farm2Fork Onions</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<!-- Navbar (EXACT SAME DESIGN) -->
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

            <li class="nav-item"><a class="nav-link active fw-bold text-danger" href="products.php">Products</a></li>

            <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>

            <li class="nav-item">
                <a class="nav-link btn btn-danger text-white ms-2" href="contact.php">Partner With Us</a>
            </li>

            <!-- ACCOUNT ICON (unchanged design) -->
            <li class="nav-item">
                <a class="nav-link p-0" href="user/login.php">
                    <i class="bi bi-person-circle" style="font-size: 2rem; margin-left: 10px;"></i>
                </a>
            </li>

        </ul>
    </div>
</div>
</nav>

<!-- Page Header -->
<header class="page-header d-flex align-items-center"
style="min-height:40vh; background: linear-gradient(90deg, rgba(180,42,20,0.05), rgba(47,79,79,0.03));">
<div class="container text-center py-5">
    <h1 class="display-5 fw-bold text-danger">Our Products</h1>
    <p class="lead">Fresh onions available in different grades</p>
</div>
</header>

<!-- Products Section -->
<section class="py-5">
<div class="container">
    <div class="row g-4">

        <?php if ($products): ?>
        <?php foreach ($products as $p): ?>
        <div class="col-md-4">
            <div class="card rounded-4 shadow-sm h-100 text-center">

                <?php if (!empty($p['image'])): ?>
                    <img src="assets/img/<?php echo htmlspecialchars($p['image']); ?>"
                         class="card-img-top"
                         alt="<?php echo htmlspecialchars($p['name']); ?>">
                <?php endif; ?>

                <div class="card-body">
                    <h5 class="card-title fw-bold"><?php echo htmlspecialchars($p['name']); ?></h5>
                    <p class="card-text">
                        ₹<?php echo $p['price']; ?> —
                        <?php echo htmlspecialchars($p['description']); ?>
                    </p>

                    <!-- Add to Cart Button -->
                    <button class="btn btn-danger px-4 mt-2"
                            onclick="openQtyModal(<?php echo $p['id']; ?>, this)">
                        Add to Cart
                    </button>
                </div>

            </div>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No products available at the moment.</p>
        <?php endif; ?>

    </div>
</div>
</section>

<!-- CTA Section -->
<section class="py-5 text-center text-white bg-danger">
<div class="container">
    <h2 class="fw-bold mb-3">Order Now</h2>
    <p class="mb-4">Partner with us for daily fresh onion delivery to your kitchen.</p>
    <a href="contact.php" class="btn btn-light btn-lg">Contact Us</a>
</div>
</section>

<!-- Footer -->
<footer class="py-4 text-center text-muted bg-light">
<div class="container">
    <p class="mb-1">© 2025 Farm2Fork Onions</p>
</div>
</footer>

<!-- Quantity Modal -->
<div class="modal fade" id="qtyModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content p-3">
      <h5 class="fw-bold mb-3">Select Quantity (KG)</h5>

      <input type="hidden" id="modalProductId">

      <div class="d-flex align-items-center gap-3 mb-3">

        <button class="btn btn-outline-danger" id="qtyMinus">-</button>

        <input type="number" id="qtyInput"
               class="form-control text-center fw-bold"
               style="width: 90px; font-size: 1.3rem;"
               min="1" value="1">

        <button class="btn btn-outline-danger" id="qtyPlus">+</button>

      </div>

      <button class="btn btn-danger w-100" id="addToCartFinal">Add</button>
    </div>
  </div>
</div>

<script>
let currentBtn = null;

document.getElementById("qtyPlus").onclick = () => {
    let q = document.getElementById("qtyInput");
    q.value = parseInt(q.value) + 1;
};

document.getElementById("qtyMinus").onclick = () => {
    let q = document.getElementById("qtyInput");
    if (q.value > 1) q.value = parseInt(q.value) - 1;
};

function openQtyModal(productId, btn) {
    currentBtn = btn;
    document.getElementById("modalProductId").value = productId;
    document.getElementById("qtyInput").value = 1;

    new bootstrap.Modal(document.getElementById("qtyModal")).show();
}

document.getElementById("addToCartFinal").onclick = () => {
    let pid = document.getElementById("modalProductId").value;
    let qty = document.getElementById("qtyInput").value;

    fetch("add-to-cart.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `id=${pid}&qty=${qty}`
    })
    .then(res => res.text())
    .then(() => {
        currentBtn.innerHTML = "✔ Added";
        currentBtn.classList.remove("btn-danger");
        currentBtn.classList.add("btn-success");

        bootstrap.Modal.getInstance(document.getElementById("qtyModal")).hide();
    });
};
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

