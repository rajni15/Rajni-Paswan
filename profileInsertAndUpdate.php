<?php
include('./config/conn.php');
session_start();

// Check if the session variable user_id is set (which means the user is logged in)
if (isset($_SESSION['id']) && isset($_SESSION['email'])) {
   $id = $_SESSION['id'];
   $email = $_SESSION['email'];
}else{
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
    <title>Registration Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Registration Form</h2>
        <form id="registrationForm" class="p-4 border rounded" method="post" action="">
            <!-- First Name Field -->
            <div class="mb-3">
                <label for="firstName" class="form-label">First Name</label>
                <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter your first name" value="" required>
            </div>

            <!-- Last Name Field -->
            <div class="mb-3">
                <label for="lastName" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter your last name" required>
            </div>

            <!-- Email Field -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $email;?>"placeholder="Enter your email" required>
            </div>

            <!-- Country Field -->
            <div class="mb-3">
                <label for="country" class="form-label">Country</label>
                <input type="text" class="form-control" id="country" name="country" placeholder="Enter your country" required>
            </div>

            <!-- City Field -->
            <div class="mb-3">
                <label for="city" class="form-label">City</label>
                <input type="text" class="form-control" id="city" name="city" placeholder="Enter your city" required>
            </div>

            <!-- Zip Code Field -->
            <div class="mb-3">
                <label for="zipcode" class="form-label">Zip Code</label>
                <input type="text" class="form-control" id="zipcode" name="zipcode" placeholder="Enter your zip code" required>
            </div>

            <!-- Submit Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function(){
           $("form#registrationForm").submit(function(event){
             event.preventDefault();
             let firstName = $("#firstName").val();
             let lastName = $("#lastName").val();
             let country = $("#country").val();
             let city = $("#city").val()
             let zipcode = $("#zipcode").val()


            $.ajax({
               type:"Post",
               url:"InsertAndUpdate.php",
               data:{
                       firstName:firstName,
                       lastName:lastName,
                       country:country,
                       city:city,
                       zipcode:zipcode
               },
               success:function(response){
                console.log(response);
                // window.location.href = "page.php"; 

               }
            });
           });
        });
    </script>
</body>
</html>
