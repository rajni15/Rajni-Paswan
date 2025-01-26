<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Login</h2>
        <form id="loginForm" class="p-4 border rounded" method="post">
            <!-- Email Field -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <!-- Password Field -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <!-- Submit Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>

            <!-- Forgot Password Link -->
            <div class="mt-3 text-center">
                <a href="forgot_password.php" class="text-decoration-none">Forgot Password?</a>
            </div>
        </form>
    </div>


    <script>

        $(document).ready(function(){
            $("form#loginForm").submit(function(event){
                event.preventDefault();
                var email = $("#email").val();
                var password = $("#password").val();
                  $.ajax({
                       type:"Post",
                       url:"userlogin.php",
                       data:{
                        email: email,
                        password: password
                       },
                       success:function(response){
                        console.log(response);
                        window.location.href = "index.php"; 
                       }
                  });

            });
        });
    </script>
</body>
</html>
