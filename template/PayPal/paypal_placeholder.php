<!DOCTYPE html>
    <head>
    <style>
        .full-loader-image {
            text-align:center;
            display: block;
            background: url('https://floralindia.com/list-loader.gif') no-repeat center;
            width: 100%;
            z-index: 9999;
            background-color: #cccccca6;
            height: 100%;
            position: fixed;
            left: 0;
            top: 0;
        }
    </style>
</head>

<body>
    <div class="full-loader-image"></div>
</body>
</html>


<?php
    $_SESSION['isPaypalEmail'] = '1';
    $tx = $params['tx'];
    header("Refresh:10; url=".DOMAIN_URL."/index.php?case=checkout-success&tx=".$params['tx']);
?>