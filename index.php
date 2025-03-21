<?php
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
    <title>Elite Cars</title>
    <link rel="shortcut icon" href="../data/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="../data/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../data/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../data/favicon-16x16.png">
    
    <script src="../js/jquery-3.6.4.min.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/sweetalert2.min.js"></script>
    <script src="js/navigation.js"></script>
    <script src="../js/script.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/sweetalert2.min.css">
    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Russo+One&display=swap" rel="stylesheet">
    <!-- icons -->
    <script src="https://kit.fontawesome.com/9e79517447.js" crossorigin="anonymous"></script>
    <style>
        .with-audio {
            display: flex;
            margin-left: 5rem;
        }

        audio {
            margin-left: 1rem;
        }

        audio::-webkit-media-controls-panel {
            background-color: var(--var-accent-color);
        }

        audio::-webkit-media-controls-play-button {
            background-color: lightgrey;
            border-radius: 50%;
        }

        audio::-webkit-media-controls-play-button:hover {
            background-color: rgba(177, 212, 224, .7);
        }
    </style>

    <script>
        $(document).ready(function () {
            document.getElementById("showCurrencies")?.addEventListener('click', function () {
                window.location.href = "projectRequests.php";
            });
        });
    </script>
</head>

<body>
    <!--dividerheader pt-3-->
    <?php require_once 'header.php'; ?>
    <section class="content">
        <div class="container">
            <div class="row pt-5 mt-5">
                <div class="col-lg-12" style="text-align: center;">
                    <h2 class="title pt-5">About Us</h2>
                    <p class="intro pt-5 pb-2">
                        Established in 1960, we are a trusted name in car sales and rentals. Our roots lie in selling
                        classic cars,
                        and we have expanded our services to include rentals. With our vast selection of cars and
                        competitive prices,
                        we strive to provide the best customer experience. Trust us to fulfill your car rental and sales
                        needs.
                    </p>
                </div>
            </div>
        </div>
        <!--width 27% for car-item-->
        <div id="container" class="col-lg-12 pt-5 mb-3"></div>
        <div class="dividerFooter mt-4"></div>
        <section class="footer col-lg-12 pt-5 mt-2">
            <div class="container" style="">
                <div class="row justify-content-center">
                    <div class="row">
                        <h4 class="contact-us-request">Contact Us</h4>
                        <div class="contact-form">
                            <div class="mb-3 col-lg-6">
                                <label for="UnameInput" class="form-label">Name:</label>
                                <input id="UnameInput" type="text" class="form-control" placeholder="Name">
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label for="EmailInput" class="form-label">Email:</label>
                                <input id="EmailInput" type="email" class="form-control" placeholder="Email">
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label for="RequestInput" class="form-label">Request:</label>
                                <textarea id="RequestInput" class="form-control" rows="4"
                                    placeholder="Enter your request..."></textarea>
                            </div>
                            <button class="btn btn-primary" id="sendBtn" role="button">Send</button>
                        </div>
                    </div>
                    <div class="pt-3 justify-content-center with-audio" style="text-align: center;">
                        <button class="btn btn-primary btn-show-currency" id="showCurrencies">Show Currencies</button>
                        <audio controls autoplay style="" id="musicplayer">
                            <source src="data/introMusic.mp3" type="audio/mp3">
                            Your browser does not support the audio element.
                        </audio>
                    </div>
                    <div class="row pt-5 justify-content-center">
                        <div class="contact-info">
                            <span class="our-email">Email: support@elitecars.com</span>
                            <span class="our-phone">Tel: 09-8881234</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
</body>

</html>