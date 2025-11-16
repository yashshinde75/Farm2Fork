<?php
session_start();
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

$phone = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';

if ($phone === '' || $password === '') {
    $_SESSION['login_error'] = "Phone and password required.";
    header("Location: login.php");
    exit;
}

$db = getDBConnection();

$res = pg_query_params($db, "SELECT id, full_name, password_hash FROM users WHERE phone = $1", [$phone]);

if (!$res || pg_num_rows($res) === 0) {
    pg_close($db);
    $_SESSION['login_error'] = "User not found.";
    header("Location: login.php");
    exit;
}

$user = pg_fetch_assoc($res);
if (password_verify($password, $user['password_hash'])) {
    // login success
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['full_name'];
    pg_close($db);
    header("Location: my-account.php");
    exit;
} else {
    pg_close($db);
    $_SESSION['login_error'] = "Wrong password.";
    header("Location: login.php");
    exit;
}
