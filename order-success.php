<?php
$id = $_GET['id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Placed</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body>

<div class="container py-5 text-center">
    <h1 class="text-success fw-bold">🎉 Order Placed Successfully!</h1>
    <p class="lead mt-3">Your Order ID is: <strong>#<?= $id ?></strong></p>

    <a href="products.php" class="btn btn-danger mt-4">Continue Shopping</a>
</div>

</body>
</html>
