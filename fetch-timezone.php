<?php
include "../config/config.php";
include "commonoperation.php";
include "../login/userdetails.php";
include "dbconnect.php";
session_start();

if (isset($_SESSION['LOGINUSER'])) {
    $obj = $_SESSION['LOGINUSER'];
    $userid = $obj->pk;

   
    $selectQuery = "SELECT timezone FROM tbluserinfo WHERE userid='$userid'";
    $selectResult = mysqli_query($conn, $selectQuery);

    if (mysqli_num_rows($selectResult) > 0) {
        // Fetch the result
        $row = mysqli_fetch_assoc($selectResult);
        $timezones = $row['timezone'];

        // Clean the timezones and convert to an array (remove curly braces and split by commas)
        $timezonesArray = explode(",", trim($timezones, "{}"));


        echo json_encode($timezonesArray);
    } else {
        echo json_encode([]); 
    }
} else {
    echo json_encode([]);  
}
?>
