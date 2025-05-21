<?php
session_start();
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST"){

    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if (!$user){
        
        $error_message = "Incorrect username";

    } elseif ($password !== $user["user_password"]){

        $error_message = "Incorrect password";

    } else {

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?;");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        $_SESSION["user_id"] = $user["id"];
        header("Location: " . $_SESSION['url'] . "/php/chathub/main.php");
        exit;

    }
}
require_once "../html/login.html.php";