<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check database connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);

    // Validate required fields
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($confirmPassword)) {
        $_SESSION['error'] = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
    } elseif ($password !== $confirmPassword) {
        $_SESSION['error'] = "Passwords do not match.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT Email FROM users WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $_SESSION['error'] = "Email is already registered.";
        } else {
            // Insert user into database
            $stmt = $conn->prepare("INSERT INTO users (FirstName, LastName, Email, Password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $firstName, $lastName, $email, $password);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Registration successful! You can now log in.";
            } else {
                $_SESSION['error'] = "Registration failed: " . $conn->error;
            }
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Galaxy | Register</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>

    <div class="container login-container">
        <div class="row mt-5">
            <!-- Logo Section -->
            <div class="col-12 col-md-5 p-5 d-flex align-items-center justify-content-center rounded-3 logo-color">
                <img src="../assets/Footer/footer-icon.png" alt="Online Galaxy Logo" class="logo-log h-300">
            </div>

            <!-- Register Form Section -->
            <div class="col-12 col-md-7 p-5">
                <h2 class="text-center register-title fw-bold">Register</h2>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger text-center"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success text-center"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 my-3">
                            <label for="firstName" class="form-label fw-bold">First Name</label>
                            <input type="text" class="form-control rounded-5" name="firstName" id="firstName" placeholder="First Name" required>
                        </div>
                        <div class="col-md-6 my-3">
                            <label for="lastName" class="form-label fw-bold">Last Name</label>
                            <input type="text" class="form-control rounded-5" name="lastName" id="lastName" placeholder="Last Name" required>
                        </div>
                    </div>
                    <div class="my-3">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <input type="email" class="form-control rounded-5" name="email" id="email" placeholder="Enter your email" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 my-3">
                            <label for="password" class="form-label fw-bold">Password</label>
                            <input type="password" class="form-control rounded-5" name="password" id="password" placeholder="Password" required>
                        </div>
                        <div class="col-md-6 my-3">
                            <label for="confirmPassword" class="form-label fw-bold">Confirm Password</label>
                            <input type="password" class="form-control rounded-5" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password" required>
                        </div>
                    </div>
                    <div class="d-grid my-3 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary w-50 rounded-4">Register</button>
                    </div>
                    <p class="text-center">
                        Already have an account? <a href="login.php" class="login-link text-decoration-none">Login</a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
