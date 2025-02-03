
<!-- nav bar -->
<?php include "include/nav.php"; ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Galaxy | Bank Services</title>

    <!-- Bootstrap css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- custom css -->
    <link rel="stylesheet" href="../css/login.css">

</head>
<body>

    <div class="login-container mt-3">

            <!-- Login Form Section -->
            <div class="col-12 col-md-12 p-5 w-700 ">
                <h2 class="text-center register-title fw-bold py-3">Banking Services</h2>
                <form>
                    <div class="row">
                        <div class="col-md-6 my-3">
                            <label for="firstName" class="form-label fw-bold">Receiver's Account Number</label>
                            <input type="text" class="form-control rounded-5" name="firstName" id="firstName"">
                        </div>
                        <div class="col-md-6 my-3">
                            <label for="lastName" class="form-label fw-bold">Receiver's  Name</label>
                            <input type="text" class="form-control rounded-5" name="lastName" id="lastName">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 my-3">
                            <label for="firstName" class="form-label fw-bold">Bank</label>
                            <div class="dropdown show">
                                <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Select Your Bank
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" href="#">People's Bank</a>
                                    <a class="dropdown-item" href="#">Bank of ceyloan</a>
                                    <a class="dropdown-item" href="#">Sampath Bank</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 my-3">
                            <label for="lastName" class="form-label fw-bold">Branch</label>
                            <div class="dropdown show">
                                <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Select Your Branch
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" href="#">Kurunegala</a>
                                    <a class="dropdown-item" href="#">Colombo</a>
                                    <a class="dropdown-item" href="#">Maho</a>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 my-3">
                            <label for="password" class="form-label fw-bold">Sender’s Name</label>
                            <input type="password" class="form-control rounded-5" id="password">
                        </div>
                        <div class="col-md-6 my-3">
                            <label for="Sendernic" class="form-label fw-bold">Senders NIC</label>
                            <input type="text" class="form-control rounded-5" id="confirmPassword">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 my-3">
                            <label for="password" class="form-label fw-bold">Mobile Number</label>
                            <input type="text" class="form-control rounded-5" id="password" value="+94 ">
                        </div>
                        <div class="col-md-6 my-3">
                            <label for="Amount-bank" class="form-label fw-bold">Amount</label>
                            <input type="text" class="form-control rounded-5" id="confirmPassword">
                        </div>
                    </div>


                    <p class="text-center text-muted py-3">
                        ** There will be an additional Charge of Rs.50.00 for each transfer **
                    </p>

                    <div class="d-grid my-3 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary w-50 rounded-4">Proceed to Pay</button>
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

<!-- footer -->
<?php include "include/footer.php"; ?>