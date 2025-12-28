<?php
require 'config.php';

$conn = getDBConnection();

$sql = "
ALTER TABLE users
ADD COLUMN IF NOT EXISTS phone VARCHAR(20) UNIQUE;
";

$result = pg_query($conn, $sql);

if ($result) {
    echo "Phone column added to users table";
} else {
    echo "Failed to add phone column";
}
