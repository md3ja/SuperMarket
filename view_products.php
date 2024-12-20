<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "supermarket_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

if ($filter === 'expired') {
    $sql = "SELECT id, product_name, brand_name, sku, expiry_date, quantity FROM products WHERE expiry_date < CURDATE() ORDER BY expiry_date ASC";
} elseif ($filter === 'near_expiry') {
    $sql = "SELECT id, product_name, brand_name, sku, expiry_date, quantity FROM products WHERE expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) ORDER BY expiry_date ASC";
} else {
    $sql = "SELECT id, product_name, brand_name, sku, expiry_date, quantity FROM products ORDER BY expiry_date ASC";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Product List</h1>
        <div class="text-center mb-3">
            <a href="view_products.php" class="btn btn-secondary"><i class="fas fa-boxes"></i> All Products</a>
            <a href="view_products.php?filter=expired" class="btn btn-danger"><i class="fas fa-exclamation-triangle"></i> Expired Products</a>
            <a href="view_products.php?filter=near_expiry" class="btn btn-warning"><i class="fas fa-clock"></i> Near Expiry Products</a>
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product Name</th>
                    <th>Brand Name</th>
                    <th>SKU</th>
                    <th>Expiry Date</th>
                    <th>Quantity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="<?php 
                            if ($row['expiry_date'] < date('Y-m-d')) {
                                echo 'table-danger';
                            } elseif ($row['expiry_date'] >= date('Y-m-d') && $row['expiry_date'] <= date('Y-m-d', strtotime('+7 days'))) {
                                echo 'table-warning';
                            } else {
                                echo '';
                            }
                        ?>">
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['brand_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['sku']); ?></td>
                            <td><?php echo $row['expiry_date'] ? $row['expiry_date'] : 'N/A'; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td>
                                <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="delete_product.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Are you sure you want to delete this product?');">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No products found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="text-center mt-4">
            <a href="add_product.html" class="btn btn-success mx-2"><i class="fas fa-plus"></i> Add Product</a>
            <a href="dashboard.php" class="btn btn-info mx-2"><i class="fas fa-chart-line"></i> Go to Dashboard</a>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
