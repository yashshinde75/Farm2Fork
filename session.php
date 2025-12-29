<?php
// session.php
// Global session configuration (MUST be included before session_start)

ini_set('session.gc_maxlifetime', 86400); // 1 day
session_set_cookie_params([
    'lifetime' => 86400,
    'path' => '/',
    'httponly' => true,
    'secure' => false, // set true if HTTPS
    'samesite' => 'Lax'
]);

session_start();
