<?php
require 'config.php';

$conn = getDBConnection();

$sql = "
ALTER TABLE orders
ADD COLUMN IF NOT EXISTS name VARCHAR(100),
ADD COLUMN IF NOT EXISTS phone VARCHAR(20),
ADD COLUMN IF NOT EXISTS address TEXT;
";

$result = pg_query($conn, $sql);

if ($result) {
    echo 'Order customer fields added successfully';
} else {
    echo 'Failed to alter orders table';
}
