<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("Invalid request");
}

$order_id = intval($_GET['id']);
$user_id = intval($_SESSION['user_id']);

$db = getDBConnection();

// Update status only if it belongs to user AND is pending
$sql = "UPDATE orders 
        SET status = 'Cancelled'
        WHERE id = $1 AND user_id = $2 AND status = 'Pending'";

$res = pg_query_params($db, $sql, [$order_id, $user_id]);

pg_close($db);

// After cancel redirect back
header("Location: my-orders.php");
exit;
?>
