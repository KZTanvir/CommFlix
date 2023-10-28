<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A community for all movie lovers with entertainment.">
    <meta name="keywords" content="#movie #community #review #streaming #service">
    
    <!-- Open Graph meta tags for Facebook and other platforms -->
    <meta property="og:title" content="<?php echo $siteTitle; ?>">
    <meta property="og:description" content="Brief description for social media.">
    <meta property="og:image" content="https://i.ibb.co/6g4qKX3/Screenshot-from-2023-10-25-21-03-20-modified.png">
    <meta property="og:url" content="kamruzzaman.tech">
    <meta property="og:type" content="Streaming Website">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="https://i.ibb.co/6g4qKX3/Screenshot-from-2023-10-25-21-03-20-modified.png">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="css/style.kz.css">
    <link rel="stylesheet" href="<?php if(isset($_SESSION['theme']) && $_SESSION['theme'] === true){ echo "css/dark.kz.css";}?>">

    <title><?php
        if(isset($siteTitle) && $siteTitle != "") {
            echo $siteTitle;
        }
    ?></title>
</head>
