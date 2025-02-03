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
    // Get form data
    $firstName = trim($_POST["firstName"]);
    $lastName = trim($_POST["lastName"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validate input
    if (!empty($firstName) && !empty($lastName) && !empty($email) && !empty($password)) {
        // Check if the email already exists
        $query = "SELECT * FROM users WHERE Email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "This email is already registered.";
        } else {
            // Insert new user data into the database
            $query = "INSERT INTO users (FirstName, LastName, Email, Password) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssss", $firstName, $lastName, $email, $password); // Using plain text password (not secure)
            
            if ($stmt->execute()) {
                $_SESSION['user_email'] = $email; // Set session variable
                header("Location: login.php"); // Redirect to login page after successful registration
                exit();
            } else {
                $error = "Error registering the user.";
            }
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
    <title>Online Galaxy | Register</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>

    <div class="login-container">
        <div class="row mt-5">

            <!-- logo section start -->

            <div class="col-0 col-md-5 p-5 d-flex align-items-center justify-content-center rounded-3 logo-color">
                <img src="../assets/Footer/footer-icon.png" alt="Online Galaxy Logo" class="logo-log h-300">
            </div>

            <!-- logo section end -->

            <!-- Register Form Section -->
            <div class="col-12 col-md-7 p-5">
                <h2 class="text-center register-title fw-bold">Register</h2>
                <?php if (isset($error)) { echo "<p class='text-danger text-center'>$error</p>"; } ?>
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6 my-3">
                            <label for="firstName" class="form-label fw-bold">First Name</label>
                            <input type="text" class="form-control rounded-5" name="firstName" id="firstName" placeholder="First Name" value="<?php echo isset($firstName) ? htmlspecialchars($firstName) : ''; ?>" required>
                        </div>
                        <div class="col-md-6 my-3">
                            <label for="lastName" class="form-label fw-bold">Last Name</label>
                            <input type="text" class="form-control rounded-5" name="lastName" id="lastName" placeholder="Last Name" value="<?php echo isset($lastName) ? htmlspecialchars($lastName) : ''; ?>" required>
                        </div>
                    </div>
                    <div class="my-3">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <input type="email" class="form-control rounded-5" name="email" id="email" placeholder="Enter your email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
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
                        Already have an account? <a href="./login.php" class="login-link text-decoration-none">Login</a>
                    </p>
                </form>
            </div>
          
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<!-- footer -->
<?php include "include/footer.php"; ?>
