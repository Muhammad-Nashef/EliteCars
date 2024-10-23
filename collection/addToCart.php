<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../account/index.php');
    exit();
}

if (!isset($_POST['car_id'])) {
    header('Location: ../collection/cars.php');
    exit();
}
$car_id = $_POST['car_id'];
$user_id = $_SESSION['user_id'];

$servername = "localhost";
$username = "###";
$password = "###";
$dbname = "###";

$conn = new mysqli($servername, $username, $password, $dbname);

$sql = "INSERT INTO orders (user_id, car_id) VALUES ('$user_id', '$car_id')";
$res = $conn->query($sql);

if (!$res) {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

exit();
?>