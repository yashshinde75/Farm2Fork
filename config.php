<?php
// config.php — SAFE & CLOUD READY

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_PORT', getenv('DB_PORT') ?: '5432');
define('DB_NAME', getenv('DB_NAME') ?: 'farm2fork');
define('DB_USER', getenv('DB_USER') ?: 'farm2fork_user');
define('DB_PASS', getenv('DB_PASS') ?: '');

function getDBConnection() {

    $connStr = sprintf(
        "host=%s port=%s dbname=%s user=%s password=%s",
        DB_HOST,
        DB_PORT,
        DB_NAME,
        DB_USER,
        DB_PASS
    );

    $dbconn = @pg_connect($connStr);

    if (!$dbconn) {
        error_log("PostgreSQL connection failed. Check credentials & network.");
        die("Database connection error.");
    }

    return $dbconn;
}
