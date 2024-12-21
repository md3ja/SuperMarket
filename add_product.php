<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "supermarket_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert product
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];
    $brand_name = $_POST['brand_name'];
    $sku = $_POST['sku'];
    $expiry_date = $_POST['expiry_date'];
    $quantity = $_POST['quantity'];

    $sql = "INSERT INTO products (product_name, brand_name, sku, expiry_date, quantity) 
            VALUES ('$product_name', '$brand_name', '$sku', '$expiry_date', '$quantity')";

    if ($conn->query($sql) === TRUE) {
        // Pass product name to notification script
        echo "<script>
                const productName = '$product_name';
                const notification = document.createElement('div');
                notification.className = 'alert-notification';
                notification.innerHTML = `<strong>Success!</strong> Product <em>${productName}</em> added successfully!`;
                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.remove();
                }, 5000);

                window.location.href = 'add_product.html';
              </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
