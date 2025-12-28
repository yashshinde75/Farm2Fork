<?php
require 'config.php';

$conn = getDBConnection();

$sql = "
CREATE TABLE IF NOT EXISTS contacts (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100),
    phone VARCHAR(20),
    email VARCHAR(150),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
";

$result = pg_query($conn, $sql);

if ($result) {
    echo "Contacts table ready";
} else {
    echo "Failed to create contacts table";
}
