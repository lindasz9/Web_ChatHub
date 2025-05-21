<?php
session_start();
require_once "db.php";

if (!isset($_SESSION["user_id"])){
    header("Location: " . $_SESSION['url'] . "/html/index.html.php");
    exit;
}

$room_id = $_POST["room_id"] ?? $_GET["room_id"] ?? null;

if (!$room_id) {
    header("Location: " . $_SESSION['url'] . "/php/chathub/main.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM chat_rooms WHERE id = ?;");
$stmt->execute([$room_id]);
$chatroom = $stmt->fetch();

if (!$chatroom){
    header("Location: " . $_SESSION['url'] . "/php/chathub/main.php");
    exit;   
}

if ($chatroom["privacy"] === "private"){
    $stmt = $pdo->prepare("SELECT * FROM private_access WHERE user_id = ? AND room_id = ?;");
    $stmt->execute([$_SESSION["user_id"], $room_id]);
    $has_access = $stmt->fetch();

    if (!$has_access) {
        header("Location: " . $_SESSION['url'] . "/php/chathub/main.php");
        exit;
    }
}

//JS üzenetküldés után: PHP-val üzenet adatbázisban rögzítése
if (isset($_POST["send_message"])){
    $message = $_POST["message"];

    $stmt = $pdo->prepare("INSERT INTO messages(room_id, user_id, message) VALUES(?, ?, ?);");
    $stmt->execute([$room_id, $_SESSION["user_id"], $message]);

    header("Content-Type: application/json");
    echo json_encode(["timestamp" => date("H:i")]);
    exit;
}

//eddigi üzenetek kiírására szükséges lista
$stmt = $pdo->prepare("SELECT messages.message, messages.timestamp, users.username, users.id 
                        FROM messages
                        JOIN users ON messages.user_id = users.id 
                        WHERE messages.room_id = ? 
                        ORDER BY messages.timestamp;");
$stmt->execute([$room_id]);
$sent_messages = $stmt->fetchAll();

require_once "../html/chatroom.html.php";