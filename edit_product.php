
<?php
/* 
File: edit_product.php
Description: Allows editing of product details based on the product ID.
*/

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "supermarket_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Retrieve product data
if ($id > 0) {
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
}

// Update product data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];
    $brand_name = $_POST['brand_name'];
    $sku = $_POST['sku'];
    $expiry_date = !empty($_POST['expiry_date']) ? $_POST['expiry_date'] : NULL;
    $quantity = $_POST['quantity'];

    $update_sql = "UPDATE products SET product_name = ?, brand_name = ?, sku = ?, expiry_date = ?, quantity = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssii", $product_name, $brand_name, $sku, $expiry_date, $quantity, $id);

    if ($update_stmt->execute()) {
        echo "<h3 style='color:green;'>Product updated successfully!</h3>";
        echo "<a href='view_products.php'>Back to Products</a>";
        exit;
    } else {
        echo "<h3 style='color:red;'>Error: " . $update_stmt->error . "</h3>";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Edit Product</h1>
        <form action="" method="post" class="p-4 border rounded bg-light shadow">
            <div class="mb-3">
                <label for="product_name" class="form-label">Product Name:</label>
                <input type="text" name="product_name" class="form-control" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="brand_name" class="form-label">Brand Name:</label>
                <input type="text" name="brand_name" class="form-control" value="<?php echo htmlspecialchars($product['brand_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="sku" class="form-label">SKU:</label>
                <input type="text" name="sku" class="form-control" value="<?php echo htmlspecialchars($product['sku']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="expiry_date" class="form-label">Expiry Date:</label>
                <input type="date" name="expiry_date" class="form-control" value="<?php echo $product['expiry_date']; ?>">
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity:</label>
                <input type="number" name="quantity" class="form-control" min="1" value="<?php echo $product['quantity']; ?>" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Update Product</button>
        </form>
        <a href="view_products.php" class="btn btn-secondary mt-3">Back to Product List</a>
    </div>
</body>
</html>
