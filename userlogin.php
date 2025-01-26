<?php 
include('config/conn.php');
session_start();
if($_SERVER["REQUEST_METHOD"]="POST"){
$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM tbllogin WHERE email = '$email' AND password = '$password'";
$result = mysqli_query($conn,$sql);

if (mysqli_num_rows($result)>0) {

    $row = mysqli_fetch_assoc($result);
    // $id = $row['id'];
    $_SESSION['id'] = $row['id'];
    $_SESSION['email'] = $row['email'];

    $response = [
        "user_id" => $row['id'],
        "email" => $row['email']
        // "password" => $password  // Just for demonstration, you might not return the password in real use
    ];

    // Return the response as JSON
    echo json_encode($response);

 
} else {
    echo "Error in tbllogin: " . mysqli_error($conn);
}

}else {
    echo "Invalid request method";
}

?>
