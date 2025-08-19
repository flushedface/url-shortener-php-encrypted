<?php

require_once("UrlShortener.php");

#$urlShortener = new UrlShortener();

if (isset($_GET['s'])) {
    $uniqueCode = $_GET['s'];
    #$orignalUrl = $urlShortener->getOrignalURL($uniqueCode);
    #header("Location: {$orignalUrl}");
    die();
}

header("Location: index.php");
?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="url shortener">
    <title><?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/main.css">
    <script src="https://cdn.jsdelivr.net/npm/jsencrypt@latest/bin/jsencrypt.min.js"></script>
    <script src="script.js"></script>
</head>
<body>
<br>
<center>
    <h1><?php echo SITE_NAME; ?></h1>