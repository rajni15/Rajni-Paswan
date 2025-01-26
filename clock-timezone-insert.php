<?php
include "../config/config.php";
include "commonoperation.php";
include "../login/userdetails.php";
include "dbconnect.php";
session_start();

// if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['timeZone'])) {
//     if (isset($_SESSION['LOGINUSER'])) {
//         $obj = $_SESSION['LOGINUSER'];

    
//         $userid = $obj->pk;
//         $timezone = $_POST['timeZone'];

//         $timezoneArray = json_decode($_POST['timeZone'], true);
       
//         $selectQuery = "SELECT * FROM tbluserinfo WHERE userid='$userid'";

//         $selectResult = mysqli_query($conn, $selectQuery);

     
//         if (mysqli_num_rows($selectResult) > 0) {
//             $row = mysqli_fetch_assoc($selectResult);

//             $existingTimeZones = $row['timezone']; 
//             $newTimeZones = implode(",", $timezoneArray); 
            
//             $updatedTimeZones = $existingTimeZones . ',' . $newTimeZones;
//             $updateQuery = "UPDATE tbluserinfo SET timezone='$updatedTimeZones' WHERE userid='$userid'";
//             $result = mysqli_query($conn, $updateQuery);


            
//             if ($result) {
//                 echo "Time zone updated successfully!";
//             } else {
//                 echo "Error: " . $conn->error;
//             }
//         } 
//     } else {
//         echo "User not logged in.";
//     }
// } else {
//     echo "No time zone provided.";
// }

  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['timeZone'])) {
      if (isset($_SESSION['LOGINUSER'])) {
          $obj = $_SESSION['LOGINUSER'];
          $userid = $obj->pk;
  
          $timezoneArray = json_decode($_POST['timeZone'], true);
  
          if (is_array($timezoneArray)) {
          
              $formattedNewTimeZones = implode(',', $timezoneArray);
  
              $selectQuery = "SELECT * FROM tbluserinfo WHERE userid='$userid'";
              $selectResult = mysqli_query($conn, $selectQuery);
  
              if (mysqli_num_rows($selectResult) > 0) {
                  $row = mysqli_fetch_assoc($selectResult);
                  $existingTimeZones = $row['timezone'];
  
                  if (!empty($existingTimeZones)) {
                 
                      $existingTimeZones = trim($existingTimeZones, '{}');
  
                     $updatedTimeZones = $existingTimeZones . ',' . $formattedNewTimeZones;
                  } else {
                    
                      $updatedTimeZones = $formattedNewTimeZones;
                  }
  
                  $updatedTimeZones = '{' . $updatedTimeZones . '}';
  
                  $updateQuery = "UPDATE tbluserinfo SET timezone='$updatedTimeZones' WHERE userid='$userid'";
                  $result = mysqli_query($conn, $updateQuery);
  
                  if ($result) {
                      echo "Time zone updated successfully!";
                  } else {
                      echo "Error: " . $conn->error;
                  }
              }
          } else {
              echo "Invalid time zone data.";
          }
      } else {
          echo "User not logged in.";
      }
  } else {
      echo "No time zone provided.";
  }

?>

