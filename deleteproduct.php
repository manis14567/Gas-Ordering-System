<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gasordering_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["productId"])) {
    $productId = $_GET["productId"];
    $delete_sql = "DELETE FROM products WHERE productId = $productId";
    if ($conn->query($delete_sql) === TRUE) {
        echo "Product deleted successfully";
        header("location: product.php");
        exit;
    } else {
        echo "Error deleting product: " . $conn->error;
    }
} else {
    echo "Invalid request";
    exit();
}

$conn->close();
?>
