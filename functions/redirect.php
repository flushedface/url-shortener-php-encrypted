<?php

require_once("UrlShortener.php");

$urlShortener = new UrlShortener();

if (isset($_GET['s'])) {
    $uniqueCode = $_GET['s'];
    echo $urlShortener->getOrignalURL($uniqueCode);
}
?>
