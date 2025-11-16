<?php
// config.php

define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_NAME', 'farm2fork');
define('DB_USER', 'farm2fork_user');
define('DB_PASS', 'Yash1234');

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
        error_log("Database connection failed: " . pg_last_error());
        die("Database connection failed.");
    }

    return $dbconn;
}
