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
        <h1>Register</h1>
        <h4>Create an account to have the ability to add recipes!</h4>
    </div>
    <nav>
        <button class="button" onclick="location.href='Homepage.php'">Home</button>
        <button class="button" onclick="location.href='db.php'">Add Recipe</button>
        <button class="button" onclick="location.href='RecipeSearch.PHP'">Search for Recipe</button>
        <button class="button" onclick="location.href='aboutUs.PHP'">About Us</button>
        <button class="button" onclick="location.href='review.PHP'">Reviews</button>
        <?php
        if (isset($_SESSION['user_id'])) {
            echo '<button class="button" onclick="location.href=\'logout.PHP\'">Logout</button>';
        }else{
            echo '<button class="button" onclick="location.href=\'login.PHP\'">Login</button>';
        }
        ?>   
    </nav>   
</header>
<body>
<?php
     $servername = "localhost";
     $username = "root";
     $password = "root";
     $dbname = "project";
 
     try {
         $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
         $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     } catch (PDOException $e) {
         die("Connection failed: " . $e->getMessage());
     }
    
    if(isset($_GET['error']) && $_GET['error'] == 1){
        echo "<p>Login Failed.  Register Below!</p>";
    }
    ?>

<form method="POST" action="register.php">
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button class="submit" type="submit">Register</button>
</form>

<?php

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        try{
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
            $stmt->execute([$username]);

            if($stmt->fetchColumn()>0){
                alert("Username already taken. Try another");
            } else{
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
                $stmt->execute([$username, $email, $password_hash]);

                header("Location: login.php?registered=1");
            }
        } catch (PDOEXCEPTION $e) {
            alert("Error: " . $e->getMessage());
        }
    }
?>
</body>
</html>
