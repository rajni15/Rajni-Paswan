<?php
  // Start the session to access session variables

include('config/conn.php');
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['email'])) {

   $userid = $_SESSION['id'];

   if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize the form inputs to prevent SQL injection
    $firstName =$_POST['firstName'];
    $lastName = $_POST['lastName'];
    $country = $_POST['country'];
    $state = $_POST['city'];
    $zipcode = $_POST['zipcode'];

    $response = [
        "firstname" => $firstName,
        "lastName" =>$lastName,
        "country"=>$country,
        "state"=>$state,
        "zipcode"=>$zipcode,
        "id"=>$id
        // "password" => $password  // Just for demonstration, you might not return the password in real use
    ];

    // Return the response as JSON
    echo json_encode($response);


    
    // Check if the user already has data in the table tbuserinfo
    $sql_check = "SELECT * FROM tbuserinfo WHERE userid = '$userid'";
    $result = mysqli_query($conn, $sql_check);

    // If a record already exists for this user, we perform an UPDATE
    if (mysqli_num_rows($result) > 0) {
        $sql_update = "UPDATE tbuserinfo 
                       SET fullname = '$firstName', 
                           lastname = '$lastName', 
                           counrty = '$country', 
                           state= '$state', 
                           zipcode = '$zipcode'
                       WHERE userid = '$userid'";

        if (mysqli_query($conn, $sql_update)) {
            echo json_encode(["status" => "success", "message" => "Record updated successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error updating record: " . mysqli_error($conn)]);
        }
    } else {
        // If no record exists, we perform an INSERT
        $sql_insert = "INSERT INTO tbuserinfo (fullname, lastname, counrty, state, zipcode) 
                       VALUES ('$firstName', '$lastName', '$country', '$state', '$zipcode')";

        if (mysqli_query($conn, $sql_insert)) {
            echo json_encode(["status" => "success", "message" => "Record inserted successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error inserting record: " . mysqli_error($conn)]);
        }
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

}
?>
