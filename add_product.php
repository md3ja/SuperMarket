
<?php
/* 
File: add_product.php
Description: PHP script to insert a new product into the database using prepared statements.
*/

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "supermarket_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve input values from the form
$product_name = $_POST['product_name'];
$brand_name = $_POST['brand_name'];
$sku = $_POST['sku'];
$expiry_date = !empty($_POST['expiry_date']) ? $_POST['expiry_date'] : NULL;
$quantity = $_POST['quantity'];

// Insert query using prepared statements to prevent SQL injection
$sql = "INSERT INTO products (product_name, brand_name, sku, expiry_date, quantity) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $product_name, $brand_name, $sku, $expiry_date, $quantity);

// Execute query and provide feedback
if ($stmt->execute()) {
    echo "<h3 style='color:green;'>New product added successfully!</h3>";
    echo "<a href='add_product.html'>Add Another Product</a> | <a href='dashboard.php'>Go to Dashboard</a>";
} else {
    echo "<h3 style='color:red;'>Error: " . $stmt->error . "</h3>";
}

// Close connections
$stmt->close();
$conn->close();
?>
