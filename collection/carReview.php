<?php
    error_reporting(E_ERROR | E_WARNING | E_PARSE); 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        header('Location: ../account/createaccount.php');
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
    
    $sql = "SELECT * FROM cars WHERE id = $car_id";
    $res = $conn->query($sql);
    
    if (!$res) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
        
    if ($res->num_rows > 0) 
        $car = $res->fetch_assoc();
    
    $_SESSION['sub_title_string'] = $car['name'];

    $conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/cars.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Russo+One&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/9e79517447.js" crossorigin="anonymous"></script>
    <title>Car Review</title>
    <link rel="shortcut icon" href="../data/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="../data/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../data/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../data/favicon-16x16.png">

    <script src="../js/jquery-3.6.4.min.js"></script>
    <script src="../js/navigation.js"></script>

<style>
:root {
    --var-accent-color: #cbab3b;
    --var-accent-paragraph: #c9b467;
}

body {
    padding: 0rem 2rem 5rem 2rem;
}

h2 {
    text-align: center;
    font-size: 30px;
    color: var(--var-accent-color);
}

.photo-gallery {
    display: grid;
    align-items: stretch;
    grid-template-columns: repeat(3, 1fr);
    grid-gap: 5rem;
    position: relative;
    top: 5rem;
}

.photo-gallery img {
    width: 100%;
    height: auto;
    border-radius: 3rem;
    box-shadow: 1px 1px 15px #cbab3b;
    transition: transform 0.3s ease-out;
}

.photo-gallery img:hover {
    transform: scale(1.1);
}

.more-info {
    display: grid;
    color: var(--var-accent-paragraph);
}
</style>
</head>
<body>
    <?php require_once '../header.php'; ?>
    <div class="car-details">
        <h2><?php echo $car['name']; ?></h2>
        <section class="more-info">
            <span><img src="../data/icons/calendar.svg" class="calendar"></img> Year: <?php echo $car['year']; ?></span>
            <span><img src="../data/icons/power.svg" class="power"></img> Power: <?php echo $car['power']; ?></span>
            <span><img src="../data/icons/road.svg" class="road"></img> Mileage: <?php echo $car['mileage']; ?></span>
            <span><img src="../data/icons/speed.svg" class="gauge"></img> Acceleration:
                <?php echo $car['acceleration']; ?></span>

            <div class="payment-info"><img class="info" src="../data/icons/info.svg" />Click the price to buy or rent
                the car</div>
            <form class="payment-container" action="../order/payment.php" method="POST">
                <button class="submit-payment-btn" type="submit" name="payment_type" value="rent">
                    <img src="../data/icons/money.svg" class="money"></img>
                    Rent Price: <?php echo $car['rent_price']; ?>
                    <input type="hidden" name="rent_price" value="<?php echo $car['rent_price']; ?>">
                </button>
                <span class="submit-btn-divider">|</span>
                <button class="submit-payment-btn" type="submit" style="margin-left: 2em;" name="payment_type"
                    value="buy">
                    Buy Price: <?php echo $car['buy_price']; ?>
                    <input type="hidden" name="buy_price" value="<?php echo $car['buy_price']; ?>">
                    <input type="hidden" name="car_name" value="<?php echo $car['name']; ?>">
                    <input type="hidden" name="car_id" value="<?php echo $_POST['car_id']; ?>">
                </button>
            </form>
    </div>
    </section>
    <?php
        echo "<div class='photo-gallery'>";
            for ($i = 1; $i <= 3; $i++) {
                $file_name = basename($car['image']);
                $file_parts = pathinfo($file_name);
                $image_url = '/data/cars/' . $file_parts['filename'] . '_' . $i . '.' . $file_parts['extension'];
                echo "<img loading='lazy' src='$image_url' class='gallery-image'>";
            }
        echo "</div>";
    ?>
    </div>
</body>
</html>