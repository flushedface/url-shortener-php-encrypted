<?php
session_start();

require_once(__DIR__."/../config.php");
require_once(__DIR__."/UrlShortener.php");

$urlShortener = new UrlShortener();

if (isset($_POST['crypt']) && !$errors) {
    $orignalURL = $_POST['crypt'];
    
    $uniqueCode = $urlShortener->validateUrlAndReturnCode($orignalURL);

    if (!$uniqueCode) {
            $_SESSION['error'] = "There was a problem. Invalid URL, perhaps?";
    }
    else {
        $_SESSION['success'] = $urlShortener->generateLinkForShortURL($uniqueCode);
    }
}


header(sprintf("Location: %s/index.php", BASE_URL));
?>