<?php
session_start();
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: signup.php");
    exit;
}

$full_name = trim($_POST['full_name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';

if ($full_name === '' || $phone === '' || $password === '') {
    $_SESSION['signup_error'] = "All fields required.";
    header("Location: signup.php");
    exit;
}

$db = getDBConnection();

// check phone unique
$res = pg_query_params($db, "SELECT id FROM users WHERE phone=$1", [$phone]);
if ($res && pg_num_rows($res) > 0) {
    pg_close($db);
    $_SESSION['signup_error'] = "Phone already registered. Try login.";
    header("Location: signup.php");
    exit;
}

// hash password
$hash = password_hash($password, PASSWORD_DEFAULT);

$insert = pg_query_params($db,
    "INSERT INTO users (full_name, phone, password_hash, created_at) VALUES ($1, $2, $3, NOW()) RETURNING id",
    [$full_name, $phone, $hash]
);

if ($insert) {
    $row = pg_fetch_assoc($insert);
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['user_name'] = $full_name;
    // redirect to account
    pg_close($db);
    header("Location: my-account.php");
    exit;
} else {
    pg_close($db);
    $_SESSION['signup_error'] = "Signup failed. Try again.";
    header("Location: signup.php");
    exit;
}
