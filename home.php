<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Manager</title>
</head>
<header>
    <div class="top">
        <h1>Travel Manager</h1>
        <h4>Manage all the details of your trip!</h4>
    </div>
    <nav>
        <?php

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "trip-project"; 

        try {
            $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } 


        catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            die("An error occurred. Please try again later.");
        }




        if (isset($_SESSION['user_id'])) {
            echo '<button class="button" onclick="location.href=\'logout.PHP\'">Logout</button>';
            echo '<button class="button" onclick="location.href=\'createTrip.PHP\'">Create Trip</button>';


            $stmt = $pdo->prepare("SELECT * FROM trip WHERE userID = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $trips = $stmt->fetchAll();

            if (count($trips) >= 1) { 
                echo "You have a trip!";
            }

        }
        
        else{
            echo '<button class="button" onclick="location.href=\'login.PHP\'">Login</button>';
        }
        ?>   
    </nav>   
</header>
<body>
</body>
<footer>

</footer>
</html>
