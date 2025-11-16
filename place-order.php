<?php
session_start();
require_once 'config.php';
$db = getDBConnection();

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    header("Location: products.php");
    exit;
}

// get user id if logged in
$user_id = $_SESSION['user_id'] ?? null;

$name = $_POST['name'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$payment = $_POST['payment'];

$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

// insert order and return id
$res = pg_query_params($db,
    "INSERT INTO orders (name, phone, address, payment_method, total_amount, user_id, order_date)
     VALUES ($1,$2,$3,$4,$5,$6,NOW())
     RETURNING id",
    [$name, $phone, $address, $payment, $total, $user_id]
);

if (!$res) {
    // handle error
    die("Order insert failed: " . pg_last_error($db));
}

$row = pg_fetch_assoc($res);
$order_id = $row['id'];

// insert order items
foreach ($cart as $item) {
    $subtotal = $item['price'] * $item['quantity'];
    pg_query_params($db,
        "INSERT INTO order_items (order_id, product_name, price, quantity, subtotal)
         VALUES ($1,$2,$3,$4,$5)",
         [$order_id, $item['name'], $item['price'], $item['quantity'], $subtotal]
    );
}

// clear cart and redirect
unset($_SESSION['cart']);
pg_close($db);
header("Location: order-success.php?id=" . $order_id);
exit;
?>