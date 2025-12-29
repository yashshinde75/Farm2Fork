<?php
require_once __DIR__ . "/session.php";
require_once "../config.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

    $db = getDBConnection();

    $check = pg_query_params($db, "SELECT id FROM users WHERE phone = $1", [$phone]);
    if (pg_num_rows($check) > 0) {
        $error = "Phone number already registered!";
    } else {
        $sql = "INSERT INTO users (name, phone, password) VALUES ($1,$2,$3)";
        $res = pg_query_params($db, $sql, [$name,$phone,$password]);

        if ($res) {
            $success = "Account created successfully! You can now login.";
        } else {
            $error = "Something went wrong.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Signup — Farm2Fork</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<style>
body {
    background: #fafafa;
    font-family: "Poppins", sans-serif;
}

.auth-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.auth-card {
    width: 100%;
    max-width: 420px;
    padding: 28px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 25px rgba(0,0,0,0.10);
}

.brand-title {
    font-size: 28px;
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
    border-radius: 10px;
}

.btn-farm:hover {
    background: #a22310;
}

.small-text {
    font-size: 14px;
}

.small-text a {
    color: #b42a14;
    text-decoration: none;
    font-weight: 600;
}

.small-text a:hover {
    text-decoration: underline;
}

/* ✅ Mobile tuning */
@media (max-width: 480px) {
    .brand-title {
        font-size: 24px;
    }
    .auth-card {
        padding: 22px;
    }
}
</style>
</head>

<body>

<div class="auth-wrapper">
    <div class="auth-card">

        <div class="brand-title">FARM2FORK</div>

        <?php if ($error): ?>
            <div class="alert alert-danger text-center py-2">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success text-center py-2">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control" required maxlength="10">
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-farm w-100 py-2">
                Create Account
            </button>
        </form>

        <p class="text-center mt-3 small-text">
            Already have an account?
            <a href="login.php">Login</a>
        </p>

    </div>
</div>

</body>
</html>
