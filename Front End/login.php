<?php
session_start(); // Start session

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Initialize error message
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $_SESSION['email_input'] = $email; // Store email in session
    $_SESSION['password_input'] = $password; // Store password in session (temporary)

    if (!empty($email) && !empty($password)) {
        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT UID, FirstName, LastName, Password FROM users WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();

            // Plaintext comparison (for now, use password_verify() if storing hashed passwords)
            if ($password === $row['Password']) { 
                // Set session variables
                $_SESSION['user_id'] = $row['UID'];
                $_SESSION['first_name'] = $row['FirstName'];

                // Remove stored login attempt
                unset($_SESSION['email_input']);
                unset($_SESSION['password_input']);

                // Redirect to index.php
                header("Location: index.php");
                exit();
            } else {
                $_SESSION['error'] = "Invalid email or password.";
            }
        } else {
            $_SESSION['error'] = "No account found with that email.";
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Please enter both email and password.";
    }

    header("Location: login.php"); // Reload login page with error message
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Galaxy | Log in</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>

    <!-- Login Form Start -->
    <div class="container login-container">
        <div class="row mt-5">
            <!-- Login Form Section -->
            <div class="col-12 col-md-7 p-5">
                <h2 class="text-center login-title my-4 fw-bold">LOGIN</h2>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger text-center"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="my-4">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <input type="email" class="form-control mt-2 rounded-4" name="email" id="email" 
                               placeholder="Enter your email" required
                               value="<?php echo isset($_SESSION['email_input']) ? $_SESSION['email_input'] : ''; ?>">
                    </div>
                    
                    <div class="my-4">
                        <label for="password" class="form-label fw-bold">Password</label>
                        <input type="password" class="form-control mt-2 rounded-4" name="password" id="password" 
                               placeholder="Enter your password" required
                               value="<?php echo isset($_SESSION['password_input']) ? $_SESSION['password_input'] : ''; ?>">
                    </div>

                    <div class="my-4 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary w-50 rounded-4">Login</button>
                    </div>
                    <p class="text-center">Don’t have an account? <a href="#" class="register-link text-decoration-none text-danger">Register</a></p>
                </form>
            </div>

            <!-- Logo Section -->
            <div class="col-12 col-md-5 p-5 d-flex align-items-center justify-content-center rounded-3 logo-color">
                <img src="../assets/Footer/footer-icon.png" alt="Online Galaxy Logo" class="logo-log h-300">
            </div>
        </div>
    </div>
    <!-- Login Form End -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
