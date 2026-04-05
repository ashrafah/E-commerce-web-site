<!-- nav bar -->
<?php include "include/nav.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Reload</title>

    <!-- Bootstrap css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');



        *{
            font-family: poppins ,sans-serif;
            margin: 0;
            padding: 0;
        }
        body {
            background-color: #fff;
        }

        .login-container{
            max-width: 900px;
            margin: 20px auto;
            background: #E7E0EF;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .login-form {
            padding: 30px;
        }
        .btn-login:hover {
            background-color: #36194C;
        }
        .logo-color {
            background-color: #D4BEE4;
        }



        .logo-log{
            height: 500px;
            width: 38px;
        }
        .logo-color {
            background-image: url('../assets/others/mobile reload.png');
            background-size: cover;       
            background-position: center;
            background-repeat: no-repeat;
            height: 680px;                
        }

    </style>

</head>
<body>

    <div class="login-container">
        <div class="row mt-5">

            <!-- logo section start -->
            <div class="col-0 col-md-5 p-5 d-flex align-items-center justify-content-center rounded-3 logo-color">
                <!-- <img src="../assets/others/mobile reload.png" alt="Broadband" class="logo-log"> -->
            </div>
            <!-- logo section end -->

            <!-- Login Form Section -->
            <div class="col-12 col-md-7 p-5">
                <h2 class="text-center register-title fw-bold">Mobile Reload</h2>
                <form action="payment.php" method="GET">

                    <!-- Hidden input for permanent parameter -->
                    <input type="hidden" name="Type" value="MobileReload">                    
                    
                    <!-- Network Provider Input (get from URL) -->
                    <div class="my-5">
                        <label for="network-provider" class="form-label fw-bold">Network Provider</label>
                        <?php
                        // Get the network provider name from the URL (if set)
                        $networkProvider = isset($_GET['Name']) ? $_GET['Name'] : 'Unknown Provider';
                        ?>
                        <input type="text" class="form-control rounded-5" name="Name" id="provider" value="<?= htmlspecialchars($networkProvider) ?>" readonly>
                    </div>

                    <!-- Hidden input for permanent parameter -->
                    <input type="hidden" name="Cname" value="MobileReload">
                    
                    <!-- Hidden input for permanent parameter -->
                    <input type="hidden" name="Num" value="MobileReload">    

                    <!-- Mobile Number Input -->
                    <div class="my-5">
                        <label for="vehical-number" class="form-label fw-bold">Enter Your Mobile Number</label>
                        <input type="tel" pattern="^\+?\d{1,15}$" class="form-control rounded-5" name="Mobile" id="mobile" value="+94" required>
                    </div>
                    
                    <!-- Amount Input -->
                    <div class="my-5">
                        <label for="email" class="form-label fw-bold">Enter Amount</label>
                        <input type="tel" class="form-control rounded-5" name="Amount" id="amount" placeholder="Rs.0.00" required>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid my-3 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary w-50 rounded-4">Proceed to Pay</button>
                    </div>
            
                    <!-- Back Button -->
                    <div class="d-grid my-3 d-flex justify-content-center">
                        <a href="MobileReloads.php" class="btn btn-success w-50 rounded-4">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- log in form end -->

    <!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

<!-- footer -->
<?php include "include/footer.php"; ?>
