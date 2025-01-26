<?php 
include('config/conn.php');

if($_SERVER["REQUEST_METHOD"]="POST"){
$email = $_POST['email'];
$password = $_POST['password'];

$sql1 = "INSERT INTO tbllogin (email, password) VALUES ('$email', '$password')";

if (mysqli_query($conn, $sql1)) {
    // Retrieve the last inserted ID (user_id) from tbllogin
    $userid = mysqli_insert_id($conn);

    // Step 2: Insert into tbluserinfo table with the retrieved user_id
    $sql2 = "INSERT INTO  tbuserinfo (userid) VALUES ('$userid')";

    if (mysqli_query($conn, $sql2)) {
        // Return success response with email and user_id
        $response = [
            "email" => $email,
            "user_id" => $userid
        ];
        echo json_encode($response);
    } else {
        echo "Error in tbuserinfo: " . mysqli_error($conn);
    }
} else {
    echo "Error in tbllogin: " . mysqli_error($conn);
}

// Return the response as JSON
// echo json_encode($response);
//   } else {
//     echo "Error: " . $sql . "<br>" . mysqli_error($conn);
//   }

}else {
    echo "Invalid request method";
}

?>
