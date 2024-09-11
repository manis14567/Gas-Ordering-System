<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        h2 {
            margin-top: 0;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border: 2px solid #333;
        }

        th, td {
            border: 1px solid #333;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        form {
            display: inline;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-buttons button {
            padding: 8px 12px;
            border: none;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .action-buttons button:hover {
            background-color: #0056b3;
        }

        select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .back-button {
            position: fixed;
            bottom: 20px;
            left: 20px;
            padding: 10px 20px;
            border: none;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-decoration: none;
        }

        @media (max-width: 768px) {
            table {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Orders</h2>

        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "gasordering_db";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && isset($_POST['order_id'])) {
            $action = $_POST['action'];
            $order_id = intval($_POST['order_id']);

            if ($action === 'update' && isset($_POST['status'])) {
                $status = htmlspecialchars($_POST['status']);

                $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
                if ($stmt === false) {
                    die("Error preparing statement: " . $conn->error);
                }
                $stmt->bind_param("si", $status, $order_id);

                if ($stmt->execute()) {
                    echo "<p>Order status updated successfully</p>";
                } else {
                    echo "<p>Error: " . $stmt->error . "</p>";
                }

                $stmt->close();
            } elseif ($action === 'delete') {
                $stmt = $conn->prepare("DELETE FROM orders WHERE order_id = ?");
                if ($stmt === false) {
                    die("Error preparing statement: " . $conn->error);
                }
                $stmt->bind_param("i", $order_id);

                if ($stmt->execute()) {
                    echo "<p>Order deleted successfully</p>";
                } else {
                    echo "<p>Error: " . $stmt->error . "</p>";
                }

                $stmt->close();
            }
        }

        $sql = "SELECT * FROM orders";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>Order ID</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row["order_id"]) . "</td>
                        <td>" . htmlspecialchars($row["product_name"]) . "</td>
                        <td>" . htmlspecialchars($row["quantity"]) . "</td>
                        <td>" . htmlspecialchars($row["total_price"]) . "</td>
                        <td>" . htmlspecialchars($row["order_date"]) . "</td>
                        <td>" . htmlspecialchars($row["status"]) . "</td>
                        <td class='action-buttons'>
                            <form action='' method='post'>
                                <input type='hidden' name='order_id' value='" . htmlspecialchars($row["order_id"]) . "'>
                                <select name='status'>
                                    <option value='pending' " . ($row["status"] == 'pending' ? 'selected' : '') . ">Pending</option>
                                    <option value='delivered' " . ($row["status"] == 'delivered' ? 'selected' : '') . ">Delivered</option>
                                </select>
                                <button type='submit' name='action' value='update'>Update</button>
                            </form>
                            <form action='' method='post'>
                            <input type='hidden' name='order_id' value='" . htmlspecialchars($row["order_id"]) . "'>
                            <button type='submit' name='action' value='delete'>Delete</button>
                        </form>
                        </td>
                    </tr>";
            }

            echo "</table>";
        } else {
            echo "<p>No orders found</p>";
        }

        $conn->close();
        ?>
        <a href="admin_dashboard.php" class="back-button">Back </a>
    </div>
</body>
</html>
