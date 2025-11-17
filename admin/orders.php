<?php
session_start();
require_once "../config.php";

// Admin authentication check
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
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
    }

    .btn-farm:hover {
        background: #a22310;
    }

    .card {
        border-radius: 12px;
    }

    table {
        background: white;
        border-radius: 10px;
        overflow: hidden;
    }

    .search-box {
        max-width: 300px;
    }
</style>
</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-light bg-white shadow-sm px-4">
    <span class="navbar-brand fw-bold fs-4 text-danger">Farm2Fork Admin</span>

    <div>
        <a href="dashboard.php" class="btn btn-outline-danger">Dashboard</a>
        <a href="admin-login.php?logout=1" class="btn btn-outline-danger">Logout</a>
    </div>
</nav>

<div class="container mt-5">

    <h2 class="title mb-4">📦 All Orders</h2>

    <!-- 🔍 Search Bar -->
    <form method="GET" class="mb-4">
        <input type="text"
               name="search"
               class="form-control search-box d-inline-block"
               placeholder="Search orders..."
               value="<?= htmlspecialchars($search) ?>">
        <button class="btn btn-farm ms-2">Search</button>

        <?php if ($search !== ""): ?>
            <a href="orders.php" class="btn btn-outline-secondary ms-2">Clear</a>
        <?php endif; ?>
    </form>

    <?php if (empty($orders)): ?>
        <div class="alert alert-warning">No orders found.</div>

    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
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
                        <td><?= nl2br(htmlspecialchars($o['address'])) ?></td>
                        <td><?= htmlspecialchars($o['payment_method']) ?></td>

                        <td class="fw-bold text-danger">₹<?= $o['total_amount'] ?></td>
                        <td><?= $o['order_date'] ?></td>

                        <td>
                            <?= htmlspecialchars($o['status']) ?>
                        </td>

                        <td>
                            <a href="order-details.php?id=<?= $o['id'] ?>" class="btn btn-sm btn-farm">
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

</body>
</html>
