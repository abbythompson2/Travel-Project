<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refined Recipes</title>
    <link rel="stylesheet" href="home.css">
</head>
<header>
    <div class="top">
        <h1>Refined Recipes</h1>
        <h4>Explore a variety of recipes and add your own!</h4>
    </div>
    <nav>
        <button class="button" onclick="location.href='Homepage.php'">Home</button>
        <button class="button" onclick="location.href='db.php'">Add Recipe</button>
        <button class="button" onclick="location.href='RecipeSearch.PHP'">Search for Recipe</button>
        <button class="button" onclick="location.href='RecipeSearch.PHP'">All Recipes</button>
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
    <img src="recipe.jpg" alt="Logo for website">
</body>
<footer>

</footer>
</html>
