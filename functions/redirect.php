<?php



$urlShortener = new UrlShortener();

if (isset($_GET['s'])) {
    $uniqueCode = $_GET['r'];
    $urlShortener->getOrignalURL($uniqueCode);
}
?>


<head>

</head>