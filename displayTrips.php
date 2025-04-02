<?php
ob_start();
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tripID'])) {
    $_SESSION['tripID'] = $_POST['tripID'];
    // Redirect to the specific page after setting the session
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'review':
                header('Location: review.php');
                break;
            case 'budget':
                header('Location: budget.php');
                break;
            case 'navigation':
                header('Location: navigation.php');
                break;
            case 'information':
                header('Location: generalInformation.php');
                break;
            default:
                header('Location: home.php');
                break;
        }
        exit();
    }
}
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
        <h1>Trips</h1>
        <h4>Welcome, here are all of your trips!</h4>
    </div>
    <nav>
        <button class="button" onclick="location.href='home.php'">Home</button>
        <button class="button" onclick="location.href='logout.php'">Logout</button>
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
            echo '<button class="button" onclick="location.href=\'createTrip.PHP\'">Create Trip</button>';

            $stmt = $pdo->prepare("SELECT * FROM trip WHERE userID = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $trips = $stmt->fetchAll();

             if (count($trips) >= 1) {
                foreach ($trips as $trip) {
                    echo "<li>" . htmlspecialchars($trip['startDate']) . " - " . htmlspecialchars($trip['endDate']) . ", " . htmlspecialchars($trip['location']) . "</li>";
                    echo '<form method="POST" style="display:inline;">
                    <input type="hidden" name="tripID" value="' . htmlspecialchars($trip['tripID']) . '" />
                    <button type="submit" class="button" name="action" value="review">Review</button>
                    <button type="submit" class="button" name="action" value="budget">Budget</button>
                    <button type="submit" class="button" name="action" value="navigation">Navigation</button>
                    <button type="submit" class="button" name="action" value="information">General Information</button>
                  </form>';
        
                }
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
<?php ob_end_flush(); ?>
