<?php
if (session_status() == PHP_SESSION_NONE){
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?= $_SESSION['url'] ?>/css/style.css" rel="stylesheet">
    <title>Sign Up</title>
</head>
<body>
    <a class="back-link" href="<?= $_SESSION['url'] ?>/html/index.html.php">Back</a>
    <div class="container-div">
        <div class="centered-div">
            <h1>Sign up</h1>
            <form action="<?= $_SESSION['url'] ?>/php/signup.php" method="post">
                <label for="username">Username:</label>
                <input autofocus class="margin-bottom" id="username" name="username" required placeholder="Username"><br>
                <label for="password">Password:</label>
                <input class="margin-bottom" type="password" id="password" name="password" required placeholder="Password"><br><br>
                <button type="submit">Sign up</button>
            </form>
            <?php if (!empty($error_message)): ?>
                <p style="color: red;"><?= $error_message ?></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>