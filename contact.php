<?php
session_start();

require_once 'config.php';

// Initialize variables
$success = false;
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize input
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $type = trim($_POST['type'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || $phone === '' || $message === '') {
        $error = "Please fill in all required fields (name, phone, message).";
    } else {
        $db = getDBConnection();

        $sql = "INSERT INTO contacts (name, phone, email, type, message, created_at)
                VALUES ($1, $2, $3, $4, $5, NOW())";

        $result = @pg_query_params($db, $sql, [$name, $phone, $email, $type, $message]);

        if ($result) {
            $success = true;
        } else {
            error_log("Contact insert failed: " . pg_last_error($db));
            $error = "Failed to save your message. Please try again later.";
        }

        pg_close($db);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact — Farm2Fork Onions</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
<style>
    .account-icon { font-size: 2rem; margin-left: 10px; }
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
            <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>

            <li class="nav-item"><a class="nav-link btn btn-danger text-white ms-2" href="contact.php">Partner With Us</a></li>

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


<main class="container" style="padding-top:90px; padding-bottom:60px;">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <?php if ($success): ?>
                <div class="alert alert-success">✅ Thanks — we received your message. We'll contact you soon.</div>

            <?php else: ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <div class="card p-4 shadow-sm">
                    <h3 class="fw-bold text-danger mb-3">Partner With Us</h3>

                    <form method="post" action="contact.php">

                        <div class="mb-3">
                            <label class="form-label">Name *</label>
                            <input name="name" type="text" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone *</label>
                            <input name="phone" type="text" class="form-control" required maxlength="10">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input name="email" type="email" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">You are</label>
                            <select name="type" class="form-select">
                                <option value="restaurant">Restaurant</option>
                                <option value="Hotel">Hotel</option>
                                <option value="farmer">Farmer</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Message *</label>
                            <textarea name="message" rows="4" class="form-control" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-danger">Send Message</button>

                    </form>
                </div>

            <?php endif; ?>

        </div>
    </div>
</main>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
