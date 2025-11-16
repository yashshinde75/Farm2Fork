<?php
session_start();
require_once "../config.php";


$error = "";

// Handle login
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);

    $db = getDBConnection();
    $sql = "SELECT * FROM users WHERE phone = $1 LIMIT 1";
    $res = pg_query_params($db, $sql, [$phone]);
    $user = pg_fetch_assoc($res);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        header("Location: ../index.php");

        exit;
    } else {
        $error = "Invalid phone or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login — Farm2Fork</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<style>
    body {
        background: #fafafa;
        font-family: "Poppins", sans-serif;
    }

    .auth-card {
        width: 420px;
        margin: 120px auto;
        padding: 35px;
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 4px 25px rgba(0,0,0,0.10);
    }

    .brand-title {
        font-size: 32px;
        text-align: center;
        font-weight: 700;
        letter-spacing: 2px;
        color: #b42a14;
        margin-bottom: 25px;
    }

    .btn-farm {
        background: #b42a14;
        color: white;
        font-weight: 600;
    }

    .btn-farm:hover {
        background: #a22310;
    }

    .small-text a {
        color: #b42a14;
        text-decoration: none;
        font-weight: 600;
    }

    .small-text a:hover {
        text-decoration: underline;
    }
</style>
</head>

<body>

<div class="auth-card">
    <div class="brand-title">FARM2FORK</div>

    <?php if ($error): ?>
        <div class="alert alert-danger text-center py-2"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">

        <label class="form-label">Phone Number</label>
        <input type="text" name="phone" class="form-control mb-3" required maxlength="10">

        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control mb-3" required>

        <button type="submit" class="btn btn-farm w-100 py-2">Login</button>
    </form>

    <p class="text-center mt-3 small-text">
        Don't have an account?
        <a href="signup.php">Signup</a>
    </p>
</div>

</body>
</html>
