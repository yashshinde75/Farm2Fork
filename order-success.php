<?php
$id = htmlspecialchars($_GET['id'] ?? 'N/A');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Placed - Farm2Fork</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <style>
        body {
            background: #f8f9fa;
        }

        .success-box {
            max-width: 480px;
            margin: auto;
        }

        @media (max-width: 576px) {
            .success-title {
                font-size: 1.6rem;
            }
        }
    </style>
</head>

<body>

<div class="container min-vh-100 d-flex align-items-center justify-content-center">

    <div class="card shadow-sm p-4 p-md-5 text-center success-box">

        <h1 class="text-success fw-bold mb-3 success-title">
            🎉 Order Placed Successfully!
        </h1>

        <p class="lead mb-3">
            Your Order ID is:
        </p>

        <h4 class="fw-bold text-danger mb-4">
            #<?= $id ?>
        </h4>

        <a href="products.php" class="btn btn-danger btn-lg w-100">
            Continue Shopping
        </a>

    </div>
</div>

</body>
</html>
