<?php
session_start();
require_once "../config.php";

// If admin not logged in → redirect to login page
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

// Handle Add / Update / Delete
$db = getDBConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['add'])) {
        $sql = "INSERT INTO products (name, price, description, image, created_at) 
                VALUES ($1,$2,$3,$4,NOW())";
        pg_query_params($db, $sql, [
            trim($_POST['name']),
            trim($_POST['price']),
            trim($_POST['description']),
            trim($_POST['image'])
        ]);
    }

    if (isset($_POST['update'])) {
        $sql = "UPDATE products SET name=$1, price=$2, description=$3, image=$4 WHERE id=$5";
        pg_query_params($db, $sql, [
            trim($_POST['name']),
            trim($_POST['price']),
            trim($_POST['description']),
            trim($_POST['image']),
            $_POST['id']
        ]);
    }

    if (isset($_POST['delete'])) {
        pg_query_params($db, "DELETE FROM products WHERE id=$1", [$_POST['id']]);
    }

    header("Location: products-admin.php");
    exit;
}

// Fetch products
$result = pg_query($db, "SELECT * FROM products ORDER BY created_at DESC");
$products = pg_fetch_all($result);
pg_close($db);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Products Admin — Farm2Fork</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
<div class="container">
    <a class="navbar-brand text-danger fw-bold" href="dashboard.php">Farm2Fork Admin</a>
    <div class="ms-auto">
        <a href="dashboard.php" class="btn btn-outline-danger">Dashboard</a>
        <a href="admin-login.php?logout=1" class="btn btn-outline-danger">Logout</a>
    </div>
</div>
</nav>

<main class="container py-5">
    <h3 class="text-danger fw-bold mb-4">Manage Products</h3>

    <!-- Add Product -->
    <div class="card p-3 mb-4 shadow-sm">
        <h5>Add New Product</h5>
        <form method="post">
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="name" class="form-control" placeholder="Name" required>
                </div>

                <div class="col-md-2">
                    <input type="number" step="0.01" name="price" class="form-control" placeholder="Price" required>
                </div>

                <div class="col-md-4">
                    <input type="text" name="description" class="form-control" placeholder="Description">
                </div>

                <div class="col-md-3">
                    <input type="text" name="image" class="form-control" placeholder="Image filename">
                </div>
            </div>

            <button type="submit" name="add" class="btn btn-danger mt-2">Add Product</button>
        </form>
    </div>

    <!-- Products Table -->
    <?php if ($products): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-danger">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price (₹)</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

            <?php foreach ($products as $p): ?>
            <tr>
                <form method="post">
                    <td>
                        <?= $p['id'] ?>
                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                    </td>

                    <td><input type="text" name="name" value="<?= htmlspecialchars($p['name']) ?>" class="form-control"></td>

                    <td><input type="number" step="0.01" name="price" value="<?= $p['price'] ?>" class="form-control"></td>

                    <td><input type="text" name="description" value="<?= htmlspecialchars($p['description']) ?>" class="form-control"></td>

                    <td><input type="text" name="image" value="<?= htmlspecialchars($p['image']) ?>" class="form-control"></td>

                    <td class="d-flex gap-1">
                        <button type="submit" name="update" class="btn btn-sm btn-success">Update</button>
                        <button type="submit" name="delete" onclick="return confirm('Delete this product?')" class="btn btn-sm btn-danger">Delete</button>
                    </td>
                </form>
            </tr>
            <?php endforeach; ?>

            </tbody>
        </table>
    </div>
    <?php else: ?>
        <p>No products found.</p>
    <?php endif; ?>

</main>

</body>
</html>
