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
        <h1>Reviews</h1>
        <h4>See and create review for your trip!</h4>
    </div>
    <nav>
    <nav>
        <button class="button" onclick="location.href='home.php'">Home</button> 
        <button class="button" onclick="location.href='logout.php'">Logout</button> 
    </nav>  
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
            echo '<button class="button" onclick="location.href=\'createReview.php\'">Create Review</button>';

        }

        $stmt = $pdo->prepare("SELECT location FROM trip WHERE tripID = ?");
        $stmt->execute([$_SESSION['tripID']]);
        $location = $stmt->fetch(PDO::FETCH_ASSOC);
        $location = $location['location']; 

        $stmt = $pdo->prepare("SELECT * FROM review WHERE location = ?");
        $stmt->execute([$location]);
        $reviews = $stmt->fetchAll();

            if (count($reviews) >= 1) {
                foreach ($reviews as $review) {
                    echo "<li>" . htmlspecialchars($review["review"]) . " " . htmlspecialchars($review['star']) . " " . htmlspecialchars($review['date']) . "</li>";
                }
            }
    
        ?>   
    </nav>   
</header>
<body>
</body>
<footer>

</footer>
</html>
