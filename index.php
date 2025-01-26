<?php
include('./config/conn.php');
session_start();

// Check if the session variable user_id is set (which means the user is logged in)
if (isset($_SESSION['id']) && isset($_SESSION['email'])) {
    $id = $_SESSION['id'];
    $email = $_SESSION['email'];
} else {
    // If the user is not logged in, redirect them to the login page
    header("Location: loginPage.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to My Dashboard</title>
</head>
<body>

<h1>Welcome to My Dashboard</h1>
<a href="profileInsertAndUpdate.php" class="">User Profile</a>

</body>
</html>
