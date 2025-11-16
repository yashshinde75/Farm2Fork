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

$total_orders = pg_fetch_result(pg_query($db, "SELECT COUNT(*) FROM orders"), 0, 0);
$total_revenue = pg_fetch_result(pg_query($db, "SELECT COALESCE(SUM(total_amount),0) FROM orders"), 0, 0);
$today_orders = pg_fetch_result(pg_query($db, "SELECT COUNT(*) FROM orders WHERE DATE(order_date)=CURRENT_DATE"), 0, 0);
$pending_orders = pg_fetch_result(pg_query_params($db, "SELECT COUNT(*) FROM orders WHERE status=$1", ['Pending']), 0, 0);

pg_close($db);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard — Farm2Fork</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

<!-- FIX RUPEE SIGN -->
<meta charset="UTF-8">

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
        padding: 30px;
        background: #fff;
        box-shadow: 0 4px 18px rgba(0,0,0,0.08);
        transition: 0.3s;
        text-align: center;
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
    }

    .btn-farm:hover {
        background: #a22310;
    }
</style>
</head>

<body>

<nav class="navbar navbar-light bg-white shadow-sm px-4">
    <span class="navbar-brand fw-bold fs-4 text-danger">Farm2Fork Admin</span>
    <a href="dashboard.php?logout=1" class="btn btn-outline-danger">Logout</a>
</nav>

<div class="container mt-4">

    <h2 class="dashboard-title mb-4">Dashboard</h2>

    <!-- STATS ROW -->
    <div class="row g-4 mb-4">

        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-label">Total Orders</div>
                <div class="stat-value"><?= $total_orders ?></div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-label">Revenue</div>
                <div class="stat-value">&#8377; <?= $total_revenue ?></div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-label">Today's Orders</div>
                <div class="stat-value"><?= $today_orders ?></div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-label">Pending Orders</div>
                <div class="stat-value"><?= $pending_orders ?></div>
            </div>
        </div>

    </div>

    <!-- FEATURE CARDS -->
    <div class="row g-4">

        <div class="col-md-4">
            <a href="products-admin.php" style="text-decoration:none; color:inherit;">
                <div class="dashboard-card">
                    <h4 class="dashboard-title">🧅 Manage Products</h4>
                    <p>View, add, edit or delete products.</p>
                    <button class="btn btn-farm mt-2">Open</button>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="admin.php" style="text-decoration:none; color:inherit;">
                <div class="dashboard-card">
                    <h4 class="dashboard-title">📩 Customer Messages</h4>
                    <p>See contact form submissions.</p>
                    <button class="btn btn-farm mt-2">Open</button>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="orders.php" style="text-decoration:none; color:inherit;">
                <div class="dashboard-card">
                    <h4 class="dashboard-title">📦 Orders</h4>
                    <p>View & manage all orders.</p>
                    <button class="btn btn-farm mt-2">Open</button>
                </div>
            </a>
        </div>

    </div>

</div>

</body>
</html>
