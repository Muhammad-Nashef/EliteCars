<?php
    error_reporting(E_ERROR | E_WARNING | E_PARSE); 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currency Converter</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="../js/jquery-3.6.4.min.js"></script>
    <link rel="shortcut icon" href="../data/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="../data/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../data/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../data/favicon-16x16.png">
    <style>
    :root {
        --var-white-text: white;
    }

    body {
        background-image: url('data/backgroundPattern.png');
        background-color: dimgray;
    }

    html {
        background-image: url('data/backgroundPattern.png');
        background-color: dimgray;
    }

    button {
        text-align: center;
    }

    .btn {
        background: var(--var-accent-color);
    }

    h1,
    h2,
    h3 {
        text-align: center;
        color: var(--var-white-text);
        font-famiy: 'Russo One', sans-serif;
        font-weight: bold;
    }

    .back-btn {
        float: left;
        background-color: transparent;
        color: white;
        margin-left: 2rem;
        margin-top: 1rem;
        scale: 1.2;
        border: 0px solid;
        cursor: pointer;
        margin-right: -1ch;
        border-radius: 10px;
        font-size: 23px;
        transition: transform 0.3s ease;
    }

    .back-btn:hover {
        transform: translateX(-5px);
    }

    .input-labels {
        color: white;
    }
    #from-title{
        font-weight: bold;
        font-style: italic;
    }
    .container{
        position: relative;
        top: 1rem;
    }
    </style>
    <script src="https://kit.fontawesome.com/9e79517447.js" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            document.getElementById("back_btn").addEventListener("click", function() {
        let answer = window.prompt("Are you sure you want to go back? (type yes or no)");
        if (answer == "yes")
            window.location.href = "user_dashboard.php";
            });
        }); 
    </script>
</head>

<body>
    <?php require_once "header.php"?>
    <button class="back-btn" id="back_btn"><i class="fa-solid fa-chevron-left"></i></button>
    <div class="container">
        <script>
        document.write("<h1>Currency Converter</h1>");
        </script>
        <div class="row mt-5">
            <div class="col-md-6">
                <?php echo "<h2 id='from-title'>1 " . strtoupper("usd") . " :</h2>"?>
                <h3>ILS: 3.59</h3>
                <h3>EUR: 0.89</h3>
                <h3>GBP: 0.77</h3>
                <h3>CAD: 1.32</h3>
            </div>
            <div class="col-md-6">
                <form method="POST" action="#" class="mt-4">
                    <div class="form-row">
                        <div class="col-sm-6 col-md-12 input-labels">
                            <label for="amount">Amount in dollars:</label>
                            <input type="text" name="amount" id="amount" class="form-control"
                                value="<?php echo isset($amount) ? $amount : ''; ?>">
                        </div>
                        <div class="col-sm-6 col-md-12 input-labels">
                            <label for="currency">Choose currency:</label>
                            <select name="currency" id="currency" class="form-control">
                                <option value="ILS"
                                    <?php if (isset($currency) && $currency === 'ILS') echo 'selected'; ?>>ILS
                                </option>
                                <option value="EUR"
                                    <?php if (isset($currency) && $currency === 'EUR') echo 'selected'; ?>>EUR
                                </option>
                                <option value="GBP"
                                    <?php if (isset($currency) && $currency === 'GBP') echo 'selected'; ?>>GBP
                                </option>
                                <option value="CAD"
                                    <?php if (isset($currency) && $currency === 'CAD') echo 'selected'; ?>>CAD
                                </option>
                            </select>
                        </div>
                        <div class="col-12 mt-3 d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">Convert</button>
                        </div>
                    </div>
                </form>

                <?php
                        function convertCurrency($amount, $currency)
                        {
                            $conversionRates = [
                                'ILS' => 3.59,
                                'EUR' => 0.89,
                                'GBP' => 0.77,
                                'CAD' => 1.32
                            ];
                            foreach($conversionRates as $temp => $rate){
                                if($currency == $temp){
                                    $convertedAmount = $amount * $rate;
                                    return $convertedAmount;
                                }

                            }
                            return "Invalid currency choice";
                        }
                
                        
                        $amount = '';
                        $currency = '';
                        $convertedAmount = '';
                
                        
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            
                            $amount = $_POST['amount'];
                            $currency = $_POST['currency'];
                
                            
                            $convertedAmount = convertCurrency($amount, $currency);
                        }

                if (!empty($convertedAmount) && $_SERVER['REQUEST_METHOD'] === 'POST') {
                    echo '<div class="mt-4 input-labels" style="font-size: 20px;font-weight: 600">';
                    echo "Converted amount: " . $convertedAmount . " " . $currency;
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>