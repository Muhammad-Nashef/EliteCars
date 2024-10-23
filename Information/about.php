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
    <title>About</title>
    <link rel="shortcut icon" href="../data/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="../data/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../data/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../data/favicon-16x16.png">
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/jquery-3.6.4.min.js"></script>
    <style>
    .about-container {
        text-align: center;
    }

    .about-us {
        color: var(--var-accent-paragraph);
        padding: 55px 50ch;
        text-align: center;
    }

    .faq {
        color: var(--var-accent-paragraph);
        text-align: left;
        padding: 2ch;
    }

    #faq-head {
        cursor: pointer;
    }
    </style>
    <script>
        $(document).ready(function() {
            const faqHeading = document.getElementById('faq-head');
            faqHeading.addEventListener('click', function() {
            const faqAnswers = document.getElementsByClassName('faq-content');
            for (let i = 0; i < faqAnswers.length; i++) {
                const answer = faqAnswers[i];
                answer.style.display = answer.style.display === 'none' ? 'block' : 'none';
                }
             });
        });
    </script>
</head>

<body>
    <?php require_once "../header.php"?>
    <div class="content">
        <div class="title-container">
            <h2 class="title">About us</h2>
        </div>
        <div id="about-container">
            <p class="about-us">
                </br></br></br>
                </br></br>
                Established in 1960, we are a trusted name in car sales and rentals. Our roots lie in selling classic
                cars,
                and we have expanded our services to include rentals. With our vast selection of cars and competitive
                prices,
                we strive to provide the best customer experience. Trust us to fulfill your car rental and sales needs.
                </br>
            </p>
            <div class="faq">
                <h3 id="faq-head">Frequently asked questions:</h3>
                <p class="faq-content">
                    1.Is Elite cars a free service?
                    </br>
                    &emsp;&emsp;Yes,Elite car website is a free service we do not charge any fees for using our website.
                </p>
                <p class="faq-content">
                    2.What is the cost of renting a car for one month?
                    </br>
                    &emsp;&emsp;The cost of renting a car for one month can vary depending on factors such as the car
                    type, rental location, duration, and seasonal demand.
                </p>
                <p class="faq-content">
                    3.What is the minimum eligible age to rent a car ?
                    </br>
                    &emsp;&emsp;The legal age limit is 24 years and above to rent a car.
                </p>
                <p class="faq-content">
                    4.What are my overheads in renting a car ?
                    </br>
                    &emsp;&emsp;Your overheads may include fuel and parking on your own as per your usage,Delivery and
                    and pick up for rental car might be be charged extra.
                </p>
            </div>
        </div>
    </div>
</body>

</html>