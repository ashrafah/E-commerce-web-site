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

    <div class="container login-container">
        <div class="row mt-5">

            <!-- logo section start -->

            <div class="col-0 col-md-5 p-5 d-flex align-items-center justify-content-center rounded-3 logo-color">
                <img src="#" alt="Finance image" class="logo-log h-300">
            </div>

            <!-- logo section end -->

            <!-- Login Form Section -->
            <div class="col-12 col-md-7 p-5">
                <h2 class="text-center register-title fw-bold">Finance</h2>
                <form>
                    
                    <div class="my-3">
                        <label for="vehical-number" class="form-label fw-bold">Enter Vehical Number</label>
                        <input type="text" class="form-control rounded-5" name="ve-num" id="ve-num" placeholder="Enter your Vehical Number">
                    </div>
                    
                    <div class="my-3">
                        <label for="email" class="form-label fw-bold">Enter Owner name</label>
                        <input type="email" class="form-control rounded-5" name="o-name" id="o-name" placeholder="Enter your email">
                    </div>

                    <div class="my-3">
                        <label for="phone-number" class="form-label fw-bold">Enter Phone Number</label>
                        <input type="text" class="form-control rounded-5" name="phoneNumber" id="phoneNumber" value="+94">
                    </div>

                    <div class="my-3">
                        <label for="amount" class="form-label fw-bold">Enter Amount</label>
                        <input type="number" class="form-control rounded-5" name="Amount" id="Amount" placeholder="Rs.0">
                    </div>
                    
                    
                    <div class="d-grid my-3 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary w-50 rounded-4">Proceed to Pay</button>
                    </div>
            
                    <div class="d-grid my-3 d-flex justify-content-center">
                        <button type="submit" class="btn btn-success w-50 rounded-4">Back</button>
                    </div>
                </form>
            </div>
          
        </div>
      </div>
    <!-- log in form end -->
    



    <!-- bootstrap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    

</body>
</html>