<?php
session_start(); // Start session at the very top, before any HTML or output

// Database connection details
$servername = "localhost"; // or your database host
$username = "root"; // your database username
$password = ""; // your database password
$dbname = "shop"; // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Terminate if connection fails
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (!empty($email) && !empty($password)) {
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

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/login.css">
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<!-- footer -->
<?php include "include/footer.php"; ?>
