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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="../js/jquery-3.6.4.min.js"></script>
    <script src="https://kit.fontawesome.com/9e79517447.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Russo+One&display=swap" rel="stylesheet">


    <style>
    :root {
        --var-accent-color: #cbab3b;
    }

    /* Style the login icon */
    .login-icon {
        position: absolute;
        top: 0;
        right: 2rem !important;
        background-color: #4CAF50;
        color: white;
        padding: 14px 16px;
        font-size: 16px;
        border: none;
        cursor: pointer;
    }

    /* Style the login dropdown */
    .login-dropdown {}

    @keyframes growOut {
        0% {
            transform: scale(0);
        }

        80% {
            transform: scale(1.1);
        }

        100% {
            transform: scale(1);
        }
    }

    .login-dropdown-content p {
        text-align: center;
        font-size: larger;
        color: var(--var-accent-paragraph);
    }

    .btn-container {
        display: flex;
        flex-direction: row;
        flex-wrap: nowrap;
    }

    /* Style the login dropdown content (hidden by default) */
    .login-dropdown-content {
        animation: growOut 400ms ease-in-out forwards;
        transform-origin: top right;
        display: none;
        z-index: 1;
        position: absolute;
        right: 2.7rem;
        border-radius: 15px;
        border-top-right-radius: 3px;
        top: 1.37rem;
        background-color: #6a6a6a;
        min-width: 15em;
        box-shadow: 0px 1px 16px -4px #cbab3b;
        padding: 25px 25px;
    }

    /* Style the login dropdown links */
    .login-dropdown-content input[type="text"],
    .login-dropdown-content input[type="password"] {
        margin-bottom: 10px;
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        height: 3ch;
    }

    .login-dropdown-content button {
        background-color: #d9d9d9;
        color: #9a8c4f;
        padding: 10px;
        font-size: 17px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        border-radius: 10px;
        width: 50%;
        margin: auto 4rem;
    }

    .login-dropdown.active .login-dropdown-content {
        display: block;
        <?php echo (isset($_SESSION["loggedin"])) ? "" : "height: 15rem;";
        ?>
    }

    label {
        color: var(--var-accent-color);
    }

    .user-btn:hover {
        transform: scale(0.96);
        background: #bebebe;
    }

    .user-btn:active {
        background-color: #b9b7b7;
        box-shadow: 0 5px #666;
        transform: translateY(3px);
    }

    .shopping-cart {
        position: absolute;
        right: 4rem;
        font-size: 20px;
        color: var(--var-accent-hover);
    }

    .username-container,
    .password-container {
        display: flex;
        align-items: center;
        flex-wrap: nowrap;
    }

    .username-container>label,
    .password-container>label {
        margin-right: 1ch;
    }

    .username-container>label>img {
        width: 25px
    }

    @media screen and (max-width: 1366px) {
        .tab-container {
            width: 88%;
        }
    }
</style>

<script>
    $(document).ready(function () {
        const homePage = document.getElementById("home-page");
        const carsPage = document.getElementById("cars-page");
        const aboutPage = document.getElementById("about-page");
        const regulationsPage = document.getElementById("regulations-page");

        homePage?.addEventListener("click", () => {
            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) { ?>
                window.location.href = "../dashboard.php";
            <?php } else { ?>
                window.location.href = "../user_dashboard.php";
            <?php } ?>
        });

        carsPage?.addEventListener("click", () => {
            window.location.href = "../collection/cars.php";
        });

        regulationsPage?.addEventListener("click", () => {
            window.location.href = "../Information/regulations.php";
        });

        aboutPage?.addEventListener("click", () => {
            window.location.href = "../Information/about.php";
        });

        const loginIcon = document.getElementById("login-icon");
        const loginDropdown = document.querySelector(".login-dropdown");

        loginIcon.addEventListener("click", (event) => {
            event.stopPropagation();
            loginDropdown.classList.toggle("active");
        });

        document.addEventListener("click", (event) => {
            if (!loginDropdown.contains(event.target)) {
                loginDropdown.classList.remove("active");
            }
        });

        $('.logout-btn').click(function () {
            window.location = '../account/logout.php';
        });
        $('.login-btn').click(function () {
            if ($("#username").val() == "" || $("#password").val() == "") {
                alert("Please fill in all fields");
                return;
            }
            <?php $_SESSION['prev_page'] = $_SERVER['PHP_SELF']; ?>
            window.location = '../account/login.php';
        });
        $('.signup-btn').click(function () {
            window.location = '../account/createaccount.php';
        });
    });
</script>

</head>

<body>
    <!-- this will be a header for every page -->
    <section class="header">
        <img class="main-logo" src="../data/EliteCarsLogo.png">
        <div class="tab-container">
            <span id="home-page" class="tab">Home</span>
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { ?>
                <span id="cars-page" class="tab">Cars</span>
            <?php } ?>
            <span id="about-page" class="tab">About</span>
            <span id="regulations-page" class="tab">Regulations</span>
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { ?>

            <?php } ?>
            <div class="login-dropdown">
                <i id="login-icon" class="fa-regular fa-user"></i>
                <div class="login-dropdown-content">
                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { ?>
                        <p>Welcome,
                            <?php echo $_SESSION['username']; ?>
                        </p>
                        <div class="btn-container">
                            <button class="user-btn"
                                onclick="window.location.href = '../account/profile.php'">Profile</button>
                            <button class="user-btn logout-btn"
                                onclick="window.location.href = '../account/logout.php'">Logout</button>
                        </div>
                    <?php } else { ?>
                        <form action="../account/login.php" method="POST">
                            <div class="username-container">
                                <label for="username"><img draggable="false" src="../data/icons/username_ico.svg"
                                        alt=""></label>
                                <input type="text" id="username" name="username">
                            </div>
                            <br>
                            <div class="password-container">
                                <label for="password"><img draggable="false" src="../data/icons/password_ico.svg"
                                        alt=""></label>
                                <input type="password" id="password" name="password">
                            </div>
                            <br>
                            <button class="user-btn login-btn" type="submit"
                                style="position: relative;left: -6.6ch;">Login</button>
                        </form>
                        <button class="user-btn signup-btn"
                            style="margin:auto 0 0 4rem;position: relative;left: 4rem;top: -2.8rem;">Sign Up</button>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="dividerHeader"></div>
    </section>


</body>
</html>