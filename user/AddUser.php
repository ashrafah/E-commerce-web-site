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

    // Insert data into the database
    $sql = "INSERT INTO `users` (`FirstName`, `LastName`, `MobileNo.`, `UserName`, `Email`, `Password`, `P.Address`, `P.Town`, `S.Address`, `S.Town`)
            VALUES ('$firstName', '$lastName', '$mobileNo', '$username', '$email', '$password', '$permanentAddress', '$permanentTown', '$shippingAddress', '$shippingTown')";

    if ($conn->query($sql) === TRUE) {
        $successMessage = "User added successfully!";
        $isSuccess = true;
    } else {
        $successMessage = "Error: " . $sql . "<br>" . $conn->error;
        $isSuccess = false;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

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
            background-color:rgb(167, 40, 99);
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

        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .checkbox-group label {
            margin-left: 5px;
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

        function copyPermanentAddress(checkbox) {
            if (checkbox.checked) {
                document.getElementById("shippingAddress").value = document.getElementById("permanentAddress").value;
                document.getElementById("shippingTown").value = document.getElementById("permanentTown").value;
            } else {
                document.getElementById("shippingAddress").value = "";
                document.getElementById("shippingTown").value = "";
            }
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
    <h2>Add User</h2>

    <!-- Success Message Popup -->
    <div id="successPopup">
        <p id="successMessage"></p>
        <button onclick="closePopup()">Close</button>
        <a href="user.php">Back to Users</a>
    </div>

    <!-- Add User Form -->
    <form action="" method="POST" onsubmit="return validateForm()">
        <div class="form-group">
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" required>
        </div>
        <div class="form-group">
            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" required>
        </div>
        <div class="form-group">
            <label for="mobileNo">Mobile No:</label>
            <input type="text" id="mobileNo" name="mobileNo" required>
        </div>
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="confirmPassword">Confirm Password:</label>
            <input type="password" id="confirmPassword" name="confirmPassword" required>
        </div>
        <div class="form-group">
            <label for="permanentAddress">Permanent Address:</label>
            <input type="text" id="permanentAddress" name="permanentAddress" required>
        </div>
        <div class="form-group">
            <label for="permanentTown">Permanent Town:</label>
            <input type="text" id="permanentTown" name="permanentTown" required>
        </div>
        <div class="checkbox-group">
            <input type="checkbox" id="sameAsPermanent" onclick="copyPermanentAddress(this)">
            <label for="sameAsPermanent">Shipping address is same as permanent address</label>
        </div>

        <div class="form-group">
            <label for="shippingAddress">Shipping Address:</label>
            <input type="text" id="shippingAddress" name="shippingAddress" required>
        </div>
        <div class="form-group">
            <label for="shippingTown">Shipping Town:</label>
            <input type="text" id="shippingTown" name="shippingTown" required>
        </div>

        <div class="button-container">
            <button type="submit">Submit</button>
            <a href="user.php" class="back-button">Back</a>
        </div>
    </form>
</div>
</body>
</html>
