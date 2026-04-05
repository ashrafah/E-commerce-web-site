<?php
session_start(); // Start session at the very top, before any HTML or output
error_reporting(0);

// Include DB connection
require_once __DIR__ . "/../db/db.php";

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Terminate if connection fails
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (!empty($email) && !empty($password)) {
        
        // Admin login check
        if ($email === "admin@gmail.com" && $password === "admin123") {
            $_SESSION['admin'] = true;
            header("Location: ../Back-End/dashboard.php"); // Redirect to admin dashboard
            exit();
        }

        // Query database for user with the provided email
        $query = "SELECT * FROM users WHERE Email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if ($password === $user['Password']) { // Direct password comparison (Not secure)
                $_SESSION['user_email'] = $user['Email'];
                $_SESSION['user_name'] = $user['Name'];
                $_SESSION['user_id'] = $user['UID'];
                header("Location: index.php"); // Redirect to user dashboard
                exit();
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "No account found with this email.";
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<!-- nav bar -->
<?php include "include/nav.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Galaxy | Log in</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

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

    </style>

</head>
<body>

    <div class="login-container">
        <div class="row mt-5">
            <!-- Login Form Section -->
            <div class="col-12 col-md-7 p-5">
                <h2 class="text-center login-title my-4 fw-bold">LOGIN</h2>
                <?php if (isset($error)) { echo "<p class='text-danger text-center'>$error</p>"; } ?>
                <form method="POST" action="">
                    <div class="my-5">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <input type="email" class="form-control mt-2 rounded-4" name="email" id="email" placeholder="Enter your email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                    </div>
                    <div class="my-5">
                        <label for="password" class="form-label fw-bold">Password</label>
                        <input type="password" class="form-control mt-2 rounded-4" name="password" id="password" placeholder="Enter your password" required>
                    </div>
                    <div class="my-4 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary w-50 rounded-4">Login</button>
                    </div>
                    <p class="text-center">Don’t have an account? <a href="./register.php" class="text-decoration-none text-danger">Register</a></p>
                </form>
            </div>

            <!-- Logo Section -->
            <div class="col-0 col-md-5 p-5 d-flex align-items-center justify-content-center rounded-3 logo-color">
                <img src="../assets/Footer/footer-icon.png" alt="Online Galaxy Logo" class="logo-log h-300">
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<!-- footer -->
<?php include "include/footer.php"; ?>
