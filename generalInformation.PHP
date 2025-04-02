<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>General Information</title>
</head>
<body>

    <h1>General Information</h1>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAQgyVs3mPAIybjdo3kGo948f3DR65qFdE&libraries=places" async defer></script>



</body>


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

    
    if (!isset($_SESSION['tripID']) || !isset($_SESSION['user_id'])) {
        
    }


    if (isset($_SESSION['tripID'])) {

        $stmt = $pdo->prepare("SELECT location FROM trip WHERE tripID = ?");
        $stmt->execute([$_SESSION['tripID']]);
        $location = $stmt->fetch();
         
    }

    ?> 


<h2>Currency Converter</h2>
<form id="currency-form">
    <label for="amount">Amount:</label>
    <input type="number" id="amount" required>

    <label for="from">From:</label>
    <select id="from">
        <option value="USD">USD</option>
        <option value="EUR">EUR</option>
        <option value="GBP">GBP</option>
        <option value="INR">INR</option>
    </select>

    <label for="to">To:</label>
    <select id="to">
        <option value="USD">USD</option>
        <option value="EUR">EUR</option>
        <option value="GBP">GBP</option>
        <option value="INR">INR</option>
    </select>

    <button type="submit">Convert</button>
</form>

<h3>Converted Amount: <span id="result">-</span></h3>


<h2>Language Translator</h2>
<form id="translator-form">
    <label for="text">Enter Text:</label>
    <input type="text" id="text" required>

    <label for="from-lang">From:</label>
    <select id="from-lang">
        <option value="en">English</option>
        <option value="es">Spanish</option>
        <option value="fr">French</option>
        <option value="de">German</option>
        <option value="zh">Chinese</option>
    </select>

    <label for="to-lang">To:</label>
    <select id="to-lang">
        <option value="en">English</option>
        <option value="es">Spanish</option>
        <option value="fr">French</option>
        <option value="de">German</option>
        <option value="zh">Chinese</option>
    </select>

    <button type="submit">Translate</button>
</form>

<h3>Translated Text: <span id="translated-result">-</span></h3>

<h2>Local Time</h2>
<p id="local-time">Loading...</p>



<script>
document.getElementById("currency-form").addEventListener("submit", async function(event) {
    event.preventDefault();

    let amount = document.getElementById("amount").value;
    let fromCurrency = document.getElementById("from").value;
    let toCurrency = document.getElementById("to").value;

    let apiKey = "d2361c79d1c2f97086664a55";  // Replace with your actual API key
    let url = `https://v6.exchangerate-api.com/v6/${apiKey}/latest/${fromCurrency}`;

    try {
        let response = await fetch(url);
        let data = await response.json();

        if (data.conversion_rates[toCurrency]) {
            let convertedAmount = (amount * data.conversion_rates[toCurrency]).toFixed(2);
            document.getElementById("result").textContent = convertedAmount + " " + toCurrency;
        } else {
            document.getElementById("result").textContent = "Invalid currency";
        }
    } catch (error) {
        console.error("Error fetching exchange rate:", error);
        document.getElementById("result").textContent = "Error fetching data";
    }
});
</script>




<?php
session_start();

if (isset($_SESSION['tripID'])) {
    $stmt = $pdo->prepare("SELECT location FROM trip WHERE tripID = ?");
    $stmt->execute([$_SESSION['tripID']]);
    $location = $stmt->fetchColumn(); 
}

?>



<script>
    let locationName = "<?php echo addslashes($location); ?>"; // Pass PHP variable to JS

    async function fetchLocalTime(location) {
    try {
        let geocodeApiKey = "AIzaSyAQgyVs3mPAIybjdo3kGo948f3DR65qFdE";
        let timeZoneApiKey = "AIzaSyAQgyVs3mPAIybjdo3kGo948f3DR65qFdE"; 



        // Get latitude and longitude
        let geocodeUrl = `https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(location)}&key=${geocodeApiKey}`;
        let geocodeResponse = await fetch(geocodeUrl);
        let geocodeData = await geocodeResponse.json();

        if (geocodeData.status !== "OK") {
            console.error("Geocoding API error:", geocodeData.status);
            document.getElementById("local-time").textContent = "Unable to fetch location.";
            return;
        }

        let { lat, lng } = geocodeData.results[0].geometry.location;



        // Get timezone
        let timestamp = Math.floor(Date.now() / 1000);
        let timeZoneUrl = `https://maps.googleapis.com/maps/api/timezone/json?location=${lat},${lng}&timestamp=${timestamp}&key=${timeZoneApiKey}`;
        let timeZoneResponse = await fetch(timeZoneUrl);
        let timeZoneData = await timeZoneResponse.json();

        if (timeZoneData.status !== "OK") {
            console.error("Time Zone API error:", timeZoneData.status);
            document.getElementById("local-time").textContent = "Unable to fetch time.";
            return;
        }

        let localOffsetMs = (timeZoneData.rawOffset + timeZoneData.dstOffset) * 1000;
        let localTime = new Date((timestamp * 1000) + localOffsetMs).toLocaleString("en-US", { timeZone: "UTC" });


        document.getElementById("local-time").textContent = `Local Time: ${localTime}`;
    } 
    
    catch (error) {
        console.error("Error fetching time:", error);
        document.getElementById("local-time").textContent = "Error fetching local time.";
    }
}

// Call the function when the page loads
window.onload = function () {
    fetchLocalTime(locationName);
};

</script>







<script>
document.getElementById("translator-form").addEventListener("submit", async function(event) {
    event.preventDefault();

    let text = document.getElementById("text").value;
    let fromLang = document.getElementById("from-lang").value;
    let toLang = document.getElementById("to-lang").value;

    
    let url = `https://api.mymemory.translated.net/get?q=${encodeURIComponent(text)}&langpair=${fromLang}|${toLang}`;

    try {
        let response = await fetch(url);
        let data = await response.json();

        if (data.responseData && data.responseData.translatedText) {
            document.getElementById("translated-result").textContent = data.responseData.translatedText;
        } 
        else {
            document.getElementById("translated-result").textContent = "Translation error";
        }
    } catch (error) {
        console.error("Error translating text:", error);
        document.getElementById("translated-result").textContent = "Error fetching translation";
    }
});


</script>


</html>


