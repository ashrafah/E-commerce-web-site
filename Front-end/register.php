




<!-- nav bar -->
<?php include "include/nav.php"; ?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Galaxy | Register</title>

    <!-- Bootstrap css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- custom css -->
    <link rel="stylesheet" href="../css/login.css">

</head>
<body>

    <div class="login-container">
        <div class="row mt-5">

            <!-- logo section start -->

            <div class="col-0 col-md-5 p-5 d-flex align-items-center justify-content-center rounded-3 logo-color">
                <img src="../assets/Footer/footer-icon.png" alt="Online Galaxy Logo" class="logo-log h-300">
            </div>

            <!-- logo section end -->

            <!-- Login Form Section -->
            <div class="col-12 col-md-7 p-5">
                <h2 class="text-center register-title fw-bold">Register</h2>
                <form>
                    <div class="row">
                        <div class="col-md-6 my-3">
                            <label for="firstName" class="form-label fw-bold">First Name</label>
                            <input type="text" class="form-control rounded-5" name="firstName" id="firstName" placeholder="First Name">
                        </div>
                        <div class="col-md-6 my-3">
                            <label for="lastName" class="form-label fw-bold">Last Name</label>
                            <input type="text" class="form-control rounded-5" name="lastName" id="lastName" placeholder="Last Name">
                        </div>
                    </div>
                    <div class="my-3">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <input type="email" class="form-control rounded-5" name="email" id="email" placeholder="Enter your email">
                    </div>
                    <div class="row">
                        <div class="col-md-6 my-3">
                            <label for="password" class="form-label fw-bold">Password</label>
                            <input type="password" class="form-control rounded-5" id="password" placeholder="Password">
                        </div>
                        <div class="col-md-6 my-3">
                            <label for="confirmPassword" class="form-label fw-bold">Confirm Password</label>
                            <input type="password" class="form-control rounded-5" id="confirmPassword" placeholder="Confirm Password">
                        </div>
                    </div>
                    <div class="d-grid my-3 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary w-50 rounded-4">Register</button>
                    </div>
                    <p class="text-center">
                        Already have an account? <a href="./login.php" class="login-link text-decoration-none">Login</a>
                    </p>
                </form>
            </div>
          
        </div>
      </div>
    <!-- log in form end -->
    



    <!-- bootstrap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    

</body>
</html>


<!-- footer -->
<?php include "include/footer.php"; ?>