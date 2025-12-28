<?php
require 'config.php';

$conn = getDBConnection();

$sql = "
ALTER TABLE orders
ADD COLUMN IF NOT EXISTS order_date DATE;
UPDATE orders
SET order_date = created_at::DATE
WHERE order_date IS NULL;
";

$result = pg_query($conn, $sql);

if ($result) {
    echo "order_date column added and synced";
} else {
    echo "Failed to add order_date";
}
