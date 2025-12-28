<?php
require 'config.php';

$conn = getDBConnection();

$sql = "
ALTER TABLE orders
ADD COLUMN IF NOT EXISTS total_amount NUMERIC(10,2);
";

$result = pg_query($conn, $sql);

if ($result) {
    echo "total_amount column added successfully";
} else {
    echo "Failed to add column";
}
