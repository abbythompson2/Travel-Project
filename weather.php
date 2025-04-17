
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="weather.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather</title>
</head>

<header>
    <div class="top">
        <h1>Travel Manager</h1>
        <h4>Manage all the details of your trip!</h4>
    </div>
    <nav>
         <button class="button" onclick="location.href='home.php'">Home</button> 
         <button class="button" onclick="location.href='logout.php'">Logout</button> 
     </nav>  
</header>
<body>
    <div id="form-container" class="form-container">
        <form id="weather-form">
            <input type="text" name="cityName" id="city_input" placeholder="Enter City Name" required>
       
            <button type="submit" id="searchBtn">Submit</button>
        </form>
    </div>

    
    <div  id="card" class="card" style="display:none;">
    <h2>Forecast</h2>
        <div id="forecast-today" class="forecast-wrapper" >
            <div class="forecast-item">
                <span>___&deg;C</span>
                <span>___&deg;C</span>

            </div>
            <p>___</p>
            <p>___</p>
            <p>___</p>
            <p>___</p>

        </div>
        <div class="days-Forecast">
            <div class="forecast-item">
                <span>___&deg;C</span>
                <span>___&deg;C</span>

            </div>
            <p>___</p>
            <p>___</p>
            <p>___</p>
            <p>___</p>

        </div>
        <div class="days-Forecast">
            <div class="forecast-item">
                <span>___&deg;C</span>
                <span>___&deg;C</span>

            </div>
            <p>___</p>
            <p>___</p>
            <p>___</p>
            <p>___</p>

        </div>
        <div class="days-Forecast">
            <div class="forecast-item">
                <span>___&deg;C</span>
                <span>___&deg;C</span>

            </div>
            <p>___</p>
            <p>___</p>
            <p>___</p>
            <p>___</p>

        </div>
        <div class="days-Forecast">
            <div class="forecast-item">
                <span>___&deg;C</span>
                <span>___&deg;C</span>

            </div>
            <p>___</p>
            <p>___</p>
            <p>___</p>
            <p>___</p>

        </div>
    </div>
</body>

<script>
    document.getElementById('weather-form').addEventListener('submit', function(e){
        e.preventDefault();
        document.getElementById('card').style.display ='flex';
        this.reset();
    });
</script>

<script>
    // Replace with your OpenWeatherMap API key
    const apiKey = '13126d9f12f3d37576962945667ee65a';
    const searchBtn = document.getElementById('searchBtn');
    const cityInput = document.getElementById('city_input');
    const forecastContainer = document.getElementById('forecast-container');


    searchBtn.addEventListener('click', function(event){
        event.preventDefault();
        getCityCoordinates();
        this.reset();
    });

   
    
    function getCityCoordinates(){
        console.log("get City coordinates triggered");

        let cityName = cityInput.value.trim();
        if(!cityName) return;
        let GEOCODING_API_URL_ = `http://api.openweathermap.org/geo/1.0/direct?q=${cityName}&limit=1&appid=${apiKey}`
        fetch(GEOCODING_API_URL_).then(res => res.json()).then(data =>{
            console.log(data);
            if(!data.length) throw new Error("City Not Found");
            const{lat,lon, } = data[0];
            getWeatherDetails(lat, lon);
            cityInput.value ='';
        }).catch(() => {
            alert(`Failed to fetch coordinates of ${cityName}`);
        });

    }

    function getWeatherDetails(lat, lon){
        
        let FORECAST_API_URL = `https://api.openweathermap.org/data/2.5/forecast?lat=${lat}&lon=${lon}&units=metric&appid=${apiKey}`

        days = [
            "Sunday",
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday",
            "Saturday"
        ],
        months = [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec"
        ];

const forecastToday = document.getElementById("forecast-today");
const forecastCards = document.querySelectorAll(".days-Forecast");

// Clear previous content
forecastToday.innerHTML = "";
forecastCards.forEach(card => card.innerHTML = "");

fetch(FORECAST_API_URL)
    .then(res => res.json())
    .then(data => {
        const dailyForecasts = [];
        const addedDates = new Set();

        data.list.forEach(entry => {
            const date = new Date(entry.dt_txt);
            const dateStr = date.toDateString();
            if (!addedDates.has(dateStr) && entry.dt_txt.includes("12:00:00")) {
                dailyForecasts.push(entry);
                addedDates.add(dateStr);
            }
        });

        // Handle today's forecast (first item in list)
        const today = data.list[0]; // Closest time entry (may not be noon!)
        const todayDate = new Date(today.dt_txt);
        forecastToday.innerHTML = `
            <div class="forecast-item">
                <div class="icon-wrapper">
                    <img src="https://openweathermap.org/img/wn/${today.weather[0].icon}.png" alt="${today.weather[0].description}">
                    <span>${Math.round(today.main.temp)}&deg;C</span>
                </div>
                <p>${todayDate.getDate()} ${months[todayDate.getMonth()]}</p>
                <p>${days[todayDate.getDay()]}</p>
                <p>Description: ${today.weather[0].description}</p>
                <p>Humidity: ${today.main.humidity}%</p>
            </div>
        `;

        // Now fill in next forecast days
        forecastCards.forEach((card, index) => {
            const forecast = dailyForecasts[index]; 
            if (!forecast) return;

            const date = new Date(forecast.dt_txt);
            card.innerHTML = `
                <div class="forecast-item">
                    <div class="icon-wrapper">
                        <img src="https://openweathermap.org/img/wn/${forecast.weather[0].icon}.png" alt="${forecast.weather[0].description}">
                        <span>${Math.round(forecast.main.temp)}&deg;C</span>
                    </div>
                    <p>${date.getDate()} ${months[date.getMonth()]}</p>
                    <p>${days[date.getDay()]}</p>
                    <p>Description: ${forecast.weather[0].description}</p>
                    <p>Humidity: ${forecast.main.humidity}%</p>
                </div>
            `;
        });
        document.getElementById('card').style.display='flex';

    })
    .catch(err => {
        console.error("Error rendering forecast:", err);
        alert('Failed to fetch current weather');
    });
    }
</script>
</html>
