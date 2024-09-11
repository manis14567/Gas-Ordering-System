<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Order</title>
</head>
<body>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname ="gasordering_db";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["delete"])) {
    $deleteId = $_GET["delete"];
    $deleteSql = "DELETE FROM orders WHERE order_id = $deleteId";
    if ($conn->query($deleteSql) === TRUE) {
    echo "Order with ID $deleteId has been deleted successfully.";
    header("Location: order.php");
    } else {
        echo "Error deleting order: " . $conn->error;
    }
} else {
    echo "Invalid request. Please provide an order ID for deletion.";
}
$conn->close();
?>
</body>
</html>
