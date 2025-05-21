<?php
if (session_status() == PHP_SESSION_NONE){
    session_start();
}
if (!isset($_SESSION["user_id"])){
    header("Location: " . $_SESSION['url'] . "/html/index.html.php");
    exit;
}
if (!isset($chatroom)){
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
    <title>Chat Room</title>
</head>
<body>
    <button onclick="window.location.href='<?= $_SESSION['url'] ?>/php/chathub/main.php'">Back</button>
    <h1><?= $chatroom["name"] ?></h1>
    <div id="message-container">
        <?php foreach ($sent_messages as $i): ?>
            <?php 
                $is_current_user = $_SESSION["user_id"] == $i["id"]; 

                $date = new DateTime($i["timestamp"]);
                $time = $date->format("H:i")
            ?>
            <div class="<?= $is_current_user ? 'message-right' : 'message-left' ?>">
                <p class="<?= $is_current_user ? 'p-right' : 'p-left' ?> p-username"><?= $is_current_user ? 'You' : $i["username"] ?></p>
                <div class="message-box <?= $is_current_user ? 'message-box-right' : 'message-box-left' ?>">
                    <p><?= $i["message"] ?> <br><span class="time-span"><?= $time ?></span></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div id="message-textarea-container">
        <form id="message-form">
            <input id="message-form-input" type="hidden" name="room_id" value="<?= $chatroom["id"] ?>">
            <textarea id="message-textarea" name="message" placeholder="Type..." required></textarea>
            <button id="send-message-button" type="submit" name="send_message">Send</button>
        </form>
    </div>
    <script>
        <?php
            $stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?;");
            $stmt->execute([$_SESSION["user_id"]]);
            $user = $stmt->fetch();
        ?>
        const baseURL = "<?= $_SESSION['url'] ?>"
        const username = "<?= $user['username'] ?>"
    </script>
    <script src="<?= $_SESSION['url'] ?>/js/chatroom.js"></script>
</body>
</html>