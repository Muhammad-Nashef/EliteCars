<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
$user_id = $_SESSION['user_id'];


$host = "localhost";
$username = "###";
$password = "###";
$dbname = "###";


$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

            $sql="SELECT users.name, users.username, users.email, users.address,users.profile_image AS img ,orders.order_id, orders.ordered_car, orders.purchase_type , orders.date, orders.total,(SELECT COUNT(*) FROM users
            JOIN orders ON users.id = orders.user_id
            WHERE users.id = $user_id ) AS totalOrders
            FROM users
            LEFT JOIN orders ON users.id = orders.user_id
            WHERE users.id = $user_id;";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$orders = array();

$user = null;

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $total_Orders = $row['totalOrders'];
    $result = mysqli_query($conn, $sql);
    if ($total_Orders > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $orders[] = $row;
        }
    }
    $userResult = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($userResult);
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $imagePath = $row['img'];
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap-grid.min.css">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="shortcut icon" href="../data/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="../data/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../data/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../data/favicon-16x16.png">
    <script src="../js/jquery-3.6.4.min.js"></script>
    <style>
        :root {
            --var-accent-color: #cbab3b;
            --var-accent-hover: #9e8631;
            --var-accent-paragraph: #c9b467;
            --var-overlay-text: #68a7ff;
            --var-profile-text: #c4d75c;
        }

        body {
            padding: 20px;
        }

        h1 {
            margin-bottom: 20px;
            color: #333;
        }

        h2 {
            margin-top: 40px;
            color: #333;
        }

        ul {
            list-style: none;
            padding-left: 0;
        }

        li {
            margin-bottom: 10px;
        }

        a {
            color: #007bff;
        }

        .profile-container {
            margin: 10rem auto;
        }

        .welcome-title {
            color: var(--var-accent-color);
            text-align: center;
        }

        .orders-container {
            float: left;
            padding-left: 10rem;
        }

        .orders-title {
            color: #4cb182;
        }

        .orders-list {
            width: max-content;
        }

        .user-details-title {
            color: #4cb182;
        }

        .card {
            background: transparent;
            color: var(--var-profile-text);
            border: 1px solid #808080a6;
            text-align: center;
        }

        .card:hover {
            border: 1px solid #cbab3ba1;
        }

        .user-details {
            float: left;
            color: var(--var-profile-text);
            font-size: x-large;
        }

        .user-details p {
            padding-left: 1ch
        }

        .items-container {
            display: flex;
            justify-content: space-between;
            margin: 0 0 0 10rem;
        }

        .circle-image {
            border-radius: 50%;
            overflow: hidden;
            width: 200px;
            height: 200px;
            margin: auto;
        }

        .circle-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .rotate {
            transform: rotateX(360deg) rotateY(360deg);
            transition: transform 1s ease;
        }
    </style>

    
    <script>
        $(document).ready(function() {
            var image = $("#profImg");
            image.on("click", function() {
                image.toggleClass("rotate");
            });
        });
    </script>
</head>

<body>
    <?php require_once '../header.php'; ?>
    <div class="container profile-container">
        <h1 class="welcome-title">Welcome
            <?php echo isset($user['name']) ? ", " . $user['name'] : ''; ?>!
        </h1>
        <div class="row justify-content-center align-items-center">
            <div class="col-12 col-md-6 text-center">
                <div class="circle-image">
                    <img id="profImg" src="<?php echo $imagePath; ?>">
                </div>
            </div>
        </div>
        <div class="items-container">
            <div class="col user-details pb-3 col-lg-5">
                <h2 class="user-details-title">Details:</h2>
                <p>
                    <?php echo isset($user['username']) ? "Username: " . $user['username'] : ''; ?>
                </p>
                <p>
                    <?php echo isset($user['email']) ? "Email: " . $user['email'] : ''; ?>
                </p>
                <p>
                    <?php echo isset($user['address']) ? "Address: " . $user['address'] : 'no address'; ?>
                </p>
            </div>
            <?php if (!empty($orders) && isset($_SESSION['is_admin']) && !$_SESSION['is_admin']): ?>
                <div class="col orders-container col-lg-5">
                    <h2 class="orders-title pb-3">Your Orders:</h2>
                    <ul class="orders-list">
                        <?php $orderNumber = 1; ?>
                        <?php foreach ($orders as $order): ?>
                            <li>
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Order #
                                            <?php echo $orderNumber; ?>
                                        </h5>
                                        <p class="card-text">Car:
                                            <?php echo $order['ordered_car']; ?>
                                        </p>
                                        <p class="card-text">Purchase Type:
                                            <?php echo $order['purchase_type']; ?>
                                        </p>
                                        <p class="card-text">Total: $
                                            <?php echo isset($order['total']) ? $order['total'] : ""; ?>
                                        </p>
                                        <p class = "card-text">Date:
                                            <?php echo isset($order['date']) ? $order['date'] : "";?>
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <?php $orderNumber++; ?>
                        <?php endforeach; ?>
                    </ul>
                <?php elseif(isset($_SESSION['is_admin']) && !$_SESSION['is_admin']):?>

                    <p class="orders-title pb-3">You have no orders yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>

</html>