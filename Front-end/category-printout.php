<!-- nav bar -->
<?php include "include/nav.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Galaxy | Printout</title>

    <!-- Bootstrap css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- custom css -->
    <link rel="stylesheet" href="../css/login.css">

</head>
<body>

    <div class="login-container">
        <div class="row mt-5">

            <!-- Login Form Section -->
            <div class="col-12 col-md-12 p-5">
                <h2 class="text-center register-title fw-bold">PrintOuts</h2>
                <form>
                    <div class="row">
                        <div class="col-md-6 my-3">
                            <label for="firstName" class="form-label fw-bold">Date</label>
                            <input type="text" class="form-control rounded-5" name="firstName" id="firstName">
                        </div>
                        <div class="col-md-6 my-3">
                            <label for="lastName" class="form-label fw-bold">Number of copies</label>
                            <input type="number" class="form-control rounded-5" name="lastName" id="lastName">
                        </div>
                    </div>
                    <div class="my-3">
                        <label for="email" class="form-label fw-bold">Title</label>
                        <input type="email" class="form-control rounded-5" name="email" id="email" placeholder="Enter your email">
                    </div>
                    
                    <div class="my-3">
                        <label for="email" class="form-label fw-bold">Description</label>
                        <input type="text" class="form-control rounded-5" name="email" id="email">
                    </div>

                    <div class="my-3">
                        <label for="email" class="form-label fw-bold">Upload Your Document:</label>
                        <input type="file" name="Document" id="Document">
                    </div>

                    <div class="d-grid my-3 d-flex justify-content-center pt-5">
                        <button type="submit" class="btn btn-primary w-50 rounded-4">Submit</button>
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