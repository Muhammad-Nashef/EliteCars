<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['is_admin'] == 0)
    header('Location: /user_dashboard.php');
else if (!isset($_SESSION['is_admin']))
    header('Location: /index.php');

if (!isset($_POST['carName']) || !isset($_POST['carRentPrice']) || !isset($_POST['carBuyPrice']) || !isset($_POST['carYear']) || !isset($_FILES['carImage']) || !isset($_POST['carPower']) || !isset($_POST['carMilage']) || !isset($_POST['car060'])) {
    header('Location: /collection/addCar.php');
}

$carName = $_POST['carName'];
$carRentPrice = $_POST['carRentPrice'];
$carRentPrice = "$" . $carRentPrice . "/month";

$carBuyPrice = $_POST['carBuyPrice'];
$carBuyPrice = "$" . $carBuyPrice;

$carYear = $_POST['carYear'];

$carImages = $_FILES['carImages'];

$carPower = $_POST['carPower'];
$carPower .= " HP";

$carMileage = $_POST['carMileage'];
$carMileage .= " KM";

$car060 = $_POST['car060'];
$car060 .= "s";

$imgDirPath = "../data/cars/";
$imageGeneralPath = $imgDirPath . str_replace('_1.webp', '.webp', $carImages['name'][0]);

$host = "localhost";
$username = "###";
$password = "###";
$dbname = "###";

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "INSERT INTO cars (name, rent_price, buy_price, year, image, power, mileage, acceleration)
            VALUES ('$carName', '$carRentPrice', '$carBuyPrice', '$carYear', '$imageGeneralPath', '$carPower', '$carMileage', '$car060')";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

if (isset($_FILES['carImages']) && !empty($_FILES['carImages']['name'][0])) {
    $uploadFolder = $imgDirPath;

    foreach ($_FILES['carImages']['tmp_name'] as $index => $tmpName) {
        $fileName = $_FILES['carImages']['name'][$index];
        $filePath = $uploadFolder . $fileName;
        try {
            move_uploaded_file($tmpName, $filePath);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
mysqli_close($conn);
header('Location: /collection/cars.php');
?>