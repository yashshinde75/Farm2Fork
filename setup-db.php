<?php
require 'config.php';

$conn = getDBConnection();

$sql = "
CREATE TABLE IF NOT EXISTS products (
    id SERIAL PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    price NUMERIC(10,2) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
";

$result = pg_query($conn, $sql);

if ($result) {
    echo 'Products table created successfully';
} else {
    echo 'Failed to create table';
}
