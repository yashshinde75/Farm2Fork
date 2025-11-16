<?php
session_start();

// If logout request is coming here
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin-login.php"); // FIXED
    exit;
}

$error = "";
$ADMIN_PASS = "Yash1234";  // your admin password

// If admin submits login form
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if ($_POST['password'] === $ADMIN_PASS) {
        $_SESSION['admin_logged_in'] = true;

        header("Location: dashboard.php");   // FIXED
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex justify-content-center align-items-center" style="height:100vh; background:#f8f9fa;">

<div class="card p-4 shadow-sm" style="width:350px;">
    <h4 class="text-center text-danger fw-bold mb-3">Admin Login</h4>

    <?php if (!empty($error)) : ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control mb-3" required>

        <button type="submit" class="btn btn-danger w-100">Login</button>
    </form>
</div>

</body>
</html>
