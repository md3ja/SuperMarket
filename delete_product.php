
<?php
/* 
File: delete_product.php
Description: Deletes a product from the database based on the product ID.
*/

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "supermarket_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve product ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Perform deletion if ID is valid
if ($id > 0) {
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<h3 style='color:green;'>Product deleted successfully!</h3>";
    } else {
        echo "<h3 style='color:red;'>Error: " . $stmt->error . "</h3>";
    }
} else {
    echo "<h3 style='color:red;'>Invalid product ID!</h3>";
}

echo "<a href='view_products.php'>Back to Products</a>";

$conn->close();
?>
