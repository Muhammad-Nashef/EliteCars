<?php
    session_start();
    // error_reporting(0);

    if (!isset($_SESSION['user_id'])) {
        header('Location: ../account/index.php');
        exit();
    }

    if (!isset($_POST['car_id'])) {
        header('Location: ../collection/cars.php');
        exit();
    }

    $error = "";

    function sendOrderMail($to, $subject, $message){
        mail($to, $subject, $message);
    }

    function convertStringToDate($dateString) {
        $date = DateTime::createFromFormat('m/Y', $dateString);
    
        if ($date !== false) {
            $convertedDate = $date->format('Y-m-d');
            return $convertedDate;
        } else {
            // Handle invalid date format
            return null;
        }
    }

    function verifyDetailsWithCards($cardNumber, $cvv, $expiryDate, $name, $price) {
        $card1 = [
            'name' => 'John Doe',
            'card_number' => "5135 1135 1355 7775",
            'cvv' => 241,
            'expiry_date' => "05/2027",
            'balance' => 10000000
        ];
        
        $card2 = [
            'name' => 'Mark Doe',
            'card_number' => "4557 4314 8592 6105",
            'cvv' => 787,
            'expiry_date' => "04/2029",
            'balance' => 777
        ];

        $cards = [$card1, $card2];

        global $error;

        foreach ($cards as $card) {
            if ($card['card_number'] == $cardNumber && $card['cvv'] == $cvv && strtolower($card['name']) == strtolower($name)) {
                $date = convertStringToDate($card['expiry_date']);
                $today = date("Y-m-d");
                if ($date > $today) {
                    $checkBalance = $card['balance'] - $price; 
                    if ( $checkBalance >= 0 ) {
                        return true;
                    } else {
                        $error .= "Insufficient funds.<br>";
                        return false;
                    }
                } else {
                    $error .= "Card has expired.<br>";
                    return false;
                }
            }
        }
        $error = "Card details are incorrect.<br>";
        return false;
    }

    $servername = "localhost";
    $username = "###";
    $password = "###";
    $dbname = "###";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST["name"];
        $cardNumber = $_POST["card_number"];
        $expiryDate = $_POST["expiry_date"];
        $cvv = $_POST["cvv"];
        $carName = $_POST["car_name"];
        $car_id = $_POST['car_id'];
        $user_id = $_SESSION['user_id'];
        $paymentType = $_POST["payment_type"];
        $rawPrice = $_POST["price"];
        $currentDate = date("Y-m-d");
        $estimatedDelivery = date("Y-m-d", strtotime($currentDate . " +30 days")); //default - 30 days
        $email = $_SESSION['email'];

        $cleanPrice = preg_replace('/[^0-9.]/', '', $rawPrice);
        $price = floatval($cleanPrice);

        $res = verifyDetailsWithCards($cardNumber, $cvv, $expiryDate, $name, $price);

        if(!$res){
            $_SESSION['error'] = $error;
            if(!isset($_POST['car_id']))
                $_POST['car_id'] = $car_id;
            unset($_SESSION['error']);
            header('Location: ../collection/cars.php');
            exit();
        }

        $to = $email;
        $subject = "Thank You for using Elite Cars!";
        $message = "Your car will be delivered to you in " . $estimatedDelivery ;
    
        sendOrderMail($to, $subject, $message);

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "INSERT INTO orders (user_id, car_id, ordered_car, purchase_type, total, date) VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $date = date("Y-m-d");
        $stmt->bind_param("iissss", $user_id, $car_id, $carName, $paymentType, $price, $date);

        $stmt->execute();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation</title>
    <link rel="shortcut icon" href="../data/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="../data/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../data/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../data/favicon-16x16.png">
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/jquery-3.6.4.min.js"></script>
    <script src="https://kit.fontawesome.com/9e79517447.js" crossorigin="anonymous"></script>
    <style>
        .order-details {
            text-align: center;
            color: var(--var-accent-paragraph);
            margin: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 20px;
            width: fit-content;
            margin: 4em auto;
            animation: shadow-animation 2s linear infinite;
        }
        .order-details h2 {
            margin-top: 0;
        }
        .order-details p {
            margin-bottom: 10px;
        }
        @keyframes shadow-animation {
            0% {
                box-shadow: 0 0 0 0 rgb(98 145 79 / 50%);
            }
            25% {
                box-shadow: 0 0 0 5px rgb(98 145 79 / 50%);
            }
            50% {
                box-shadow: 0 0 0 10px rgb(98 145 79 / 50%);
            }
            75% {
                box-shadow: 0 0 0 4px rgb(98 145 79 / 50%);
            }
            100% {
                box-shadow: 0 0 0 0 rgb(98 145 79 / 50%);
            }
        }
        .estimated-delivery{
            font-size: 20px;
            color: limegreen;
        }
        .success-message{
            color: #ffffffd4;
        }
        strong{
            font-size: 18px !important;
            margin-right: 7px;
        }
        .back-btn{
            float: left;
            background-color: transparent;
            color: var(--var-overlay-text);
            border: 0px solid;
            cursor: pointer;
            margin-right: -1ch;
            border-radius: 10px;
            font-size: 23px;
            transition: transform 0.3s ease;
        }
        .back-btn:hover{
            transform: translateX(-5px);
        }
    </style>
    <script>
        let carName;
        $(document).ready(function(){
            
            carName = $("#carName").text()?.toLowerCase();
            carName = carName?.split(" ");
            carName = carName.slice(2);
            carName = carName.join(" ");

            let sucessMessage = `Thank You for choosing Elite Cars!<br>Your order of ${carName} has been placed, we will contact you shortly!`;
            let orderDetails = $(".order-details")[0];
            let p = document.createElement("p");
            p.innerHTML = sucessMessage;
            p.setAttribute("class", "success-message");

            orderDetails.appendChild(p);


        });

        </script>

</head>
<body>
    <?php 
        require_once '../header.php';
    ?>
    <div class="order-details">
    <button class="back-btn" onclick="window.location.href='../collection/cars.php'"><i class="fa-solid fa-chevron-left"></i></button>
        <h2>Order Details <i class="fa-solid fa-truck-fast" style="color: #76d681;"></i></h2>
        <p id="carName"><strong>Car Name:</strong> <?php echo $carName; ?></p>
        <p><strong>Payment Type:</strong> <?php echo $paymentType; ?></p>
        <p><strong>Total Amount:</strong> $<?php echo $price; ?></p>
        <p><strong>Date:</strong> <?php echo date("Y-m-d"); ?></p>
        <p class="estimated-delivery"><strong>Estimated Delivery:</strong> <?php echo $estimatedDelivery; ?></p>
    </div>
</body>
</html>