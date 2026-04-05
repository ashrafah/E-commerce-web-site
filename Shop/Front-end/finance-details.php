<?php
// Include DB connection
require_once __DIR__ . "/../db/db.php";

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch finance name and BPID using SID
    $sql = "SELECT Name, BPID FROM services WHERE SID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $SID);
    $stmt->execute();
    $stmt->bind_result($FiName, $BPID);
    $stmt->fetch();
    $stmt->close();
    $conn->close();
?>

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



        .logo-color {
            background-image: url('../assets/others/finance.png');
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
                <!-- <img src="#" alt="Finance image" class="logo-log h-300"> -->
            </div>
            <!-- logo section end -->

            <!-- Form Section -->
            <div class="col-12 col-md-7 p-5">
                <h2 class="text-center register-title fw-bold">Finance</h2>
                <form action="Payment.php" method="GET">

                    <!-- Hidden input for permanent parameter -->
                    <input type="hidden" name="Type" value="Finance">
                    
                    <!-- Finance Name Section -->
                    <div class="my-3">
                        <label for="finance-name" class="form-label fw-bold">Finance Name</label>
                        <input type="text" class="form-control rounded-5" id="finance-name" name="Name" value="<?php echo htmlspecialchars($FiName); ?>" readonly>
                    </div>

                    <!-- Vehicle Number Section -->
                    <div class="my-3">
                        <label for="vehical-number" class="form-label fw-bold">Enter Vehicle Number</label>
                        <input type="text" class="form-control rounded-5" name="Num" id="Num" placeholder="Enter your Vehicle Number">
                    </div>
                    
                    <!-- Owner Name Section -->
                    <div class="my-3">
                        <label for="o-name" class="form-label fw-bold">Enter Owner Name</label>
                        <input type="text" class="form-control rounded-5" name="Cname" id="Cname" placeholder="Enter your name">
                    </div>

                    <!-- Phone Number Section -->
                    <div class="my-3">
                        <label for="phone-number" class="form-label fw-bold">Enter Phone Number</label>
                        <input type="text" class="form-control rounded-5" name="Mobile" id="Mobile" value="+94">
                    </div>

                    <!-- Amount Section -->
                    <div class="my-3">
                        <label for="amount" class="form-label fw-bold">Enter Amount</label>
                        <input type="number" class="form-control rounded-5" name="Amount" id="Amount" placeholder="Rs.0">
                    </div>
                    
                    <!-- Proceed to Pay Button -->
                    <div class="d-grid my-3 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary w-50 rounded-4">Proceed to Pay</button>
                    </div>
            
                    <!-- Back Button -->
                    <div class="d-grid my-3 d-flex justify-content-center">
                        <a href="Finance.php?BPID=<?php echo htmlspecialchars($BPID); ?>" class="btn btn-success w-50 rounded-4">Back</a>
                    </div>
                </form>
            </div>
          
        </div>
      </div>
    <!-- Form End -->

        <!-- Bootstrap JS and dependencies -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

<!-- footer -->
<?php include "include/footer.php"; ?>
