<?php
// config.php
// Central database configuration file (PostgreSQL)

// 1️⃣ Read values from environment variables (cloud-safe)
// 2️⃣ Fallback to local values if env variables are not set

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_PORT', getenv('DB_PORT') ?: '5432');
define('DB_NAME', getenv('DB_NAME') ?: 'farm2fork');
define('DB_USER', getenv('DB_USER') ?: 'farm2fork_user');
define('DB_PASS', getenv('DB_PASS') ?: 'Yash1234');

/**
 * Returns PostgreSQL database connection
 */
function getDBConnection() {

    $connStr = sprintf(
        "host=%s port=%s dbname=%s user=%s password=%s",
        DB_HOST,
        DB_PORT,
        DB_NAME,
        DB_USER,
        DB_PASS
    );

    $dbconn = pg_connect($connStr);

    if (!$dbconn) {
        // Log error internally, do NOT show to user
        error_log("Database connection failed: " . pg_last_error());
        die("Service temporarily unavailable. Please try again later.");
    }

    return $dbconn;
}
