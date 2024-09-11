<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gasordering_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$order_id = $_GET["id"] ?? 0;
$fullname = "";
$address = "";
$product = "";
$quantity = 0;
$product_price = 0.00;
$contact = "";
$order_date = "";
$status = "";

$sql = "SELECT * FROM orders WHERE order_id = '$order_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $fullname = $row["fullname"];
    $address = $row["address"];
    $product = $row["product"];
    $quantity = $row["quantity"];
    $product_price = $row["product_price"];
    $contact = $row["contact"];
    $order_date = $row["order_date"];
    $status = $row["status"];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST["fullname"];
    $address = $_POST["address"];
    $product = $_POST["product"];
    $quantity = $_POST["quantity"];
    $product_price = $_POST["product_price"];
    $amount = $quantity * $product_price;
    $contact = $_POST["contact"];
    $status = $_POST["status"];

    $updateSql = "UPDATE orders
                  SET fullname='$fullname', address='$address', product='$product', quantity=$quantity, 
                      product_price=$product_price, amount=$amount, contact='$contact', status='$status'
                  WHERE order_id = $order_id";

    if ($conn->query($updateSql) === TRUE) {
        echo "<p class='confirmation'>Order updated successfully.</p>";
        header("Location: order.php");
    } else {
        echo "Error updating order: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Order</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: LightGray; 
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        form {
            background-color: White; 
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: left;
        }
        h2 {
            color: DimGray; 
            margin-bottom: 30px;
        }
        label {
            display: block;
            margin-top: 15px;
            font-size: 14px;
            color: DarkGray; 
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 1px solid LightGray; 
            border-radius: 4px;
            font-size: 14px;
        }
        button {
            background-color: DodgerBlue; 
            color: White; 
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: SteelBlue;
        }
        .confirmation {
            color: FireBrick;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <form method="post" action="">
        <h2>Update Order</h2>
        <label for="fullname">Full Name:</label>
        <input type="text" name="fullname" value="<?php echo $fullname; ?>" required><br>

        <label for="address">Address:</label>
        <input type="text" name="address" value="<?php echo $address; ?>" required><br>

        <label for="product">Product:</label>
        <input type="text" name="product" value="<?php echo $product; ?>" required><br>

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" min="1" value="<?php echo $quantity; ?>" required><br>
        
        <label for="product_price">Product Price:</label>
        <input type="number" step="0.01" name="product_price" min="1" value="<?php echo $product_price; ?>" required><br>

        <label for="contact">Contact:</label>
        <input type="text" name="contact" value="<?php echo $contact; ?>" required><br>

        <label for="status">Status:</label>
        <select name="status" required>
            <option value="Pending" <?php if($status == "Pending") echo "selected"; ?>>Pending</option>
            <option value="In Process" <?php if($status == "In Process") echo "selected"; ?>>In Process</option>
            <option value="Delivered" <?php if($status == "Delivered") echo "selected"; ?>>Delivered</option>
        </select><br>

        <button type="submit">Update Order</button>
    </form>
</body>
</html>

<?php
$conn->close();
?>
