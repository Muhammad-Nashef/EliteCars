<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $servername = "localhost";
    $username = "###";
    $password = "###";
    $dbname = "###";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $user = $_POST['username'];
    $pass = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$user' AND password = '$pass'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $user;
        $_SESSION['email'] = $row['email'];
        $_SESSION['user_id'] = $row['id'];

        if (strtolower($_POST['username']) === 'admin' && strtolower($_POST['password']) === 'admin')
            $_SESSION['is_admin'] = true;
        else
            $_SESSION['is_admin'] = false;

        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1)
            header('Location: /dashboard.php');
        else if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 0)
            header('Location: /user_dashboard.php');

    } else {
        $_SESSION['loggedin'] = false;
        header('Location: /index.php');
    }

    mysqli_close($conn);
}
?>