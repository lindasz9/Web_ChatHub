<?php
if (session_status() == PHP_SESSION_NONE){
    session_start();
}
$_SESSION["url"] = "http://localhost/00_Projects/chathub";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?= $_SESSION['url'] ?>/css/style.css" rel="stylesheet">
    <title>Index</title>
</head>
<body>
    <div class="container-div">
        <div class="centered-div">
            <h1>Did you already sign up?</h1>
            <button class="margin-bottom" onclick="window.location.href='<?= $_SESSION['url'] ?>/php/signup.php'">Sign up</button><br>
            <button onclick="window.location.href='<?= $_SESSION['url'] ?>/php/login.php'">Log in</button>
        </div>
    </div>
</body>
</html>