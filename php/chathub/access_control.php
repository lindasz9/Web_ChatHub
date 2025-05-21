<?php
session_start();
require_once "../db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: " . $_SESSION['url'] . "/html/index.html.php");
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET' && isset($_GET['room_id']) && isset($_GET['action']) && $_GET['action'] === 'check_access') {
    
    // van-e már hozzáférés
    $room_id = $_GET['room_id'];
    $stmt = $pdo->prepare("SELECT * FROM private_access WHERE user_id = ? AND room_id = ?");
    $stmt->execute([$_SESSION["user_id"], $room_id]);
    $has_access = $stmt->fetch() ? true : false;

    header('Content-Type: application/json');
    echo json_encode(["access" => $has_access]);
    exit;
}

if ($method === 'POST' && isset($_POST['room_id']) && isset($_POST['password']) && isset($_POST['action']) && $_POST['action'] === 'check_password') {
    $room_id = $_POST['room_id'];
    $password = $_POST['password'];

    // jelszó ellenőrzése
    $stmt = $pdo->prepare("SELECT private_password FROM chat_rooms WHERE id = ?");
    $stmt->execute([$room_id]);
    $room = $stmt->fetch();

    if (!$room) {
        http_response_code(404);
        echo json_encode(["success" => false, "error" => "Room not found"]);
        exit;
    }

    if ($room["private_password"] !== $password) {
        echo json_encode(["success" => false]);
        exit;
    }

    // hozzáférés elmentése
    $stmt = $pdo->prepare("SELECT * FROM private_access WHERE user_id = ? AND room_id = ?");
    $stmt->execute([$_SESSION["user_id"], $room_id]);

    if (!$stmt->fetch()) {
        $stmt = $pdo->prepare("INSERT INTO private_access (user_id, room_id) VALUES (?, ?)");
        $stmt->execute([$_SESSION["user_id"], $room_id]);
    }

    echo json_encode(["success" => true]);
    exit;
}

header("Location: main.php");
exit;