<?php
if (session_status() == PHP_SESSION_NONE){
    session_start();
}
if (!isset($_SESSION["user_id"])){
    header("Location: " . $_SESSION['url'] . "/html/index.html.php");
    exit;
}
if (!isset($chat_rooms)){
    header("Location: " . $_SESSION['url'] . "/php/chathub/main.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?= $_SESSION['url'] ?>/css/style.css" rel="stylesheet">
    <title>ChatHub</title>
</head>
<body>
    <div id="flex-row-div">
        <h1 id="flex-row-left">ChatHub</h1>
        <form id="flex-row-center" action="<?= $_SESSION['url'] ?>/php/chathub/search.php" method="get">
            <div id="search_wrapper">
                <input id="flex-row-input" autocomplete="off" name="search_input" placeholder="Search">
                <div id="search_results" class="dropdown-results"></div>
            </div>    
            <button type="submit">Search</button>
        </form>
        <form id="flex-row-right" action="<?= $_SESSION['url'] ?>/php/chathub/main.php" method="post">
            <button id="logout_button" type="submit" name="logout">Logout</button>
        </form>
    </div>
    <div class="card-div margin-bottom">
        <h2 class="no-margin-top">Creating chat room</h2>
        <form action="<?= $_SESSION['url'] ?>/php/chathub/create_chat_room.php" method="post">
            <label for="name">Name:</label>
            <input class="margin-bottom" id="name" autocomplete="off" type="text" name="name" required placeholder="Name"><br>
            <input class="margin-bottom" type="radio" name="privacy" value="public" checked>Public<br>
            <input class="margin-bottom" type="radio" name="privacy" value="private">Private<br>
            <label class="display_none" for="password">Password:</label>
            <input class="margin-bottom display_none" id="password" type="text" name="password" placeholder="Password"><br>
            <button type="submint" name="create_chat_room">Create chat room</button>
            <?php if (!empty($error_message)): ?>
                <p style="color: red;"><?= $error_message ?></p>
            <?php endif; ?>
        </form>
    </div>
    <div id="two-column-div">
        <?php foreach ($chat_rooms as $i): ?>
            <div class="chat-room-divs" 
                data-room_id="<?= $i["id"] ?>" 
                data-privacy="<?= $i["privacy"] ?>">
                <h3><?= $i["name"] ?></h3>
                <p class="privacy-p"><?= $i["privacy"] ?></p>
            </div>
        <?php endforeach ?>
    </div>
    <script>
        const baseURL = "<?= $_SESSION['url'] ?>"
        const userId = "<?= $_SESSION['user_id'] ?>"
    </script>
    <script src="<?= $_SESSION['url'] ?>/js/chathub.js"></script>
</body>
</html>