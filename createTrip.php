<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<header>
    <div class="top">
        <h1>Create Trip</h1>
        <h4>Create a trip to start planning!</h4>
    </div>
    <nav>
        <?php
        if (isset($_SESSION['user_id'])) {
            echo '<button class="button" onclick="location.href=\'logout.PHP\'">Logout</button>';
        }
        
        else{
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
     } 
     
     catch (PDOException $e) {
         die("Connection failed: " . $e->getMessage());
     }

    ?>

<form method="POST" action="register.php">
    <input type="number" name="budget" placeholder="Budget" required>
    <input type="date" name="endDate" placeholder="End Date" required>
    <input type="date" name="startDate" placeholder="Start Date" required>
    <input type="text" name="location" placeholder="Location" required>
    <button class="submit" type="submit">Submit</button>
</form>

<?php

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $budget = $_POST['budget'];
        $endDate = $_POST['endDate'];
        $startDate = $_POST['startDate'];
        $location = $_POST['location'];


        try{
      
        $stmt = $pdo->prepare("INSERT INTO trip (budget, endDate, location, startDate, userID) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$budget, $endDate, $location, $startDate, $_SESSION['user_id']]);

            
        } 
        catch (PDOEXCEPTION $e) {
            alert("Error: " . $e->getMessage());
        }
    }
?>
</body>
</html>
