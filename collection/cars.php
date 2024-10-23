<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['error'])){
    unset($_SESSION['error']);
}
require_once('../security/protect.php');

$servername = "localhost";
$username = "###";
$password = "###";
$dbname = "###";


try {
    $conn = new mysqli($servername, $username, $password, $dbname);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$sql = "SELECT * FROM cars WHERE id NOT IN (SELECT car_id FROM orders WHERE car_id = id);";
$res = $conn->query($sql);
$data = array();

while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
    $data[] = $row;
}

$conn->close();

function add_suffix_to_file_name($file_name, $suff)
{
    $file_parts = pathinfo($file_name);
    return '../data/cars/' . $file_parts['filename'] . $suff . '.' . $file_parts['extension'];
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Cars Collection</title>
    <link rel="stylesheet" type="text/css" href="../css/cars.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Russo+One&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="../data/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="../data/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../data/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../data/favicon-16x16.png">

    <script src="../js/jquery-3.6.4.min.js"></script>
    <script src="../js/navigation.js"></script>

<style>
    .glass-texture {
        background: rgb(164 155 110 / 22%);
        border-radius: 16px;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(0px);
        -webkit-backdrop-filter: blur(0px);
        border: 1px solid rgba(201, 182, 86, 0.31);
    }

    #addCar {
        width: 80px;
        height: 80px;
        position: absolute;
        top: 40%;
        left: 44%;
        cursor: pointer;
        transition: transform 0.3s ease-out
    }

    #addCar:hover {
        transform: scale(1.1);
    }

    @media screen and (max-width: 1440px) {
        .add-to-cart-form {
            left: 13rem;
        }

        .car-item:hover .overlay {
            height: 40% !important;
        }

        .prices-container {
            margin: 1rem -1rem;
        }
    }

    @media screen and (max-width: 1024px) {
        .car-name-label {
            font-size: 18px;
        }

        .add-to-cart-form {
            scale: 0.8;
            position: relative;
            top: 1rem;
            left: 7rem;
            width: fit-content;
            margin: 0;
        }

        .car-specs-list {
            scale: 0.77;
            color: white;
            position: inherit;
            top: 25px;
            margin: -21px -53px;
        }
    }
</style>
</head>
<body>
    <?php require_once '../header.php'; ?>
    <div class="content">
        <div class="title-container">
            <h2 class="title">Our Collection</h2>
        </div>
        <div id="container">
            <?php
            $count = 0;
            $lazy_loading_limit = 6;

            if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
                echo '<div class="car-item glass-texture">';
                echo '<img id="addCar" src="../data/icons/plusIcon.png">';
                echo '</div>';
            }
            foreach ($data as $row) {
                $count++;

                echo '<div class="car-item">';
                $image_url = add_suffix_to_file_name($row['image'], '_1');

                if ($count <= $lazy_loading_limit)
                    echo '<img class="car-image" src="' . $image_url . '"></img>';
                else
                    echo '<img loading="lazy" class="car-image" src="' . $image_url . '"></img>';
                echo '<div class="overlay">';
                echo '<h3 class="car-name-label">' . $row['name'] . '</h3>';
                echo '<form class="add-to-cart-form" action="carReview.php" method="post">';
                echo '<input type="hidden" name="car_id" value="' . $row['id'] . '">';
                echo '<button type="submit" class="add-to-cart-button">More Info</button>';
                echo '</form>';
                echo '<ul class="car-specs-list">';
                echo '<div class="info-container">';
                echo '<p>Year: ' . $row['year'] . '</p>';
                echo '<p>Mileage: ' . $row['mileage'] . '</p>';
                echo '</div>';
                echo '<div class="prices-container">';
                echo '<div class="rent-price-label">Rent Price: ' . $row['rent_price'] . '</div>';
                echo '<div class="buy-price-label">Buy Price: ' . $row['buy_price'] . '</div>';
                echo '</div>';
                echo '</ul>';
                echo '</div>';
                echo '</div>';
            }
            ?>

        </div>
        <div id="sponsers_logo">
            <img class="sponsor-logo" draggable="false" src="../data/bmw_logo.ico"
                onclick="window.location.href='https://www.bmw.com/en/index.html'"></img>
            <img class="sponsor-logo" draggable="false" src="../data/mercedes_logo.ico"
                onclick="window.location.href='https://www.mercedes-benz.com/en/'"></img>
            <img class="sponsor-logo" draggable="false" src="../data/porsche_logo.ico"
                onclick="window.location.href='https://www.porsche.com/usa/'"></img>
        </div>
    </div>

<script>
    var cartIcon = document.querySelector('.cart-icon');
    var cartCount = document.querySelector('.cart-count');

    var addToCartButtons = document.querySelectorAll('.add-to-cart');
    var addNewCar = document.querySelector('#addCar');
    addToCartButtons?.forEach(function (button) {
        button?.addEventListener('click', function (event) {
            var count = parseInt(cartCount.innerText);
            cartCount.innerText = count + 1;
            cartIcon.innerHTML = '<i class="fas fa-shopping-cart"></i>' + cartCount.innerText;
        });
    });

    addNewCar?.addEventListener('click', function (event) {
        window.location.href = 'addCar.php';
    });
</script>
</body>
</html>