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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Review</title>
</head>
<header>
    <div class="top">
        <h1>Create Review</h1>
        <h4>Create a review for your trip location!</h4>
    </div> 
</header>
<body>
<nav>
        <button class="button" onclick="location.href='home.php'">Home</button> 
        <button class="button" onclick="location.href='logout.php'">Logout</button> 
    </nav> 

<form method="POST" action="">
    <input type="text" name="review" placeholder="Review" required>
    <input type="number" name="star" placeholder="Star" min="1" max="5" required>
    <button class="submit" type="submit">Create Review</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['tripID']) || !isset($_SESSION['user_id'])) {
        die("Error: No trip or user session found.");
    }

    $review = $_POST['review'];
    $star = $_POST['star'];

    // Fetch the trip location
    $stmt = $pdo->prepare("SELECT location FROM trip WHERE tripID = ?");
    $stmt->execute([$_SESSION['tripID']]);
    $location = $stmt->fetch(PDO::FETCH_ASSOC); 

    if (!$location) {
        die("Error: Invalid trip ID.");
    }

    $current_date = date('Y-m-d');

    try {
        $stmt = $pdo->prepare("INSERT INTO review (location, date, review, star, userID) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$location['location'], $current_date, $review, $star, $_SESSION['user_id']]);

        header("Location: review.php");
        exit();
    } catch (PDOException $e) {
        error_log("Error: " . $e->getMessage());
        die("Database error occurred. Please try again later.");
    }
}
?>
</body>
</html>


