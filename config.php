<?php
// config.php

define('DB_HOST', getenv('DB_HOST'));
define('DB_PORT', getenv('DB_PORT'));
define('DB_NAME', getenv('DB_NAME'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASS', getenv('DB_PASS'));

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
?>
