<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Maps Routing</title>
    <style>
        #map {
            height: 500px;
            width: 100%;
        }
        #directionsPanel {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            height: 200px;
            overflow-y: auto;
            background: #f9f9f9;
        }
    </style>
</head>
<body>

    <h1>Route Between Two Addresses</h1>

    <form id="routeForm">
        <label for="start">Start Address:</label>
        <input type="text" id="start" name="start"><br><br>
        <label for="end">End Address:</label>
        <input type="text" id="end" name="end"><br><br>
        <button type="submit">Get Route</button>
    </form>

    <div id="map"></div>
    <div id="directionsPanel"></div>

    <script src="https://maps.googleapis.com/maps/api/js?key=API_KEY&libraries=places&callback=initMap" async defer></script>

    <script>
        let map, directionsService, directionsRenderer;

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 7,
                center: { lat: 39.8283, lng: -98.5795 }, // Center of USA
                mapTypeId: 'roadmap'
            });

            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer();
            directionsRenderer.setMap(map);
            directionsRenderer.setPanel(document.getElementById("directionsPanel")); // Attach to panel

            // Initialize Autocomplete
            initAutocomplete();
        }

        function initAutocomplete() {
            new google.maps.places.Autocomplete(document.getElementById("start"));
            new google.maps.places.Autocomplete(document.getElementById("end"));
        }

        document.getElementById("routeForm").addEventListener("submit", function (e) {
            e.preventDefault();
            const start = document.getElementById("start").value;
            const end = document.getElementById("end").value;
            calculateRoute(start, end);
        });

        function calculateRoute(start, end) {
            const request = {
                origin: start,
                destination: end,
                travelMode: google.maps.TravelMode.DRIVING,
            };

            directionsService.route(request, function (result, status) {
                if (status === google.maps.DirectionsStatus.OK) {
                    directionsRenderer.setDirections(result);
                    displayDirections(result);
                } else {
                    alert("Directions request failed due to " + status);
                }
            });
        }

        function displayDirections(result) {
            const directionsPanel = document.getElementById("directionsPanel");
            directionsPanel.innerHTML = ""; // Clear previous directions
            const route = result.routes[0].legs[0];

        }
    </script>

</body>
</html>



