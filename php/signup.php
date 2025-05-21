<?php
session_start();
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST"){

    $username = $_POST["username"];
    $password = $_POST["password"];
        
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);

    if ($stmt->fetchColumn() > 0){
        $error_message = "The username is taken";

    } else {

        $stmt = $pdo->prepare("INSERT INTO users(username, user_password) VALUES (?, ?)");
        $stmt->execute([$username, $password]);
    
        header("Location: " . $_SESSION['url'] . "/php/login.php");
        exit;
    }

}
require_once "../html/signup.html.php";