<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel_project_final";

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
    <link rel="stylesheet" href="budget.css">
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

if (isset($_SESSION['user_id'])) {
    echo '<button class="button" onclick="location.href=\'logout.PHP\'">Logout</button>';
    echo '<button class="button" onclick="location.href=\'createTrip.PHP\'">Create Trip</button>';
    
}

?>
</nav>
</header>

<body>
    <div class="form-container">
        <form method="POST" action="budget.php">
            <h3>Enter a expense</h3>
            <input type="text" name="expense_name" placeholder="expense name" required>
            <input type="number" name="expense_amount" placeholder="expense amount" required>
            <button type="submit">Submit</button>
        </form>
    </div>

    <?php
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $expense_name = $_POST['expense_name'];
        $expense_amount = $_POST['expense_amount'];

        $stmt = $pdo->prepare("SELECT expenseID FROM expenses WHERE expense_name = ? AND expense_amount = ?");
        $stmt->execute([$expense_name, $expense_amount]);
        $expense = $stmt->fetch();
    
        if($expense){
            $expenseID = $expense['expenseID']; 
        }else{
            $stmt = $pdo->prepare("INSERT INTO expenses (expense_name, expense_amount) VALUES (?, ?)");
            $stmt->execute([$expense_name, $expense_amount]);
            $expenseID = $pdo->lastInsertId();
        }
        $stmt = $pdo->prepare("INSERT INTO trip_expenses (expenseID, tripID) VALUES (?, ?)");
        $stmt->execute([$expenseID, $_SESSION['tripID']]);
    }

    ?>
<?php
$stmt = $pdo->prepare("SELECT expenseID FROM trip_expenses WHERE tripID = ?");
         $stmt->execute([$_SESSION['tripID']]);
         $expense_IDs = $stmt->fetchAll();

         echo"<div class='expenses'>";
         
         if (count($expense_IDs)>=1){
            foreach ($expense_IDs as $expenseID){
                $stmt = $pdo->prepare("SELECT * FROM expenses WHERE  expenseID=?");
                $stmt->execute([$expenseID['expenseID']]);
                $expense = $stmt->fetch();

                echo "<div class='expense'>";
                echo"<p>Expense Name: ".htmlspecialchars($expense['expense_name']). "<p>";
                echo "<p>Amount: $" .htmlspecialchars($expense['expense_amount']). "<p>";
                echo "</div>";
            }

         }
         echo "</div>";

?>    
</body>

<footer>

</footer>
</html>
