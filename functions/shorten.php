<?php
session_start();

require_once(__DIR__."/../config.php");
require_once(__DIR__."/UrlShortener.php");

$urlShortener = new UrlShortener();

if (isset($_POST['crypt']) && !$errors) {
    $uniqueCode = $urlShortener->validateUrlAndReturnCode($_POST['crypt']); # Store the Encrypted Content

    if (!$uniqueCode) {
            $_SESSION['error'] = "There was a problem";
    }
    else {
        $_SESSION['success'] = $urlShortener->generateLinkForShortURL($uniqueCode);
    }
}


header(sprintf("Location: %s/index.php", BASE_URL));
?>