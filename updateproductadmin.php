<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 400px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"],
        input[type="submit"],
        input[type="radio"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: blue;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .back-button {
            position: absolute;
            bottom: 20px;
            left: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .back-button:hover {
            background-color: #0056b3;
        }

        .radio-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .radio-group label {
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <div class="container">

        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "gasordering_db";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $productId = "";
        $productName = "";
        $quantity = "";
        $productPrice = "";
        $imagePath = "";
        $availability = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['productId'])) {
            $productId = intval($_POST['productId']);
            $productName = htmlspecialchars($_POST['product_name']);
            $quantity = intval($_POST['quantity']);
            $productPrice = floatval($_POST['product_price']);

            if ($productPrice < 1500) {
                echo "<p>Error: Product price cannot be less than 1500.</p>";
            } elseif ($quantity < 1) {
                echo "<p>Error: Quantity must be at least 1.</p>";
            } else {
                if (isset($_FILES['image_path']) && $_FILES['image_path']['error'] == UPLOAD_ERR_OK) {
                    $uploadDir = 'uploads/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    $imagePath = $uploadDir . basename($_FILES['image_path']['name']);
                    if (!move_uploaded_file($_FILES['image_path']['tmp_name'], $imagePath)) {
                        echo "<p>Error: Failed to upload image.</p>";
                    }
                } else {
                    $imagePath = htmlspecialchars($_POST['existing_image_path']);
                }

                $availability = isset($_POST['availability']) ? intval($_POST['availability']) : 0;

                $stmt = $conn->prepare("UPDATE products SET product_name = ?, quantity = ?, product_price = ?, image_path = ?, availability = ? WHERE productId = ?");
                if ($stmt === false) {
                    die("Error preparing statement: " . $conn->error);
                }
                $stmt->bind_param("sidsii", $productName, $quantity, $productPrice, $imagePath, $availability, $productId);

                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        echo "<p>Product updated successfully</p>";
                    } else {
                        echo "<p>No changes made to the product.</p>";
                    }
                } else {
                    echo "<p>Error: " . $stmt->error . "</p>";
                }

                $stmt->close();
            }
        } else if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['productId'])) {
            $productId = intval($_GET['productId']);
            $sql = "SELECT * FROM products WHERE productId = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("Error preparing statement: " . $conn->error);
            }
            $stmt->bind_param("i", $productId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $product = $result->fetch_assoc();
                $productName = $product['product_name'];
                $quantity = $product['quantity'];
                $productPrice = $product['product_price'];
                $imagePath = $product['image_path'];
                $availability = $product['availability'];
            } else {
                echo "<p>Product not found</p>";
            }

            $stmt->close();
        }

        $conn->close();
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="productId" value="<?php echo htmlspecialchars($productId); ?>">
            <input type="hidden" name="existing_image_path" value="<?php echo htmlspecialchars($imagePath); ?>">
            
            <label for="product_name">Product Name:</label>
            <input type="text" id="product_name" name="product_name" value="<?php echo htmlspecialchars($productName); ?>" required><br><br>
            
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" value="<?php echo htmlspecialchars($quantity); ?>" required min="1" max="30"><br><br>
            
            <label for="product_price">Product Price:</label>
            <input type="text" id="product_price" name="product_price" value="<?php echo htmlspecialchars($productPrice); ?>" required><br><br>
            
            <label for="image_path">Image Path:</label>
            <input type="file" id="image_path" name="image_path"><br><br>
            <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Product Image" style="max-width: 100px;"><br><br>
            
            <label>Availability:</label>
            <div class="radio-group">
                <label>
                    <input type="radio" name="availability" value="1" <?php echo $availability ? 'checked' : ''; ?>> Available
                </label>
                <label>
                    <input type="radio" name="availability" value="0" <?php echo !$availability ? 'checked' : ''; ?>> Unavailable
                </label>
            </div>
            
            <input type="submit" value="Update Product">
            <button type="button" class="back-button" onclick="window.history.back()">Back</button>
        </form>
    </div>
</body>
</html>
