<?php
require 'config.php';

$conn = getDBConnection();

$sql = "
ALTER TABLE order_items
ADD COLUMN IF NOT EXISTS subtotal NUMERIC(10,2);
";

$result = pg_query($conn, $sql);

if ($result) {
    echo "subtotal column added to order_items";
} else {
    echo "Failed to add subtotal column";
}
