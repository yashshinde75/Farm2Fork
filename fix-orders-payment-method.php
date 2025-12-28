<?php
require 'config.php';

$conn = getDBConnection();

$sql = "
ALTER TABLE orders
ADD COLUMN IF NOT EXISTS payment_method VARCHAR(50);
";

$result = pg_query($conn, $sql);

if ($result) {
    echo "payment_method column added successfully";
} else {
    echo "Failed to add payment_method column";
}
