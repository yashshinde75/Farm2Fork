<?php
session_start();

// If logout request is coming here
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin-login.php");
    exit;
}

$error = "";

// ✅ IMPORTANT: Later we will move this to ENV for security during deployment
$ADMIN_PASS = "Yash1234";

// If admin submits login form
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if ($_POST['password'] === $ADMIN_PASS) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Incorrect password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login — Farm2Fork</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* ✅ Small mobile optimization */
        @media (max-width: 400px) {
            .admin-card {
                padding: 1.2rem !important;
            }
        }
    </style>
</head>

<body class="d-flex justify-content-center align-items-center min-vh-100 bg-light">

<div class="card p-4 shadow-sm admin-card w-100 mx-3" style="max-width: 380px;">
    <h4 class="text-center text-danger fw-bold mb-3">Admin Login</h4>

    <?php if (!empty($error)) : ?>
        <div class="alert alert-danger text-center">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input 
                type="password" 
                name="password" 
                class="form-control"
                required
                autofocus
            >
        </div>

        <button type="submit" class="btn btn-danger w-100">
            Login
        </button>
    </form>
</div>

</body>
</html>
