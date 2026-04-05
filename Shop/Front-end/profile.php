<?php
// Start the session to access session data
session_start();
error_reporting(0);

// Check if UID is set in the session
if (!isset($_SESSION['user_id'])) {
    echo "You need to log in first.";
    exit();
}

// Include DB connection
require_once __DIR__ . "/../db/db.php";

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch the UID from the session
$uid = $_SESSION['user_id'];

// Query the database to get user details
$query = "SELECT * FROM users WHERE UID = '$uid'";
$result = mysqli_query($conn, $query);

// Check if the query was successful and if the user exists
if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
} else {
    echo "User not found.";
    exit();
}
?>

<!-- Include Navbar -->
<?php include "include/nav.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
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



        .log-in-container {
            background: #E7E0EF;
            max-width: 900px;
        }
        .profile-pic {
            width: 120px;
            height: 120px;
        }
        .profile-pic i {
            font-size: 50px;
            color: white;
        }
    </style>
        
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="log-in-container py-2 shadow rounded-4 w-100">
            <div class="row align-items-start m-3">
                
                <!-- Profile Picture -->
                <div class="col-md-3 text-center">
                    <div class="profile-pic ms-5 rounded-circle d-flex align-items-center justify-content-center bg-primary">
                        <i class="bi bi-person-fill"></i>
                    </div>
                </div>

                <!-- Input Fields -->
                <div class="col-md-9 ">
                    <form action="" method="">

                        <div class="row my-4">
                            <label class="col-md-4 col-form-label fw-light text-start">First Name:</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control rounded-5" name="firstname" value="<?php echo $user['FirstName']; ?>" readonly>
                            </div>
                        </div>

                        <div class="row my-4">
                            <label class="col-md-4 col-form-label fw-light text-start">Last Name:</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control rounded-5" name="lastname" value="<?php echo $user['LastName']; ?>" readonly>
                            </div>
                        </div>

                        <div class="row my-4">
                            <label class="col-md-4 col-form-label fw-light text-start">Permanent Address:</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control rounded-5 mb-2" name="Address" value="<?php echo $user['P.Address']; ?>" readonly>
                            </div>
                        </div>

                        <div class="row my-4">
                            <label class="col-md-4 col-form-label fw-light text-start">Permanent Town:</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control rounded-5" name="Town" value="<?php echo $user['P.Town']; ?>" readonly>
                            </div>
                        </div>

                        <div class="row my-4">
                            <label class="col-md-4 col-form-label fw-light text-start">E-mail:</label>
                            <div class="col-md-8">
                                <input type="email" class="form-control rounded-5" name="Email" value="<?php echo $user['Email']; ?>" readonly>
                            </div>
                        </div>

                        <div class="row my-4">
                            <label class="col-md-4 col-form-label fw-light text-start">Mobile Number:</label>
                            <div class="col-md-8">
                                <input type="number" class="form-control rounded-5" name="Mobile-number" value="<?php echo $user['MobileNo.']; ?>" readonly>
                            </div>
                        </div>

                        <!-- Shipping Address and Town -->
                        <div class="row my-4">
                            <label class="col-md-4 col-form-label fw-light text-start">Shipping Address:</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control rounded-5" name="shipping_address" value="<?php echo $user['S.Address']; ?>" readonly>
                            </div>
                        </div>

                        <div class="row my-4">
                            <label class="col-md-4 col-form-label fw-light text-start">Shipping Town:</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control rounded-5" name="shipping_town" value="<?php echo $user['S.Town']; ?>" readonly>
                            </div>
                        </div>
                        
                    </form>

                    <div class="col-md-12 d-flex mt-5">
                        <div class="col-md-6">
                            <!-- Change Password Button -->
                            <a href="change-password.php" class="btn btn-primary w-75">Change Password</a>
                        </div>
                        <div class="col-md-6">
                            <!-- Edit Details Button -->
                            <a href="edit-profile.php" class="btn btn-primary w-75">Edit Details</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

<!-- Include Footer -->
<?php include "include/footer.php"; ?>
