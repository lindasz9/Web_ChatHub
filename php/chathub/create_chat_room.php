<?php
session_start();
require_once "../db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: " . $_SESSION['url'] . "/html/index.html.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["create_chat_room"])) {

    $name = $_POST["name"];
    $privacy = $_POST["privacy"];

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM chat_rooms WHERE name = ?");
    $stmt->execute([$name]);

    if ($stmt->fetchColumn() > 0) {
        $_SESSION["error_message"] = "The name is taken";
    } else {
        $password = $privacy === "private" ? $_POST["password"] : null;
        $stmt = $pdo->prepare("INSERT INTO chat_rooms(name, privacy, private_password) VALUES(?,?,?)");
        $stmt->execute([$name, $privacy, $password]);
    }
}

header("Location: " . $_SESSION['url'] . "/php/chathub/main.php");
exit;