<?php
session_start();
require_once "config.php";

if (!isset($_POST['id']) || !isset($_POST['qty'])) {
    exit("Invalid request");
}

$id = intval($_POST['id']);
$qty = intval($_POST['qty']);

$db = getDBConnection();

$q = "SELECT id, name, price, image FROM products WHERE id = $1";
$r = pg_query_params($db, $q, [$id]);
$product = pg_fetch_assoc($r);

pg_close($db);

if (!$product) exit("No product");

// create cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['quantity'] += $qty;
} else {
    $_SESSION['cart'][$id] = [
        "id" => $product['id'],
        "name" => $product['name'],
        "price" => $product['price'],
        "image" => $product['image'],
        "quantity" => $qty
    ];
}

echo "added";
?>
