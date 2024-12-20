<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "supermarket_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Total Products
$sqlTotal = "SELECT COUNT(*) AS total FROM products";
$resultTotal = $conn->query($sqlTotal);
$totalProducts = $resultTotal->fetch_assoc()['total'];

// Expired Products
$sqlExpired = "SELECT product_name, brand_name, expiry_date FROM products WHERE expiry_date < CURDATE()";
$resultExpired = $conn->query($sqlExpired);
$expiredProductNames = '';
while ($row = $resultExpired->fetch_assoc()) {
    $expiredProductNames .= $row['product_name'] . " (" . $row['brand_name'] . ") - Expired on: " . $row['expiry_date'] . "<br>";
}

// Products Near Expiry
$sqlNearExpiry = "SELECT product_name, brand_name, expiry_date, DATEDIFF(expiry_date, CURDATE()) AS days_left FROM products WHERE expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)";
$resultNearExpiry = $conn->query($sqlNearExpiry);
$nearExpiryProductNames = '';
while ($row = $resultNearExpiry->fetch_assoc()) {
    $nearExpiryProductNames .= $row['days_left'] . " days - " . $row['product_name'] . " (" . $row['brand_name'] . ")<br>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Product Dashboard</h1>
        <div class="d-flex justify-content-center">
            <div class="card text-center border-success mx-2">
                <div class="card-body">
                    <h5>Total Products</h5>
                    <p class="text-success">
                        <i class="fas fa-box-open"></i> <?php echo $totalProducts; ?>
                    </p>
                </div>
            </div>
            <div class="card text-center border-danger mx-2">
                <div class="card-body">
                    <h5>Expired Products</h5>
                    <p class="text-danger">
                        <i class="fas fa-times-circle"></i><br><?php echo $expiredProductNames ?: "None"; ?>
                    </p>
                </div>
            </div>
            <div class="card text-center border-warning mx-2">
                <div class="card-body">
                    <h5>Products Near Expiry (7 days)</h5>
                    <p class="text-warning">
                        <i class="fas fa-hourglass-half"></i><br><?php echo $nearExpiryProductNames ?: "None"; ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="add_product.html" class="btn btn-success mx-2">
                <i class="fas fa-plus"></i> Add Product
            </a>
            <a href="view_products.php" class="btn btn-primary mx-2">
                <i class="fas fa-list"></i> View Products
            </a>
        </div>
    </div>
</body>
</html>
