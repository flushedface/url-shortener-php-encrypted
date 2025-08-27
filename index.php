<?php
    session_start();
    require_once("./config.php");
    $_SESSION['error']
?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="url shortener">
    <meta id="session" conttet=<php echo session_id();?>>
    <title><?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/main.css">
    
    <script src="redirect.js"></script>
    <script type="module" src="script.js"></script>
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
    <p><?php 
        if($_SESSION['error']) {  echo $_SESSION['error']; } echo $_SESSION['success'];?>
    <p>
    <?php 
        $_SESSION['error'] = null;
        $_SESSION['success'] = null;
    ?>

    <div class="section group">
        <div class="col span_3_of_3">
                <input type="url" id="input" class="input" placeholder="Enter a URL or Text here" oninput="window.c.encrypt( document.getElementById('input'), document.getElementById('cinput'),false)">
        </div>
    </div>

    <form method="POST" action="functions/shorten.php">
        <div class="section group">
            <div class="col span_3_of_3">
                <input type="submit" value="Short" class="submit">
            </div>
        </div>
        <div class="section group">
            <div class="col span_3_of_3 ">
                
            </div>
        </div>
        <input style="pointer-events: none;" id="cinput" name="crypt" class="input">
    </form>

    <p>All Content is Encrypted</p>
</center>
</body>
</html>
