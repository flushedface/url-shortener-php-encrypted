<?php
    session_start();
    require_once("./config.php");
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
    <section>
  <div class="wave">
    <span></span>
    <span></span>
    <span></span>
  </div>
</section>
<center>
    <h1><?php echo SITE_NAME; ?></h1>

    <?php
    if (isset($_SESSION['success'])) {
        echo "<p class='success'>" . $_SESSION['success'] . "</p>";
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['error'])) {
        echo "<p class='alert'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']);
    }
    ?>


    <div class="section group">
        <div class="col span_3_of_3">
                <input type="url" id="input" class="input" placeholder="Enter a URL or Text here" oninput="crypt_url()">
        </div>
    </div>

    <form method="POST" action="functions/shorten.php">
        <div class="section group">
            <div class="col span_3_of_3">
                <input type="submit" value="Short" class="submit">
            </div>
        </div>
        <input disabled id="cinput" name="url" class="input">
    </form>

    <p>All Content is Encrypted</p>
</center>
</body>
</html>
