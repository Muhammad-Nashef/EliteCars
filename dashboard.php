<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 0)
    header('Location: /user_dashboard.php');
else if (!isset($_SESSION['is_admin']))
    header('Location: /index.php');

$host = "localhost";
$username = "###";
$password = "###";
$dbname = "###";

$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $usersSql = "SELECT id,username FROM `users`;";
                $carsSql = "SELECT id FROM `cars`;";

                $AvailableUsers=array();
                $AvailableCars=array();
                
                $usersSqlResult = mysqli_query($conn, $usersSql);
                $carsSqlResult =  mysqli_query($conn,$carsSql);

                while ($row = mysqli_fetch_assoc($usersSqlResult)) {
                    $AvailableUsers[] = $row;
                }

                while ($row = mysqli_fetch_assoc($carsSqlResult)) {
                    $AvailableCars[] = $row;
                }

        foreach ($_POST['action'] as $index => $action) {

            if (
                !isset($_POST['order_id'][$index]) && !isset($_POST['total'][$index]) && !isset($_POST['date'][$index]) && isset($_POST['new_user_id'][0]) && isset($_POST['new_user_name'][0]) && isset($_POST['new_car_id'][0]) &&
                isset($_POST['new_ordered_car'][0]) && isset($_POST['new_purchase_type'][0]) &&
                isset($_POST['new_total'][0]) && isset($_POST['new_date'][0])
            ) {

                $newUserId = $_POST['new_user_id'][0];
                $newUserName = $_POST['new_user_name'][0];
                $newCarId = $_POST['new_car_id'][0];
                $newOrderedCar = $_POST['new_ordered_car'][0];
                $newPurchaseType = $_POST['new_purchase_type'][0];
                $newTotal = $_POST['new_total'][0];
                $newDate = $_POST['new_date'][0];
                
                $userFound = false;
                $carFound = false;

                foreach ($AvailableUsers as $userCheck) {
                    if ($userCheck['id'] == $newUserId && $userCheck['username']==$newUserName) {
                        $userFound = true;
                        break;
                    }
                }
                foreach ($AvailableCars as $carCheck) {
                    if ($carCheck['id'] == $newCarId) {
                        $carFound = true;
                        break;
                    }
                }
                

                if($userFound && $carFound){
                    $insertSql = "INSERT INTO orders (user_id, car_id, ordered_car, purchase_type, total, date) 
                                    VALUES ('$newUserId', '$newCarId', '$newOrderedCar', '$newPurchaseType', '$newTotal', '$newDate')";
                $insertResult = mysqli_query($conn, $insertSql);

                if (!$insertResult) {
                    die("Insert failed: " . mysqli_error($conn) . " OrderID: " . $orderId . " total: " . $total . " date:" . $date);
                }

                if (!isset($_POST['inserted'])) {
                    $_POST['inserted'] = true;
                }
            }
            else{
                echo "<script>alert('User ID $newUserId or car ID $newCarId is not exist! .');</script>";
            }
                
        }
            // Update existing row
            if (isset($_POST['order_id'][$index]) && isset($_POST['total'][$index]) && isset($_POST['date'][$index])) {

                //really weird bug, but this works
                $orderId = end($_POST['order_id']);
                $total = $_POST['total'][$index];
                $date = $_POST['date'][$index];

                $updateSql = "UPDATE orders SET total = '$total', date = '$date' WHERE order_id = $orderId";
                $updateResult = mysqli_query($conn, $updateSql);

                if (!$updateResult) {
                    die("Update failed: " . mysqli_error($conn) . "OrderID: " . $orderId . "total: " . $total . " date:" . $date);
                }
            }
        }
    }
}

$sql = "SELECT o.order_id, o.user_id, u.username AS user_name, o.car_id, o.ordered_car, o.purchase_type, o.total, o.date
            FROM orders o
            JOIN users u ON o.user_id = u.id;";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$ordersData = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $ordersData[] = $row;
    }
}

mysqli_close($conn);
$_POST = array();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">

    <link rel="shortcut icon" href="../data/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="../data/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../data/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../data/favicon-16x16.png">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <script src="../js/jquery-3.6.4.min.js"></script>


    <script>
        $(document).ready(function() {
        const saveButtons = document.querySelectorAll('.save-button');
        saveButtons.forEach(button => {
            button.addEventListener('click', () => {
                const form = button.closest('form');
                const row = button.closest('tr');
                const orderId = row.querySelector('td:nth-child(1)').textContent;
                const totalCell = row.querySelector('td:nth-child(7)');
                const total = totalCell.textContent;
                const dateCell = row.querySelector('td:nth-child(8)');
                const date = dateCell.textContent;

                // debugger
                const hiddenOrderId = document.createElement('input');
                hiddenOrderId.type = 'hidden';
                hiddenOrderId.name = 'order_id[]';
                hiddenOrderId.value = parseInt(orderId);
                form.appendChild(hiddenOrderId);

                const hiddenTotal = document.createElement('input');
                hiddenTotal.type = 'hidden';
                hiddenTotal.name = 'total[]';
                hiddenTotal.value = total;
                form.appendChild(hiddenTotal);

                const hiddenDate = document.createElement('input');
                hiddenDate.type = 'hidden';
                hiddenDate.name = 'date[]';
                hiddenDate.value = date;
                form.appendChild(hiddenDate);

                const hiddenInserted = document.createElement('input');
                hiddenInserted.type = 'hidden';
                hiddenInserted.name = 'inserted[]';
                hiddenInserted.value = 1;
                form.appendChild(hiddenInserted);

                form.submit();
            });
        });

        const insertButtons = document.querySelectorAll('.insert-button');
        insertButtons.forEach(button => {
            button.addEventListener('click', () => {
                const form = button.closest('form');
                const newRow = form.querySelector('tr:last-child');
                const newUserId = newRow.querySelector('input[name="new_user_id[]"]').value;
                const newUserName = newRow.querySelector('input[name="new_user_name[]"]').value;
                const newCarId = newRow.querySelector('input[name="new_car_id[]"]').value;
                const newOrderedCar = newRow.querySelector('input[name="new_ordered_car[]"]').value;
                const newPurchaseType = newRow.querySelector('input[name="new_purchase_type[]"]').value;
                const newTotal = newRow.querySelector('input[name="new_total[]"]').value;
                const newDate = newRow.querySelector('input[name="new_date[]"]').value;

                const hiddenUserId = document.createElement('input');
                hiddenUserId.type = 'hidden';
                hiddenUserId.name = 'new_user_id[]';
                hiddenUserId.value = newUserId;
                form.appendChild(hiddenUserId);

                const hiddenUserName = document.createElement('input');
                hiddenUserName.type = 'hidden';
                hiddenUserName.name = 'new_user_name[]';
                hiddenUserName.value = newUserName;
                form.appendChild(hiddenUserName);

                const hiddenCarId = document.createElement('input');
                hiddenCarId.type = 'hidden';
                hiddenCarId.name = 'new_car_id[]';
                hiddenCarId.value = newCarId;
                form.appendChild(hiddenCarId);

                const hiddenOrderedCar = document.createElement('input');
                hiddenOrderedCar.type = 'hidden';
                hiddenOrderedCar.name = 'new_ordered_car[]';
                hiddenOrderedCar.value = newOrderedCar;
                form.appendChild(hiddenOrderedCar);

                const hiddenPurchaseType = document.createElement('input');
                hiddenPurchaseType.type = 'hidden';
                hiddenPurchaseType.name = 'new_purchase_type[]';
                hiddenPurchaseType.value = newPurchaseType;
                form.appendChild(hiddenPurchaseType);

                const hiddenTotal = document.createElement('input');
                hiddenTotal.type = 'hidden';
                hiddenTotal.name = 'new_total[]';
                hiddenTotal.value = newTotal;
                form.appendChild(hiddenTotal);

                const hiddenDate = document.createElement('input');
                hiddenDate.type = 'hidden';
                hiddenDate.name = 'new_date[]';
                hiddenDate.value = newDate;
                form.appendChild(hiddenDate);

                form.submit();
            });
        });
    });
    </script>
    <style>
    :root {
        --var-accent-color: #cbab3b;
        --var-accent-hover: #9e8631;
        --var-accent-paragraph: #c9b467;
        --var-overlay-text: #68a7ff;
        --var-profile-text: #c4d75c;
    }

    body {
        background-color: revert;
    }

    .table {
        --bs-table-bg: inherit;
        --bs-table-color: #a5e41a;
        --bs-table-border-color: #49894c36;
    }

    ul {
        color: cadetblue;
        text-align: center;
        list-style: none;
    }

    li {
        font-size: large;
        font-weight: 700;
    }

    h2 {
        text-align: center;
        color: var(--var-accent-color);
    }

    hr {
        color: red;
        opacity: 0.50;
    }

    .pulse-dot {
        position: relative;
        display: inline-block;
        width: 15px;
        margin-left: 10px;
        bottom: 2px;
        height: 15px;
        background-color: red;
        border-radius: 50%;
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.2);
        }

        100% {
            transform: scale(1);
        }
    }

    input {
        padding: 3px;
        height: 3ch;
        background: inherit;
        color: inherit;
    }

    td.hidden-input {
        cursor: default;
        user-select: none;
    }
</style>
</head>

<body>
    <?php require_once 'header.php'; ?>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="container" style="max-width: -webkit-fill-available;">
            <div class="row">
                <div class="col-lg-6" style="margin-left: 3rem;">
                    <h2>Users Orders</h2>
                    <form method="post" action="#">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>User ID</th>
                                    <th>User Name</th>
                                    <th>Car ID</th>
                                    <th>Ordered Car</th>
                                    <th>Purchase Type</th>
                                    <th>Total</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $host = "localhost";
                                $username = "id20708849_elite_carsdb";
                                $password = "7R+nXfGpK-dnR#<R";
                                $dbname = "id20708849_elite";


                                $conn = mysqli_connect($host, $username, $password, $dbname);

                                if (!$conn) {
                                    die("Connection failed: " . mysqli_connect_error());
                                }

                                $sql = "SELECT o.order_id, o.user_id, u.username AS user_name, o.car_id, o.ordered_car, o.purchase_type, o.total, o.date
                                        FROM orders o
                                        JOIN users u ON o.user_id = u.id;";
                                $result = mysqli_query($conn, $sql);

                                if (!$result) {
                                    die("Query failed: " . mysqli_error($conn));
                                }

                                $ordersData = [];
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $ordersData[] = $row;
                                    }
                                }

                                mysqli_close($conn); ?>



                                <?php foreach ($ordersData as $order): ?>
                                    <tr>
                                        <td>
                                            <input type="hidden" name="order_id[]"
                                                value="<?php echo $order['order_id']; ?>">
                                            <input type="hidden" name="action[]" value="update"
                                                title="Will be generated automatically">
                                            <?php echo $order['order_id']; ?>
                                        </td>
                                        <td>
                                            <?php echo $order['user_id']; ?>
                                        </td>
                                        <td>
                                            <?php echo $order['user_name']; ?>
                                        </td>
                                        <td>
                                            <?php echo $order['car_id']; ?>
                                        </td>
                                        <td>
                                            <?php echo $order['ordered_car']; ?>
                                        </td>
                                        <td>
                                            <?php echo $order['purchase_type']; ?>
                                        </td>
                                        <td contenteditable="true">
                                            <?php echo $order['total']; ?>
                                        </td>
                                        <td contenteditable="true">
                                            <?php echo $order['date']; ?>
                                        </td>
                                        <td style="position: relative;z-index: 999;text-align:center;">
                                            <button class="btn btn-success save-button">Save</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td class="hidden-input">
                                        <input type="hidden" name="action[]" value="insert"
                                            title="Will be generated automatically">
                                        Auto
                                    </td>
                                    <td><input type="text" name="new_user_id[]"></td>
                                    <td><input type="text" name="new_user_name[]"></td>
                                    <td><input type="text" name="new_car_id[]"></td>
                                    <td><input type="text" name="new_ordered_car[]"></td>
                                    <td><input type="text" name="new_purchase_type[]"></td>
                                    <td><input type="text" name="new_total[]"></td>
                                    <td><input type="text" name="new_date[]"></td>
                                    <td style="position: relative; z-index: 999;">
                                        <button class="btn btn-primary insert-button">Insert</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="col-lg-1 border-end" style="margin-left: -4rem;"></div>
                <div class="col-lg-4" style="margin-left: 5rem;">
                    <h2>Today's Returned Cars
                        <span class="pulse-dot" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Live Updates (refresh the page)"></span>
                    </h2>
                    <table class="table">
                        <thead>
                        </thead>
                        <tbody>
                            <?php
                            $data = [
                                ['Order ID' => 3, 'User ID' => 1, 'Car ID' => 101, 'Ordered Car' => 'BMW M4', 'Purchase Type' => 'Rent', 'Total' => '$50,000', 'Date' => '2023-07-14'],
                                ['Order ID' => 4, 'User ID' => 2, 'Car ID' => 102, 'Ordered Car' => 'Mercedes-Benz S-Class', 'Purchase Type' => 'Rent', 'Total' => '$40,000', 'Date' => '2023-07-15'],
                                ['Order ID' => 5, 'User ID' => 3, 'Car ID' => 103, 'Ordered Car' => 'Audi R8', 'Purchase Type' => 'Rent', 'Total' => '$60,000', 'Date' => '2023-07-16'],
                                ['Order ID' => 6, 'User ID' => 4, 'Car ID' => 104, 'Ordered Car' => 'Dodge Challenger', 'Purchase Type' => 'Rent', 'Total' => '$70,000', 'Date' => '2023-07-17'],
                                ['Order ID' => 5, 'User ID' => 1, 'Car ID' => 105, 'Ordered Car' => 'Lamborghini Huracan', 'Purchase Type' => 'Rent', 'Total' => '$120,000', 'Date' => '2023-07-18'],
                                ['Order ID' => 6, 'User ID' => 2, 'Car ID' => 106, 'Ordered Car' => 'Porsche 911', 'Purchase Type' => 'Rent', 'Total' => '$100,000', 'Date' => '2023-07-19'],
                                ['Order ID' => 7, 'User ID' => 3, 'Car ID' => 107, 'Ordered Car' => 'Ferrari 458 Italia', 'Purchase Type' => 'Rent', 'Total' => '$150,000', 'Date' => '2023-07-20'],
                                ['Order ID' => 8, 'User ID' => 4, 'Car ID' => 108, 'Ordered Car' => 'Aston Martin DB11', 'Purchase Type' => 'Rent', 'Total' => '$180,000', 'Date' => '2023-07-21'],
                                ['Order ID' => 9, 'User ID' => 2, 'Car ID' => 109, 'Ordered Car' => 'Jaguar F-Type', 'Purchase Type' => 'Rent', 'Total' => '$80,000', 'Date' => '2023-07-22'],
                                ['Order ID' => 10, 'User ID' => 3, 'Car ID' => 110, 'Ordered Car' => 'Chevrolet Camaro', 'Purchase Type' => 'Rent', 'Total' => '$50,000', 'Date' => '2023-07-23'],
                            ];

                            $randomKeys = array_rand($data, 2);
                            $randomEntries = [$data[$randomKeys[0]], $data[$randomKeys[1]]];

                            ?>
                            <?php foreach ($randomEntries as $entry): ?>
                                <ul>
                                    <li>Order ID:
                                        <?php echo $entry['Order ID']; ?>
                                    </li>
                                    <li>User ID:
                                        <?php echo $entry['User ID']; ?>
                                    </li>
                                    <li>Car ID:
                                        <?php echo $entry['Car ID']; ?>
                                    </li>
                                    <li>Ordered Car:
                                        <?php echo $entry['Ordered Car']; ?>
                                    </li>
                                    <li>Purchase Type:
                                        <?php echo $entry['Purchase Type']; ?>
                                    </li>
                                    <li>Total:
                                        <?php echo $entry['Total']; ?>
                                    </li>
                                    <li>Date:
                                        <?php echo $entry['Date']; ?>
                                    </li>
                                </ul>
                                <hr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    

    <script src="../js/bootstrap.bundle.min.js"></script>
</body>

</html>