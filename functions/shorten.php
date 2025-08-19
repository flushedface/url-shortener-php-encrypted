<?php

session_start();

require_once(__DIR__."/../config.php");
require_once(__DIR__."/UrlShortener.php");

$errors       = false;

$urlShortener = new UrlShortener();

if (isset($_POST['url']) && !$errors) {
    $orignalURL = $_POST['url'];
    
    if ($uniqueCode = $urlShortener->validateUrlAndReturnCode($orignalURL)) {
            $_SESSION['success'] = $urlShortener->generateLinkForShortURL($uniqueCode);
    }

    $_SESSION['error'] = "There was a problem. Invalid URL, perhaps?";
}

header("Location: ../index.php");
exit();
?>