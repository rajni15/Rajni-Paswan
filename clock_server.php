<?php
include('config/conn.php');  // Ensure your database connection file is included

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the data sent via AJAX
    $country = $_POST['country'];
    $state = $_POST['state'];
    $timezone = $_POST['timezone'];


    $response = [
        "country" =>$country,
        "state" =>$state,
        "timezone" =>$timezone
    ];
    echo json_encode($response);
    // SQL query to insert data into the database
    // $sql = "INSERT INTO user_location (country, state, timezone) VALUES ('$country', '$state', '$timezone')";

    // if (mysqli_query($conn, $sql)) {
    //     echo json_encode(["status" => "success", "message" => "Location added successfully"]);
    // } else {
    //     echo json_encode(["status" => "error", "message" => "Error: " . mysqli_error($conn)]);
    // }

    // // Close connection
    // mysqli_close($conn);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>
