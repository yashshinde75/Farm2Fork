<?php
session_start();
require_once "../config.php";

// ✅ Admin authentication check (FIXED redirect)
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit;
}

$db = getDBConnection();

// Search filter
$search = trim($_GET['search'] ?? "");

// Base query
$sql = "SELECT * FROM orders";

// If searching, modify query
if ($search !== "") {
    $sql .= " WHERE 
                CAST(id AS TEXT) ILIKE $1 OR 
                name ILIKE $1 OR 
                phone ILIKE $1 OR 
                status ILIKE $1";
    $result = pg_query_params($db, $sql, ['%' . $search . '%']);
} else {
    // Normal view (no search)
    $sql .= " ORDER BY order_date DESC";
    $result = pg_query($db, $sql);
}

// Fetch orders
$orders = $result ? pg_fetch_all($result) : [];

pg_close($db);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Orders — Farm2Fork Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

<style>
    body {
        background: #f9f9f9;
        font-family: "Poppins", sans-serif;
    }

    .title {
        color: #b42a14;
        font-weight: 700;
    }

    .btn-farm {
        background: #b42a14;
        color: white;
        border-radius: 6px;
        padding: 6px 14px;
    }

    .btn-farm:hover {
        background: #a22310;
        color: #fff;
    }

    table {
        background: white;
        border-radius: 10px;
        overflow: hidden;
    }

    .search-box {
        width: 100%;
        max-width: 320px;
    }

    /* ✅ Mobile improvements */
    @media (max-width: 576px) {
        .title {
            font-size: 1.4rem;
        }
    }
</style>
</head>

<body>

<!-- ✅ RESPONSIVE NAVBAR -->
<nav class="navbar navbar-light bg-white shadow-sm px-3 px-md-4 d-flex justify-content-between align-items-center">
    <span class="navbar-brand fw-bold fs-5 fs-md-4 text-danger">
        Farm2Fork Admin
    </span>

    <div class="d-flex gap-2">
        <a href="dashboard.php" class="btn btn-outline-danger btn-sm">
            Dashboard
        </a>
        <a href="admin-login.php?logout=1" class="btn btn-outline-danger btn-sm">
            Logout
        </a>
    </div>
</nav>

<div class="container mt-4">

    <h2 class="title mb-4 text-center text-md-start">
        📦 All Orders
    </h2>

    <!-- ✅ RESPONSIVE SEARCH BAR -->
    <form method="GET" class="mb-4 d-flex flex-column flex-md-row gap-2 align-items-stretch align-items-md-center">

        <input type="text"
               name="search"
               class="form-control search-box"
               placeholder="Search orders..."
               value="<?= htmlspecialchars($search) ?>">

        <div class="d-flex gap-2">
            <button class="btn btn-farm">Search</button>

            <?php if ($search !== ""): ?>
                <a href="orders.php" class="btn btn-outline-secondary">Clear</a>
            <?php endif; ?>
        </div>

    </form>

    <?php if (empty($orders)): ?>

        <div class="alert alert-warning text-center">
            No orders found.
        </div>

    <?php else: ?>

        <!-- ✅ RESPONSIVE ORDERS TABLE -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-nowrap small">
                <thead class="table-danger">
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Payment</th>
                        <th>Total (₹)</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Items</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($orders as $o): ?>
                    <tr>
                        <td><?= $o['id'] ?></td>
                        <td><?= htmlspecialchars($o['name']) ?></td>
                        <td><?= htmlspecialchars($o['phone']) ?></td>

                        <td style="max-width:260px; white-space:normal;">
                            <?= nl2br(htmlspecialchars($o['address'])) ?>
                        </td>

                        <td><?= htmlspecialchars($o['payment_method']) ?></td>

                        <td class="fw-bold text-danger">
                            ₹<?= $o['total_amount'] ?>
                        </td>

                        <td class="text-nowrap">
                            <?= $o['order_date'] ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($o['status']) ?>
                        </td>

                        <td>
                            <a href="order-details.php?id=<?= $o['id'] ?>" 
                               class="btn btn-sm btn-farm">
                                View Items
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>

    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
