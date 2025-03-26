<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refined Recipes</title>
</head>
<header>
    <div class="top">
        <h1>Travel Manager</h1>
        <h4>Manage all the details of your trip!</h4>
    </div>
    <nav>
        <?php
        if (isset($_SESSION['user_id'])) {
            echo '<button class="button" onclick="location.href=\'logout.PHP\'">Logout</button>';
            echo '<button class="button" onclick="location.href=\'createTrip.PHP\'">Create Trip</button>';
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
