<?php
session_start();
require_once "../config.php";

// Block if not logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit;
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin-login.php");
    exit;
}

// DB Queries
$db = getDBConnection();

// ✅ TOTAL ORDERS (ALL, INCLUDING CANCELLED)
$total_orders = pg_fetch_result(
    pg_query($db, "SELECT COUNT(*) FROM orders"),
    0,
    0
);

// ✅ ✅ REVENUE (ONLY NON-CANCELLED + CURRENT YEAR)
$total_revenue = pg_fetch_result(
    pg_query($db, "
        SELECT COALESCE(SUM(total_amount), 0) 
        FROM orders 
        WHERE status != 'Cancelled' 
        AND EXTRACT(YEAR FROM order_date) = EXTRACT(YEAR FROM CURRENT_DATE)
    "),
    0,
    0
);

// ✅ TODAY'S ORDERS (ALL STATUS)
$today_orders = pg_fetch_result(
    pg_query($db, "
        SELECT COUNT(*) 
        FROM orders 
        WHERE DATE(order_date) = CURRENT_DATE
    "),
    0,
    0
);

// ✅ ✅ PENDING ORDERS (EXCLUDES CANCELLED AUTOMATICALLY)
$pending_orders = pg_fetch_result(
    pg_query_params(
        $db,
        "SELECT COUNT(*) FROM orders WHERE status = $1",
        ['Pending']
    ),
    0,
    0
);

pg_close($db);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard — Farm2Fork</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

<style>
    body {
        background: #f9f9f9;
        font-family: "Poppins", sans-serif;
    }

    .stat-card {
        background: #fff;
        border-radius: 14px;
        padding: 22px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: .3s;
        text-align: center;
    }

    .stat-card:hover {
        transform: translateY(-4px);
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #333;
    }

    .stat-label {
        font-size: 15px;
        color: #666;
    }

    .dashboard-card {
        border-radius: 15px;
        padding: 26px;
        background: #fff;
        box-shadow: 0 4px 18px rgba(0,0,0,0.08);
        transition: 0.3s;
        text-align: center;
        height: 100%;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }

    .dashboard-title {
        color: #b42a14;
        font-weight: 700;
    }

    .btn-farm {
        background: #b42a14;
        color: white;
        border-radius: 8px;
        padding: 8px 18px;
    }

    .btn-farm:hover {
        background: #a22310;
        color: #fff;
    }

    /* ✅ Mobile improvements */
    @media (max-width: 576px) {
        .stat-value {
            font-size: 24px;
        }
        .dashboard-card {
            padding: 20px;
        }
        .navbar-brand {
            font-size: 18px;
        }
    }
</style>
</head>

<body>

<!-- ✅ RESPONSIVE NAVBAR -->
<nav class="navbar navbar-light bg-white shadow-sm px-3 px-md-4">
    <span class="navbar-brand fw-bold fs-5 fs-md-4 text-danger">
        Farm2Fork Admin
    </span>
    <a href="dashboard.php?logout=1" class="btn btn-outline-danger btn-sm btn-md">
        Logout
    </a>
</nav>

<div class="container mt-4">

    <h2 class="dashboard-title mb-4 text-center text-md-start">
        Dashboard
    </h2>

    <!-- ✅ STATS ROW (STACKS NICELY ON MOBILE) -->
    <div class="row g-3 g-md-4 mb-4">

        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-label">Total Orders</div>
                <div class="stat-value"><?= $total_orders ?></div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-label">Revenue</div>
                <div class="stat-value">₹<?= $total_revenue ?></div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-label">Today's Orders</div>
                <div class="stat-value"><?= $today_orders ?></div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-label">Pending Orders</div>
                <div class="stat-value"><?= $pending_orders ?></div>
            </div>
        </div>

    </div>

    <!-- ✅ FEATURE CARDS -->
    <div class="row g-3 g-md-4">

        <div class="col-12 col-md-4">
            <a href="products-admin.php" class="text-decoration-none text-dark">
                <div class="dashboard-card">
                    <h4 class="dashboard-title">🧅 Manage Products</h4>
                    <p>View, add, edit or delete products.</p>
                    <button class="btn btn-farm mt-2">Open</button>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-4">
            <a href="admin.php" class="text-decoration-none text-dark">
                <div class="dashboard-card">
                    <h4 class="dashboard-title">📩 Customer Messages</h4>
                    <p>See contact form submissions.</p>
                    <button class="btn btn-farm mt-2">Open</button>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-4">
            <a href="orders.php" class="text-decoration-none text-dark">
                <div class="dashboard-card">
                    <h4 class="dashboard-title">📦 Orders</h4>
                    <p>View & manage all orders.</p>
                    <button class="btn btn-farm mt-2">Open</button>
                </div>
            </a>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
