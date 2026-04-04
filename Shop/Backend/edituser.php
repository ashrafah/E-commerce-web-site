<?php
// Include DB connection
require_once __DIR__ . "/../db/db.php";

session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: ../Front-end/login.php"); // Redirect if not logged in as admin
    exit();
}

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the user ID from the URL
if (isset($_GET['id'])) {
    $uid = $_GET['id'];

    // Fetch the user data from the database
    $sql = "SELECT * FROM users WHERE UID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $uid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "User not found!";
        exit();
    }
}

// Handle form submission (update the user)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $mobileNo = $_POST['mobileNo'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $permanentAddress = $_POST['permanentAddress'];
    $permanentTown = $_POST['permanentTown'];
    $shippingAddress = $_POST['shippingAddress'];
    $shippingTown = $_POST['shippingTown'];

    // Update user data in the database
    $sql = "UPDATE users SET `FirstName` = ?, `LastName` = ?, `MobileNo.` = ?, `UserName` = ?, `Email` = ?, Password = ?, 
            `P.Address` = ?, `P.Town` = ?, `S.Address` = ?, `S.Town` = ? WHERE UID = ?";
    $stmt = $conn->prepare($sql);
    
    // Bind parameters to the prepared statement
    $stmt->bind_param('ssssssssssi', $firstName, $lastName, $mobileNo, $username, $email, $password, $permanentAddress, $permanentTown, $shippingAddress, $shippingTown, $uid);

    if ($stmt->execute()) {
        $successMessage = "User updated successfully!";
        $isSuccess = true;
    } else {
        $successMessage = "Error: " . $stmt->error;
        $isSuccess = false;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #F8F8FF;
        }

        .sidebar {
            background-color: #E7E0EF;
            width: 250px;
            height: 100vh;
            position: fixed;
            padding: 20px;
            border-radius: 15px;
        }

        .sidebar h2 {
            text-align: center;
        }

        .nav-link {
            color: white !important;
            background-color: #472BE9;
            border-radius: 15px;
            margin-bottom: 10px;
            padding: 10px;
            text-align: center;
        }

        .nav-link.active {
            background-color: #2BBDE9;
        }

        .logout-btn {
            background-color: #D9534F;
            color: white;
            border-radius: 15px;
            text-align: center;
            padding: 10px;
            display: block;
            margin-top: auto;
        }

        .form-container {
            margin-left: 300px;
            margin-right: 50px;
            margin-top: 50px;
            padding: 30px;
            border-radius: 15px;
            background-color: #E7E0EF;
        }
    </style>

    <script>
        window.onload = function() {
            <?php if (isset($isSuccess) && $isSuccess): ?>
                $('#successModal').modal('show'); // Show the modal when the form is submitted successfully
                setTimeout(function() {
                    $('#successModal').modal('hide'); // Hide the modal after 3 seconds
                    window.location.href = 'user.php'; // Redirect to user.php page
                }, 3000); // Auto-close after 3 seconds
            <?php endif; ?>
        };
    </script>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar d-flex flex-column">
            <h2>Dashboard</h2>
            </br>
            </br>
            <a href="dashboard.php" class="nav-link">Dashboard</a>
            </br>
            <a href="products.php" class="nav-link">Products</a>
            </br>
            <a href="categories.php" class="nav-link">Category</a>
            </br>
            <a href="user.php" class="nav-link active">Users</a>
            </br>
            <a href="earnings.php" class="nav-link">Earnings</a>
            </br>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>

        <!-- Right Content -->
        <div class="container form-container">
            <h2 class="text-center">Edit User</h2>

            <!-- Add User Form -->
            <form action="" method="POST">
                <div class="form-group">
                    <label for="firstName">First Name:</label>
                    <input type="text" id="firstName" name="firstName" class="form-control" value="<?php echo $user['FirstName']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name:</label>
                    <input type="text" id="lastName" name="lastName" class="form-control" value="<?php echo $user['LastName']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="mobileNo">Mobile No:</label>
                    <input type="text" id="mobileNo" name="mobileNo" class="form-control" value="<?php echo $user['MobileNo.']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" class="form-control" value="<?php echo $user['UserName']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo $user['Email']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" class="form-control" value="<?php echo $user['Password']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="permanentAddress">Permanent Address:</label>
                    <input type="text" id="permanentAddress" name="permanentAddress" class="form-control" value="<?php echo $user['P.Address']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="permanentTown">Permanent Town:</label>
                    <input type="text" id="permanentTown" name="permanentTown" class="form-control" value="<?php echo $user['P.Town']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="shippingAddress">Shipping Address:</label>
                    <input type="text" id="shippingAddress" name="shippingAddress" class="form-control" value="<?php echo $user['S.Address']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="shippingTown">Shipping Town:</label>
                    <input type="text" id="shippingTown" name="shippingTown" class="form-control" value="<?php echo $user['S.Town']; ?>" required>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success w-50">Update</button>
                    <a href="user.php" class="btn btn-primary w-50 text-center">Back</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Success</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php if (isset($successMessage)) echo $successMessage; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
