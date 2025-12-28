<?php
require 'config.php';

$conn = getDBConnection();

$sql = "
ALTER TABLE order_items
ADD COLUMN IF NOT EXISTS product_name VARCHAR(150);
";

$result = pg_query($conn, $sql);

if ($result) {
    echo "product_name column added to order_items";
} else {
    echo "Failed to add product_name";
}
