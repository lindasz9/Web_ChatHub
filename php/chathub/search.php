<?php
session_start();
require_once "../db.php";

$search_query = $_POST["search_input"] ?? "";

if ($search_query) {
    $stmt = $pdo->prepare("SELECT name FROM chat_rooms WHERE name LIKE ?");
    $stmt->execute(["%$search_query%"]);
    $searched_chatrooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($searched_chatrooms) {
        foreach ($searched_chatrooms as $i) {
            echo "<div class='dropdown-item'>" . $i["name"] . "</div>";
        }
    } 
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["search_input"])){
    $search_input = $_GET["search_input"];
    
    $stmt = $pdo->prepare("SELECT * FROM chat_rooms WHERE name LIKE ? ORDER BY name");
    $stmt->execute(["%$search_input%"]);
    $chat_rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

    require_once "../../html/chathub.html.php";
}