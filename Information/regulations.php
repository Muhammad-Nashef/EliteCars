<?php
    error_reporting(E_ERROR | E_WARNING | E_PARSE); 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = $_POST["email"];
        $termsContent = file_get_contents("termsConditions.txt");
        $to = $email;
        $subject = "Elite cars - Terms & Conditions ";
        $message = $termsContent;
        mail($to, $subject, $message);
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../data/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="../data/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../data/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../data/favicon-16x16.png">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/sweetalert2.min.css">
    <script src="../js/jquery-3.6.4.min.js"></script>
    <title>Regulations</title>
    <style>
    .title {
        margin: 1rem -5rem;
    }

    #regulations-container {
        color: var(--var-accent-paragraph);
        padding: 8ch 30ch;
        word-spacing: 3px;
    }

    #submit {
        color: #ffffff;
        border: none;
        border-radius: 3px;
        padding: 10px 20px;
        cursor: pointer;
        margin-top: 10px;

        position: relative;
        backface-visibility: hidden;
        background: var(--var-accent-color);
        border: 0;
        border-radius: .375rem;
        box-sizing: border-box;
        color: #fff;
        cursor: pointer;
        display: inline-block;
        font-family: Circular, Helvetica, sans-serif;
        font-size: 1.125rem;
        font-weight: 700;
        letter-spacing: -.01em;
        line-height: 1.3;
        padding: 1rem 1.25rem;
        position: relative;
        text-align: left;
        text-decoration: none;
        transform: translateZ(0) scale(1);
        transition: transform .2s;
        user-select: none;
        -webkit-user-select: none;
        touch-action: manipulation;
    }

    #submit:disabled {
        color: #787878;
        cursor: auto;
    }

    #submit:not(:disabled):hover {
        background-color: var(--var-accent-hover);
        transform: scale(1.05);
    }

    #submit:not(:disabled):hover:active {
        transform: scale(1.05) translateY(.125rem);
    }

    #submit:focus {
        outline: 0 solid transparent;
    }

    #submit:focus:before {
        border-width: .125rem;
        content: "";
        left: calc(-1*.375rem);
        pointer-events: none;
        position: absolute;
        top: calc(-1*.375rem);
        transition: border-radius;
        user-select: none;
    }

    #submit:focus:not(:focus-visible) {
        outline: 0 solid transparent;
    }

    #submit:not(:disabled):active {
        transform: translateY(.125rem);
    }

    #email {
        width: 15%;
        display: initial;
    }

    #sendEmail-content {
        text-align: center;
    }

    input[type="checkbox"] {
        width: unset;
    }

    label[for="agree"] {
        color: var(--var-accent-color);
        margin-left: 2ch;
    }
    </style>

<script>
        $(document).ready(function() {
            fetch("termsConditions.txt")
                .then(response => response.text())
                .then(content => {
                    content = content.replace(/\n/g, "<br>");
                    document.getElementById("regulations-container").innerHTML = content;
                })
                .catch(error => {
                    console.error("Error fetching file:", error);
                });

            
        });
    </script>
</head>

<body>
    <?php require_once "../header.php"?>
    <div class="content">
        <div class="title-container">
            <h2 class="title">Terms & Conditions</h2>
        </div>
        <form method="POST" action="#" id="termsForm" novalidate>
            <div id="regulations-container"></div>
            <div id="sendEmail-content">
                <div>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div
                    style="display: inline-flex;display: inline-flex;align-items: baseline;margin-left: 2ch;margin-block: 1ch;">
                    <input type="checkbox" id="agree" name="agree" required>
                    <label for="agree">I agree to the Terms & Conditions</label>
                </div>
                <div>
                    <button type="submit" id="submit">Send</button>
                </div>
            </div>
        </form>
        <script>
        document.getElementById("termsForm").addEventListener("submit", function(event) {
            var emailInput = document.getElementById("email");
            if (!emailInput.value) {
                event.preventDefault();
                swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please enter an email address!',
                });
            } else if (!document.getElementById("agree").checked) {
                event.preventDefault();
                swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'You must agree to the Terms & Conditions!',
                });
            }
        });
        </script>
        <script src="../js/sweetalert2.min.js"></script>
    </div>
</body>

</html>