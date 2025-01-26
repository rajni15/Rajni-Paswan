<?php
include "../config/config.php";
include "commonoperation.php";
include "../login/userdetails.php";
include "dbconnect.php";
session_start();


// if (isset($_SESSION['LOGINUSER'])) {
//     $obj = $_SESSION['LOGINUSER'];
//     $userid = $obj->pk;
    
//     $timezoneArray = $_POST['timeZone'];

//     $selectQuery = "SELECT timezone FROM tbluserinfo WHERE userid='$userid'";
//     $selectResult = mysqli_query($conn, $selectQuery);

//     if (mysqli_num_rows($selectResult) > 0) {
       
//         $row = mysqli_fetch_assoc($selectResult);
//         $timezones = $row['timezone'];

//         $timezones = trim($timezones, "{}");
//         echo
//         $timezoneArrayFromDb = explode(",", $timezones);

//         $found = false;
//         foreach ($timezoneArrayFromDb as $index => $timezone) {
//             if ($timezone == $timezoneArray) {
          
//                 unset($timezoneArrayFromDb[$index]);
//                 $found = true;
//                 break;  
//             }
//         }

//         if ($found) {  
//             $timezoneArrayFromDb = array_values($timezoneArrayFromDb);
//             $updatedTimezones = "{" . implode(",", $timezoneArrayFromDb) . "}";
//             $updateQuery = "UPDATE tbluserinfo SET timezone='$updatedTimezones' WHERE userid='$userid'";
//             if (mysqli_query($conn, $updateQuery)) {
//                 echo "Timezone deleted successfully!";
//             } else {
//                 echo "Error updating the timezone list.";
//             }
//         } else {
//             echo "Timezone not found in the list.";
//         }
//     } else {
//         echo "User not found or no timezones set.";
//     }
// } else {
//     echo "User not logged in.";
// }


if (isset($_SESSION['LOGINUSER'])) {
    $obj = $_SESSION['LOGINUSER'];
    $userid = $obj->pk;
    $timezone = isset($_POST['timezones']);
    echo $timezone;
   
    if (isset($_POST['timezones'])) {
        $timezoneArray = $_POST['timezones'];  

        $selectQuery = "SELECT timezone FROM tbluserinfo WHERE userid='$userid'";
        $selectResult = mysqli_query($conn, $selectQuery);

        if (mysqli_num_rows($selectResult) > 0) {
            $row = mysqli_fetch_assoc($selectResult);
            $timezones = $row['timezone'];

            $timezones = trim($timezones, "{}");
            $timezoneArrayFromDb = explode(",", $timezones);
            $timezoneArrayFromDb = array_map('trim', $timezoneArrayFromDb);
            $newTimezoneArray = array_intersect($timezoneArray, $timezoneArrayFromDb);
            $updatedTimezones = "{" . implode(",", $newTimezoneArray) . "}";

            $updateQuery = "UPDATE tbluserinfo SET timezone='$updatedTimezones' WHERE userid='$userid'";
            if (mysqli_query($conn, $updateQuery)) {
                echo "Timezone list updated successfully!";
            } else {
                echo "Error updating the timezone list.";
            }
        } else {
            echo "User not found or no timezones set.";
        }
    } else {
        echo "No timezones to update.";
    }
} else {
    echo "User not logged in.";
}
?>









