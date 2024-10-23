<?php 
    error_reporting(E_ERROR | E_WARNING | E_PARSE); 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if($_SESSION['is_admin'] == 0)
        header('Location: /user_dashboard.php');
    else if(!isset($_SESSION['is_admin']))
        header('Location: /index.php');    
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Car</title>
    <link rel="shortcut icon" href="../data/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="../data/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../data/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../data/favicon-16x16.png">
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/jquery-3.6.4.min.js"></script>
    <style>
    .mb-3 {
        width: max-content;
    }

    .title-container {
        text-align: center;
        color: var(--var-accent-color);
    }

    .container {
        position: absolute;
        top: 45%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    #carImage {
        color: burlywood;
    }

    input {
        padding: 10px 7px 8px 10px;
        width: 160%;
    }

    .button-div {
        margin-left: 30%;
    }
    </style>

    <script>
        $(document).ready(function() {
            document.getElementById('carImage').setAttribute('title', 'file name format: carName_year_1/2/3.webp');
        });
    </script>
</head>

<body>
    <?php require_once "../header.php";?>

    <div class="container">
        <div class="row">
            <div class="title-container col-12">
                <h2>Add Car</h2>
            </div>
            <div class="row">
                <div class="col-12">
                    <form action="../collection/processCarAddition.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="carName" class="form-label">Car Name</label>
                            <input type="text" class="form-control" id="carName" name="carName" required>
                        </div>
                        <div class="mb-3">
                            <label for="carRentPrice" class="form-label">Rent Price</label>
                            <input type="number" class="form-control" id="carRentPrice" name="carRentPrice" required>
                        </div>
                        <div class="mb-3">
                            <label for="carBuyPrice" class="form-label">Buy Price</label>
                            <input type="number" class="form-control" id="carBuyPrice" name="carBuyPrice" required>
                        </div>
                        <div class="mb-3">
                            <label for="carImage" class="form-label">Car Image</label>
                            <input type="file" class="form-control" id="carImage" name="carImages[]" accept="image/*"
                                multiple="multiple" title="">
                        </div>

                        <div class="mb-3">
                            <label for="carYear" class="form-label">Year</label>
                            <input type="number" class="form-control" id="carYear" name="carYear" required>
                        </div>
                        <div class="mb-3">
                            <label for="carPower" class="form-label">Power</label>
                            <input type="number" class="form-control" id="carPower" name="carPower" required>
                        </div>
                        <div class="mb-3">
                            <label for="carMileage" class="form-label">Mileage</label>
                            <input type="number" class="form-control" id="carMileage" name="carMileage" required>
                        </div>
                        <div class="mb-3">
                            <label for="car060" class="form-label">0-60</label>
                            <input type="number" class="form-control" id="car060" name="car060" required>
                        </div>
                        <div class="mb-3 button-div">
                            <button type="submit" class="btn btn-primary" id="sendBtn">Add Car</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

</body>

</html>