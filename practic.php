<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Div Data Example</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        .container h2 {
            color: #333;
        }
        .container p {
            color: #555;
            line-height: 1.6;
        }
        .julie {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div> <?php getdata(); ?></div>
        <div class="julie"></div> <!-- Data will be populated here -->
    </div>

    <script>
        // JavaScript function to populate data inside the .julie div
        function fatchdata(data) {
            const julieDiv = document.querySelector('.julie');
            // Loop through the data and create a paragraph for each entry
            data.forEach(item => {
                const p = document.createElement('p');
                p.textContent = `Country: ${item.country}, City: ${item.city}`;
                julieDiv.appendChild(p);  // Append each paragraph to the .julie div
            });
        }

       
    </script>
</body>
</html>

<?php
// getdata.php

function getdata() {
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "your_database_name"; // Replace with your database name

    $conn = new mysqli($host, $user, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT country, city FROM timezone";
    $result = $conn->query($sql);

    $res = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $res[] = [
                'country' => $row['country'],
                'city' => $row['city']
            ];
        }
    }

    // Return the JSON data to the JavaScript function
    echo "fatchdata(" . json_encode($res) . ");";
}

// Call the function only if this file is accessed directly
// if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
//     getdata();
// }
?>
