<?php
session_start();
require 'db.php';

if (isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    $stmt = $conn->prepare("SELECT user_id, username, password_hash, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user["password_hash"])) {
            session_regenerate_id(true);
            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"] = $user["role"];
            header("Location: index.php");
            exit;
        }
    }

    $error = "Wrong credentials, try again.";
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Welcome - Benny's Motor Service</title>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, Helvetica, sans-serif;
}

body {
    height: 100vh;
    background: url("images/bg.jpg") center center / cover no-repeat;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Dark overlay */
body::before {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.6);
}

/* LOGIN BOX */
.login-box {
    position: relative;
    width: 420px;
    background: rgba(0,0,0,0.85);
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.8);
    z-index: 2;
}

/* Welcome Text */
.login-box h2 {
    color: #ffd700;
    font-size: 22px;
    font-weight: 500;
    letter-spacing: 1px;
    margin-bottom: 25px;
    text-align: center;
}

/* Inputs */
.login-box input {
    width: 100%;
    padding: 12px;
    margin-bottom: 18px;
    background: #111;
    border: 1px solid #333;
    border-radius: 6px;
    color: #fff;
    font-size: 14px;
    outline: none;
}

.login-box input:focus {
    border-color: #ffd700;
}

/* Button */
.login-box button {
    width: 100%;
    padding: 12px;
    background: #ffd700;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    font-size: 14px;
    transition: 0.2s ease;
}

.login-box button:hover {
    background: #e6c200;
}

/* Error */
.error {
    color: #ff4d4d;
    margin-bottom: 15px;
    font-size: 13px;
    text-align: center;
}
</style>
</head>

<body>

<div class="login-box">

    <h2>Welcome Homie...</h2>

    <?php if($error) echo "<div class='error'>$error</div>"; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Enter Username" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <button type="submit">Enter Garage</button>
    </form>

</div>

</body>
</html>