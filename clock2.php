<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Clock with Time Zone</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.43/moment-timezone-with-data.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        #canvas {
            margin-top: 20px;
        }
        .dropdown {
            margin: 10px;
        }
        .calendar {
            margin-top: 20px;
            border: 1px solid #333;
            display: inline-block;
            padding: 10px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

<h1>Dynamic Clock & Calendar</h1>

<!-- Country and State Dropdowns -->
<div>
    <select id="country" class="dropdown">
        <option value="" disabled selected>Select Country</option>
    </select>
    <select id="state" class="dropdown" disabled>
        <option value="" disabled selected>Select State</option>
    </select>
</div>

<!-- Clock -->
<canvas id="canvas" width="400" height="400" style="background-color:#333">
    Sorry, your browser does not support canvas.
</canvas>

<!-- Calendar -->
<div class="calendar" id="calendar"></div>

<script>
    const canvas = document.getElementById("canvas");
    const ctx = canvas.getContext("2d");
    let radius = canvas.height / 2;
    ctx.translate(radius, radius);
    radius = radius * 0.90;

    let currentTimeZone = moment.tz.guess(); // Default: System's local time zone

    // Country and State Data
    const countryStateData = {
        "USA": ["California", "Texas", "Florida", "New York"],
        "Australia": ["New South Wales", "Queensland", "Victoria", "Tasmania"]
    };

    const timeZones = ["America/Los_Angeles",
        "America/Chicago",
        "America/New_York",
         "America/New_York",
        "Australia/Sydney",
        "Australia/Brisbane",
        "Australia/Melbourne",
        "Australia/Hobart"]
        

    // Populate Country Dropdown
    const countrySelect = document.getElementById("country");
    const stateSelect = document.getElementById("state");

    Object.keys(countryStateData).forEach(country => {
        const option = document.createElement("option");
        option.value = country;
        option.textContent = country;
        countrySelect.appendChild(option);
    });

    // Handle Country Selection
    countrySelect.addEventListener("change", function () {
        const states = countryStateData[this.value];
        stateSelect.innerHTML = '<option value="" disabled selected>Select State</option>';
        stateSelect.disabled = false;

        states.forEach(state => {
            const option = document.createElement("option");
            option.value = state;
            option.textContent = state;
            stateSelect.appendChild(option);
        });
    });

    // Handle State Selection
    stateSelect.addEventListener("change", function () {
        const country = countrySelect.value;
        const state = stateSelect.value;
        const timeZoneKey = state ? `${country}/${state}` : country;
        currentTimeZone = timeZones[timeZoneKey]; // Update the current time zone
        updateCalendar(currentTimeZone);

        // Send data to the server for insertion
        $.ajax({
            type: "POST",
            url: "clock_server.php",  // The PHP script to handle the database insertion
            data: {
                country: country,
                state: state,
                timezone: currentTimeZone
            },
            success: function(response) {
                console.log(response);
                // const res = JSON.parse(response);
                // if (res.status === "success") {
                //     alert(res.message);  // Success message
                // } else {
                //     alert("Error: " + res.message);  // Error message
                // }
            },
            error: function() {
                alert("An error occurred while saving the data.");
            }
        });
    });

    // Start Clock
    function startClock() {
        setInterval(() => {
            drawClock(currentTimeZone);
        }, 1000);
    }

    function drawClock(timeZone) {
        const now = moment().tz(timeZone);
        const hour = now.hours();
        const minute = now.minutes();
        const second = now.seconds();

        ctx.clearRect(-radius, -radius, canvas.width, canvas.height);
        drawFace(ctx, radius);
        drawNumbers(ctx, radius);
        drawHands(ctx, radius, hour, minute, second);
    }

    function drawFace(ctx, radius) {
        ctx.beginPath();
        ctx.arc(0, 0, radius, 0, 2 * Math.PI);
        ctx.fillStyle = "white";
        ctx.fill();

        ctx.strokeStyle = "#333";
        ctx.lineWidth = radius * 0.1;
        ctx.stroke();

        ctx.beginPath();
        ctx.arc(0, 0, radius * 0.1, 0, 2 * Math.PI);
        ctx.fillStyle = "#333";
        ctx.fill();
    }

    function drawNumbers(ctx, radius) {
        ctx.font = `${radius * 0.15}px arial`;
        ctx.textBaseline = "middle";
        ctx.textAlign = "center";

        for (let num = 1; num <= 12; num++) {
            const ang = (num * Math.PI) / 6;
            ctx.rotate(ang);
            ctx.translate(0, -radius * 0.85);
            ctx.rotate(-ang);
            ctx.fillText(num.toString(), 0, 0);
            ctx.rotate(ang);
            ctx.translate(0, radius * 0.85);
            ctx.rotate(-ang);
        }
    }

    function drawHands(ctx, radius, hour, minute, second) {
        drawHand(ctx, ((hour % 12) * Math.PI) / 6 + (minute * Math.PI) / (6 * 60), radius * 0.5, radius * 0.07);
        drawHand(ctx, (minute * Math.PI) / 30 + (second * Math.PI) / (30 * 60), radius * 0.8, radius * 0.07);
        drawHand(ctx, (second * Math.PI) / 30, radius * 0.9, radius * 0.02);
    }

    function drawHand(ctx, pos, length, width) {
        ctx.beginPath();
        ctx.lineWidth = width;
        ctx.lineCap = "round";
        ctx.moveTo(0, 0);
        ctx.rotate(pos);
        ctx.lineTo(0, -length);
        ctx.stroke();
        ctx.rotate(-pos);
    }

    // Update Calendar
    function updateCalendar(timeZone) {
        const now = moment().tz(timeZone);
        const calendar = document.getElementById("calendar");
        calendar.innerHTML = `<strong>${now.format("dddd, MMMM Do YYYY")}</strong>`;
    }

    // Initialize Clock and Calendar
    updateCalendar(currentTimeZone);
    startClock();
</script>

</body>
</html>
