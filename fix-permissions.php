<?php
require 'config.php';

$conn = getDBConnection();

$sql = "
GRANT ALL PRIVILEGES ON SCHEMA public TO postgres;
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO postgres;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO postgres;
";

$result = pg_query($conn, $sql);

if ($result) {
    echo "PERMISSIONS FIXED SUCCESSFULLY";
} else {
    echo "FAILED TO FIX PERMISSIONS";
}
