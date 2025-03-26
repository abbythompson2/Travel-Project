<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<header>
    <div class="top">
        <h1>Register</h1>
        <h4>Create an account to have the ability to plan trips!</h4>
    </div>
    <nav>
        <?php
        if (isset($_SESSION['ID'])) {
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
     $password = "";
     $dbname = "trip-project";    ## CHANGE THIS FOR YOUR DB NAME
 
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
    <input type="text" name="name" placeholder="Name" required>
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button class="submit" type="submit">Register</button>
</form>

<?php

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $password = password_hash($password, PASSWORD_BCRYPT);

        try{
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE username = ?");
            $stmt->execute([$username]);

            if($stmt->fetchColumn()>0){
                alert("Username already taken. Try another");
            } 
            
            else{
                $stmt = $pdo->prepare("INSERT INTO user (name, username, password) VALUES (?, ?, ?)");
                $stmt->execute([$name, $username, $password]);

                header("Location: login.php?registered=1");
            }
        } 
        catch (PDOEXCEPTION $e) {
            alert("Error: " . $e->getMessage());
        }
    }
?>
</body>
</html>
