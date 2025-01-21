<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$isSuccess = false;
$successMessage = "";

// Retrieve user ID from URL query string
if (isset($_GET['id'])) {
    $uid = $_GET['id'];

    // Fetch user details from the database
    $sql = "SELECT * FROM users WHERE UID = $uid";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();

    if (!$user) {
        die("User not found.");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $uid = $_POST['uid'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $mobileNo = $_POST['mobileNo'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $permanentAddress = $_POST['permanentAddress'];
    $permanentTown = $_POST['permanentTown'];
    $shippingAddress = $_POST['shippingAddress'];
    $shippingTown = $_POST['shippingTown'];

    // Validate confirm password
    if ($password !== $confirmPassword) {
        $successMessage = "Passwords do not match.";
        $isSuccess = false;
    } else {
        // Update user data in the database
        $sql = "UPDATE users SET 
                    `FirstName`='$firstName', 
                    `LastName`='$lastName', 
                    `MobileNo.`='$mobileNo', 
                    `UserName`='$username', 
                    `Email`='$email', 
                    `Password`='$password', 
                    `P.Address`='$permanentAddress', 
                    `P.Town`='$permanentTown', 
                    `S.Address`='$shippingAddress', 
                    `S.Town`='$shippingTown' 
                WHERE UID=$uid";

        if ($conn->query($sql) === TRUE) {
            $successMessage = "User updated successfully!";
            $isSuccess = true;
        } else {
            $successMessage = "Error: " . $sql . "<br>" . $conn->error;
            $isSuccess = false;
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <style>
        
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .left-rectangle {
            background-color: #E7E0EF;
            position: fixed;
            top: 50px;
            left: 50px;
            bottom: 50px;
            width: 200px;
            padding: 20px;
            box-sizing: border-box;
            border-radius: 15px;
        }

        .right-rectangle {
            background-color: #E7E0EF;
            margin: 50px;
            margin-left: 300px;
            padding: 20px;
            box-sizing: border-box;
            border-radius: 15px;
            width: calc(100% - 350px);
        }

        .left-rectangle h2, .right-rectangle h2 {
            margin-top: 0;
            text-align: center;
        }

        .logout-button {
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 10px 20px;
            background-color: #D9534F;
            color: white;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            text-align: center;
        }

        .logout-button img {
            width: 16px;
            height: 16px;
            margin-right: 10px;
        }

        .nav-links a {
            display: block;
            margin: 15px 0;
            text-decoration: none;
            color: white;
            text-align: center;
            padding: 10px;
            background-color: #472BE9;
            border-radius: 15px;
        }

        .nav-links a.current {
            background-color: #2BBDE9;
        }

        /* Styling for the Add User Form */
        form {
            display: flex;
            flex-wrap: wrap;
            width: 100%;
            margin: 0 auto;
            padding: 50px;
            box-sizing: border-box;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            width: 100%;
            margin-bottom: 20px;
        }

        .form-group label {
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        /* Styling for buttons */
        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            width: 100%;
        }

        .button-container button, .button-container a {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            width: 48%;
        }

        .back-button {
            background-color: #007bff;
        }



        /* Success popup styling */
        #successPopup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        #successPopup button,
        #successPopup a {
            margin-top: 10px;
            background-color: #007bff;
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #successPopup button {
            background-color: #28a745;
        }
    </style>
    <script>
        window.onload = function() {
            <?php if (isset($isSuccess) && $isSuccess): ?>
                showSuccessMessage("<?php echo $successMessage; ?>");
            <?php endif; ?>
        };

        function showSuccessMessage(message) {
            const popup = document.getElementById("successPopup");
            const successMessage = document.getElementById("successMessage");
            successMessage.innerText = message;
            popup.style.display = "block";
        }

        function closePopup() {
            const popup = document.getElementById("successPopup");
            popup.style.display = "none";
        }
    </script>
</head>
<body>
<div class="left-rectangle">
    <h2>Dashboard</h2>
    <div class="nav-links">
        <a href="dashboard.php">Dashboard</a>
        <a href="products.php">Products</a>
        <a href="categories.php">Category</a>
        <a href="user.php" class="current">Users</a>
        <a href="earnings.php">Earnings</a>
    </div>
    <a href="login.php"><button class="logout-button">
        <img src="img/logout.png" alt="Logout">Logout
    </button></a>
</div>

<div class="right-rectangle">
    <h2>Edit User</h2>

    <!-- Success Message Popup -->
    <div id="successPopup">
        <p id="successMessage"></p>
        <button onclick="closePopup()">Close</button>
        <a href="user.php">Back to Users</a>
    </div>

    <!-- Edit User Form -->
    <form action="" method="POST" onsubmit="return validateForm()">

        <input type="hidden" name="uid" value="<?php echo $user['UID']; ?>">

        <div class="form-group">
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" value="<?php echo $user['FirstName']; ?>" required>
        </div>

        <div class="form-group">
            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" value="<?php echo $user['LastName']; ?>" required>
        </div>

        <div class="form-group">
            <label for="mobileNo">Mobile No:</label>
            <input type="text" id="mobileNo" name="mobileNo" value="<?php echo $user['MobileNo.']; ?>" required>
        </div>

        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo $user['UserName']; ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $user['Email']; ?>" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" value="<?php echo $user['Password']; ?>" required>
        </div>

        <div class="form-group">
            <label for="confirmPassword">Confirm Password:</label>
            <input type="password" id="confirmPassword" name="confirmPassword" value="<?php echo $user['Password']; ?>" required>
        </div>

        <div class="form-group">
            <label for="permanentAddress">Permanent Address:</label>
            <input type="text" id="permanentAddress" name="permanentAddress" value="<?php echo $user['P.Address']; ?>" required>
        </div>

        <div class="form-group">
            <label for="permanentTown">Permanent Town:</label>
            <input type="text" id="permanentTown" name="permanentTown" value="<?php echo $user['P.Town']; ?>" required>
        </div>

        <div class="form-group">
            <label for="shippingAddress">Shipping Address:</label>
            <input type="text" id="shippingAddress" name="shippingAddress" value="<?php echo $user['S.Address']; ?>" required>
        </div>

        <div class="form-group">
            <label for="shippingTown">Shipping Town:</label>
            <input type="text" id="shippingTown" name="shippingTown" value="<?php echo $user['S.Town']; ?>" required>
        </div>

        <div class="button-container">
            <button type="submit">Update User</button>
            <a href="user.php" class="back-button">Back</a>
        </div>
    </form>
</div>
</body>
</html>
