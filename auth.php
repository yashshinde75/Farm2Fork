<?php
require_once __DIR__ . "/session.php";

function require_login()
{
    if (
        !isset($_SESSION['logged_in']) ||
        $_SESSION['logged_in'] !== true ||
        !isset($_SESSION['user_id'])
    ) {
        header("Location: /user/login.php");
        exit;
    }
}
