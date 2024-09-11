<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gasordering_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (isset($_GET['id'])) {
    $deleteId = $_GET['id'];
    $deleteSql = "DELETE FROM tbl_user WHERE user_id = $deleteId";
    if ($conn->query($deleteSql) === TRUE) {
        echo "Customer deleted successfully!";
    } else {
        echo "Error deleting customer: " . $conn->error;
    }
} else {
    echo "Customer ID not specified.";
}
$conn->close();
?>
