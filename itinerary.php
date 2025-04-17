<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel_project_final";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    die("An error occurred. Please try again later.");
}

if (!isset($_SESSION['tripID'])) {
    die("No trip selected.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['event_name'], $_POST['event_date'])) {
    $event_date = $_POST['event_date'];
    $event_name = $_POST['event_name'];

    $stmt = $pdo->prepare("INSERT INTO events (event_date, event_name, tripID) VALUES (?, ?, ?)");
    $stmt->execute([$event_date, $event_name, $_SESSION['tripID']]);
}

// Fetch trip details
$stmt = $pdo->prepare("SELECT startDate, endDate, location, zipcode, postalcode FROM trip WHERE tripID = ?");
$stmt->execute([$_SESSION['tripID']]);
$trip = $stmt->fetch(PDO::FETCH_ASSOC);

$start = new DateTime($trip['startDate']);
$end = new DateTime($trip['endDate']);
$end->modify('+1 day'); // include end date
$interval = new DateInterval('P1D');
$period = new DatePeriod($start, $interval, $end);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="itinerary.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Manager</title>
</head>
<body>
<header>
    <div class="top">
        <h1>Travel Manager</h1>
        <h4>Manage all the details of your trip!</h4>
    </div>
    <nav>
        <?php
        if (isset($_SESSION['tripID']) && isset($_SESSION['user_id'])) {
            echo '<button class="button" onclick="location.href=\'logout.php\'">Logout</button>';
            echo '<button class="button" onclick="location.href=\'createTrip.php\'">Create Trip</button>';
        }
        ?>
    </nav>
</header>
<div class="Daily-Section">
<?php foreach($period as $date): ?>
    <div class='Day'>
        <h2><?= $date->format('m-d-y') ?></h2>

        <?php
        // Get events for this date
        $stmt = $pdo->prepare("SELECT * FROM events WHERE tripID = ? AND event_date = ?");
        $stmt->execute([$_SESSION['tripID'], $date->format('Y-m-d')]);
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <form method='POST' action='itinerary.php'>
            <h3>Add an event</h3>
            <input type='text' name='event_name' placeholder='Event name' required>
            <input type='hidden' name='event_date' value='<?= $date->format('Y-m-d') ?>'>
            <button type='submit'>Submit</button>
        </form>

        <?php if (count($events) > 0): ?>
            <?php foreach ($events as $event): ?>
                <div class='event'>
                    <h4>Event Name: <?= htmlspecialchars($event['event_name']) ?></h4>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No events for this day yet.</p>
        <?php endif; ?>

    </div>
<?php endforeach; ?>
</div>
</body>
</html>
