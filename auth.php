<?php
// auth.php
session_start();

/**
 * Call this function on every page that requires login
 */
function require_login()
{
    // Single source of truth for login
    if (
        !isset($_SESSION['logged_in']) ||
        $_SESSION['logged_in'] !== true ||
        !isset($_SESSION['user_id'])
    ) {
        // Not logged in → redirect to login
        header("Location: /user/login.php");
        exit;
    }
}
