<?php
require 'config.php';

$conn = getDBConnection();
$sql = file_get_contents(__DIR__ . '/final-schema.sql');

$result = pg_query($conn, $sql);

if ($result) {
    echo "FINAL DATABASE SCHEMA APPLIED SUCCESSFULLY";
} else {
    echo "FAILED TO APPLY SCHEMA";
}
