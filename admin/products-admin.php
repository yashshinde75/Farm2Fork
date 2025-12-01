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
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body>

<!-- ✅ RESPONSIVE NAVBAR -->
<nav class="navbar navbar-light bg-light shadow-sm">
<div class="container d-flex align-items-center justify-content-between">
    <a class="navbar-brand text-danger fw-bold" href="dashboard.php">
        Farm2Fork Admin
    </a>
    <div class="d-flex gap-2">
        <a href="dashboard.php" class="btn btn-outline-danger btn-sm">
            Dashboard
        </a>
        <a href="admin-login.php?logout=1" class="btn btn-outline-danger btn-sm">
            Logout
        </a>
    </div>
</div>
</nav>

<main class="container py-4">

    <h3 class="text-danger fw-bold mb-4 text-center text-md-start">
        Manage Products
    </h3>

    <!-- ✅ ADD PRODUCT (BUTTON FIXED ONLY) -->
    <div class="card p-3 mb-4 shadow-sm">
        <h5 class="mb-3">Add New Product</h5>

        <form method="post">
            <div class="row g-2 g-md-3">

                <div class="col-12 col-md-3">
                    <input type="text"
                           name="name"
                           class="form-control"
                           placeholder="Product Name"
                           required>
                </div>

                <div class="col-12 col-md-2">
                    <input type="number"
                           step="0.01"
                           name="price"
                           class="form-control"
                           placeholder="Price"
                           required>
                </div>

                <div class="col-12 col-md-4">
                    <input type="text"
                           name="description"
                           class="form-control"
                           placeholder="Description">
                </div>

                <div class="col-12 col-md-3">
                    <input type="text"
                           name="image"
                           class="form-control"
                           placeholder="Image filename">
                </div>
            </div>

            <!-- ✅ ONLY THIS BUTTON WAS CHANGED -->
            <button type="submit"
                    name="add"
                    class="btn btn-danger mt-3 px-4">
                Add Product
            </button>
        </form>
    </div>

    <!-- ✅ PRODUCTS TABLE -->
    <?php if ($products): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-nowrap">
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

                    <td>
                        <input type="text"
                               name="name"
                               value="<?= htmlspecialchars($p['name']) ?>"
                               class="form-control form-control-sm">
                    </td>

                    <td>
                        <input type="number"
                               step="0.01"
                               name="price"
                               value="<?= $p['price'] ?>"
                               class="form-control form-control-sm">
                    </td>

                    <td>
                        <input type="text"
                               name="description"
                               value="<?= htmlspecialchars($p['description']) ?>"
                               class="form-control form-control-sm">
                    </td>

                    <td>
                        <input type="text"
                               name="image"
                               value="<?= htmlspecialchars($p['image']) ?>"
                               class="form-control form-control-sm">
                    </td>

                    <td class="d-flex gap-1 flex-wrap">
                        <button type="submit"
                                name="update"
                                class="btn btn-sm btn-success">
                            Update
                        </button>

                        <button type="submit"
                                name="delete"
                                onclick="return confirm('Delete this product?')"
                                class="btn btn-sm btn-danger">
                            Delete
                        </button>
                    </td>

                </form>
            </tr>
            <?php endforeach; ?>

            </tbody>
        </table>
    </div>
    <?php else: ?>
        <p class="text-muted text-center">No products found.</p>
    <?php endif; ?>

</main>

</body>
</html>
