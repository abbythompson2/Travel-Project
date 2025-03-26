<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="login.css">
    <title>Login</title>
</head>
<header>
    <div class="top">
        <h1>Travel Planner</h1>
        <h4>Start planning your today!</h4></h4>
    </div>
    <nav>
        <button class="button" onclick="location.href='home.php'">Home</button> 
    </nav>   
</header>
<body>
<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trip-project";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} 
catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: home.php");
        exit(); // Ensure that no further code runs after redirect
    } else {
        header("Location: register.php?error=1");
        exit(); // Ensure that no further code runs after redirect
    }
}
?>

<form method="POST" action="login.php">
    <input type="text" name="username" placeholder="Username" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button class="submit" type="submit">Login</button>
</form>

<div class="account">
    No Account? <a href="register.php"> Click Here</a> to register!</p>
</div>

</body>
</html>
