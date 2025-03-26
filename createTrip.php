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
    error_log("Database Error: " . $e->getMessage());
    die("An error occurred. Please try again later.");
}
?>

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
            echo '<button class="button" onclick="location.href=\'logout.php\'">Logout</button>';
        } else {
            echo '<button class="button" onclick="location.href=\'login.php\'">Login</button>';
        }
        ?>   
    </nav>   
</header>
<body>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['user_id'])) {
        echo "You must be logged in to create a trip.";
        exit;
    }

    $budget = filter_input(INPUT_POST, 'budget', FILTER_SANITIZE_NUMBER_INT);
    $endDate = htmlspecialchars($_POST['endDate']);
    $startDate = htmlspecialchars($_POST['startDate']);
    $location = htmlspecialchars($_POST['location']);
    $userID = $_SESSION['user_id'];

    try {
        $stmt = $pdo->prepare("INSERT INTO trip (budget, endDate, location, startDate, userID) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$budget, $endDate, $location, $startDate, $userID]);

        echo "Trip created successfully!";
    } 
    catch (PDOException $e) {
        error_log("Error: " . $e->getMessage());
        echo "An error occurred. Please try again.";
    }
}
?>

<form method="POST" action="">
    <input type="number" name="budget" placeholder="Budget" required>
    <input type="date" name="endDate" required>
    <input type="date" name="startDate" required>
    <input type="text" name="location" placeholder="Location" required>
    <button class="submit" type="submit">Submit</button>
</form>

</body>
</html>
