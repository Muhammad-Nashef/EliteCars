<?php 
    session_start();
    error_reporting(0);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $paymentType = $_POST["payment_type"];
        $carName = $_POST["car_name"];
        $car_id = $_POST['car_id'];
        $user_id = $_SESSION['user_id'];
        
        if ($paymentType == "rent") {
            $price = $_POST["rent_price"];
        } elseif ($paymentType == "buy") {
            $price = $_POST["buy_price"];
        }

        if(!isset($_SESSION['sub_title_string'])){
            $sub_title_string =  ucfirst($paymentType) . "ing " . "this " . $carName . " for " . $price;
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
	<title>Payment Page</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/style.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/sweetalert2.min.css">
    <link rel="shortcut icon" href="../data/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="../data/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../data/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../data/favicon-16x16.png">

    <script src="../js/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/card/2.5.0/card.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>

<style>
    html,body{
        background-color: #3c3c3c !important;
        font-family: 'Russo One' !important;
        --var-accent-color: #cbab3b;
    }
    h2{
        text-align: center;
    }
    label, p, h2{
        color: var(--var-accent-color);
    }
    .header{
        height: 3.5rem !important;
    }
    .form-group{
        width: fit-content;
    }
    .card-wrapper{
        position: relative;
        left: 20rem;
        top: 7rem;
    }
    .fields-wrapper{
        float: left;
        padding: 2rem 30px;
    }
    .container-fluid{
        margin-top: 2rem !important;
    }
    .sub-title-string{
        color: #97c8ff;
    }
</style>

<script>
    $(document).ready(function(){
        const cardNumberInput = document.querySelector('#credit-card-number');
        const cardExpiryInput = document.querySelector('#expiry_date');
        const cardCvcInput = document.querySelector('#cvv');
        const cardNameInput = document.querySelector('#name');
    
        const card = new Card({
        form: 'form',
        container: '.card-wrapper',
        formSelectors: {
            numberInput: '#credit-card-number',
            expiryInput: '#expiry_date',
            cvcInput: '#cvv',
            nameInput: '#name'
        },
        formatting: true,
        placeholders: {
            number: '**** **** **** ****',
            name: 'Full Name',
            expiry: 'MM/YY',
            cvc: '***',
        },
        });
    
        cardNumberInput.addEventListener('input', () => {
            let value = cardNumberInput.value.replace(/\D/g, '');
            value = value.replace(/(\d{4})/g, '$1 ').trim();
            //card.update({number: value});
            card["number"] = value;
        });
    
        cardExpiryInput.addEventListener('input', () => {
            const value = cardExpiryInput.value.replace(/\D/g, '').slice(0, 4);
            const month = value.slice(0, 2);
            const year = value.slice(2);
            //card.update({expiry: `${month}/${year}`});
            card["expiry"] = `${month}/${year}`;
        });
    
        cardCvcInput.addEventListener('input', () => {
            const value = cardCvcInput.value.replace(/\D/g, '');
            //card.update({cvc: value});
            card["cvv"] = value;
        });
    
        cardNameInput.addEventListener('input', () => {
            //card.update({name: cardNameInput.value});
            card["name"] = cardNameInput;
        });
    });    
</script>
</head>
<body>
<?php require_once '../header.php'; ?>
	<div class="container-fluid">
		<h2>Payment</h2>
        
        <h4 class="sub-title-string"><?php echo empty($_SESSION['sub_title_string']) ? '' : $_SESSION['sub_title_string'];?></h4>
		<p>Please enter your payment details below:</p>

		<form action="processPayment.php" method="post">
		    <div class="fields-wrapper">
    			<div class="form-group">
    				<label for="name">Name on Card:</label>
    				<input type="text" class="form-control" id="name" name="name" required>
    			</div>
    			<div class="form-group">
    				<label for="credit-card-number">Card Number:</label>
    				<input type="text" class="form-control" id="credit-card-number" name="card_number" required>
    			</div>
    			<div class="form-group">
    				<label for="expiry_date">Expiry Date:</label>
    				<input type="text" class="form-control" id="expiry_date" name="expiry_date" required>
    				<label for="cvv" style="position: relative;top: 4px;">CVV:</label>
    				<input type="text" class="form-control" id="cvv" name="cvv" required>
    			</div>
    			<button type="submit" class="btn btn-primary" style="position: relative;top: 20px;left: 1rem;">Submit Payment</button>
                <input type="hidden" name="car_name" value="<?php echo $carName; ?>">
                <input type="hidden" name="car_id" value="<?php echo $car_id; ?>">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <input type="hidden" name="payment_type" value="<?php echo $paymentType; ?>">
                <input type="hidden" name="price" value="<?php echo $price; ?>">
			</div>
			<div class="form-group card-wrapper"></div>
		</form>
	</div>

<?php if (isset($_SESSION['error'])) {
    echo "<script>";
    echo "setTimeout(function() {";
        echo "Swal.fire(";
            echo "'Payment Failed!',";
            echo "'" . $_SESSION['error'] . "',";
            echo "'error'";
    echo ")}, 500);";
    echo "</script>";

    unset($_SESSION['error']);
}?>

<script src="../js/sweetalert2.min.js"></script>
</body>
</html>