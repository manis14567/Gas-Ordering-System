<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gasordering_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM tbl_user";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid blue;
        }
        th {
            background-color: blue;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .action {
            text-align: center;
        }
        .action a {
            text-decoration: none;
            padding: 5px 10px;
            background-color: #f44336;
            color: white;
            border-radius: 3px;
            margin-right: 5px;
        }
        .action a:hover {
            background-color: #d32f2f;
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
    <h2>Customer List</h2>
    <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Contact</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["user_id"]. "</td>
                            <td>" . $row["username"]. "</td>
                            <td>" . $row["contact"]. "</td>
                            <td>" . $row["email"]. "</td>
                            <td class='action'>
                                <a href='updatecustomer.php?id=" . $row["user_id"]. "'>Update</a>
                                <a href='deletecustomer.php?id=" . $row["user_id"]. "' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No customers found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <a href="admin_dashboard.php" class="back-button">Back </a>
</body>
</html>

<?php
$conn->close();
?>
