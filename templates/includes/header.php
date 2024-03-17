<?php

use App\Config\ConfigApp;

$header = new ConfigApp();
?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= $header->app_description ?>">
    <meta name="keywords" content="<?= $header->app_keywords ?>">
    <meta name="author" content="<?= $header->app_author ?>">
    <title><?= $header->app_name ?></title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <style>
        .sidebar-height {
            height: calc(100vh - 70px);
        }

        @media screen and (max-width: 767.67px) {
            .sidebar-height {
                height: 100%;
            }
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            10%,
            30%,
            50%,
            70%,
            90% {
                transform: translateX(-5px);
            }

            20%,
            40%,
            60%,
            80% {
                transform: translateX(5px);
            }
        }

        .shake {
            animation: shake 0.5s;
        }
    </style>
</head>

<body>