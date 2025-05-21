<?php
session_start();
require_once "../db.php";

if (!isset($_SESSION["user_id"])){
    header("Location: " . $_SESSION['url'] . "/html/index.html.php");
    exit;
}

if (isset($_POST["logout"])){
    session_destroy();
    header("Location: " . $_SESSION['url'] . "/html/index.html.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM chat_rooms ORDER BY name");
$chat_rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once "../../html/chathub.html.php";