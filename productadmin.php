<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <style>
       body {
            font-family: Arial, sans-serif;
            background-color: Gainsboro;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px 0;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            background-color: White;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow: hidden;
        }

        th, td {
            border: 1px solid LightGray;
            padding: 12px 15px;
            text-align: left;
        }

        th {
            background-color: MediumBlue;
            color: White;
        }

        tr:nth-child(even) {
            background-color: FloralWhite;
        }

        tr:hover {
            background-color: LightGray;
        }

        a {
            text-decoration: none;
            color: MediumBlue;
            margin-right: 5px;
        }

        a:hover {
            color: DarkBlue;
        }

        .back-button, .add-product-button {
            padding: 10px 20px;
            background-color: blue;
            color: White;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
            display: inline-block;
            text-align: center;
        }

        .back-button:hover, .add-product-button:hover {
            background-color: skyblue;
        }

        .back-button {
            position: fixed;
            bottom: 20px;
            left: 20px;
        }

        .add-product-button {
            margin-bottom: 20px;
        }

        .actions {
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }

        .actions a {
            margin-right: 10px;
            padding: 5px 10px;
            border-radius: 3px;
            transition: background-color 0.3s;
        }

        .actions a.update {
            background-color: Gold;
            color: White;
        }

        .actions a.update:hover {
            background-color: DarkGoldenRod;
        }

        .actions a.delete {
            background-color: FireBrick;
            color: White;
        }

        .actions a.delete:hover {
            background-color: Crimson;
        }
    </style>
</head>
<body>

<div class="container">
    <a href="addproduct.php" class="add-product-button">Add Product</a>

    <?php
    $servername = "localhost"; 
    $username = "root"; 
    $password = ""; 
    $dbname = "gasordering_db"; 

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT productId, product_name, quantity, product_price FROM products";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["productId"] . "</td>
                    <td>" . $row["product_name"] . "</td>
                    <td>" . $row["quantity"] . "</td>
                    <td>" . $row["product_price"] . "</td>
                    <td class='actions'>
                        <a href='update.php?productId=" . $row["productId"] . "' class='update'>Update</a>
                        <a href='delete.php?productId=" . $row["productId"] . "' class='delete' onclick='return confirm(\"Are you sure you want to delete this product?\");'>Delete</a>
                    </td>
                </tr>";
        }

        echo "</table>";
    } else {
        echo "0 results";
    }

    $conn->close();
    ?>

    <a href="admin_dashboard.php" class="back-button">Back</a>
</div>

</body>
</html>
