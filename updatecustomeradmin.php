<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gasordering_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $username = $contact = $email = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];

    $updateSql = "UPDATE tbl_user SET 
                    username = '$username', 
                    contact = '$contact', 
                    email = '$email'
                  WHERE user_id = $user_id";

    if ($conn->query($updateSql) === TRUE) {
        echo "Customer updated successfully!";
    } else {
        echo "Error updating customer: " . $conn->error;
    }
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $selectSql = "SELECT * FROM tbl_user WHERE user_id = $user_id";
    $result = $conn->query($selectSql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $username = $row['username'];
        $contact = $row['contact'];
        $email = $row['email'];
    } else {
        header("Location: customer.php");
        exit();
    }
} else {
    header("Location: customer.php");
    exit();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Customer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        form {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            max-width: 100%;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }
        input[type="text"],
        input[type="email"] {
            width: calc(100% - 16px);
            padding: 8px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }
        input[type="submit"] {
            background-color: red;
            color: white;
            cursor: pointer;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: skyblue;
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
    </style>
</head>
<body>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $user_id; ?>">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
        
        <label for="contact">Contact:</label>
        <input type="text" id="contact" name="contact" value="<?php echo $contact; ?>" required>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
        
        <input type="submit" value="Update Customer">
    </form>
    <a href="customer.php" class="back-button">Back</a>
</body>
</html>
