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
    $uprice = $_POST['uprice'];
    $qty = $_POST['qty'];
    $type = "Cash";
    $total = $uprice * $qty;
    $date = date("Y-m-d");

    // Insert data into the payments table
    $sql = "INSERT INTO `payments` (`Type`, `UPrice`, `Qty`, `Total`, `Date`)
            VALUES ('$type', '$uprice', '$qty', '$total', '$date')";

    if ($conn->query($sql) === TRUE) {
        $successMessage = "Payment added successfully!";
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
    <title>Add Payment</title>

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
        .left-rectangle h2 {
            margin-top: 0;
            text-align: center;
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
        form {
            display: flex;
            flex-direction: column;
        }
        .form-group {
            margin-bottom: 20px;
            margin-top: 20px;
            margin-left: 20px;
            margin-right: 20px;
            
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .button-container {
            display: flex;
            justify-content: space-between;
        }
        .button-container button,
        .button-container a {
            width: 48%;
            padding: 10px;
            background-color: #472BE9;
            color: white;
            border: none;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            text-decoration: none;
        }
        .button-container a {
            background-color: #007bff;
        }
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
            text-align: center;
        }
        #successPopup button {
            margin-top: 10px;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
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
        <a href="user.php">Users</a>
        <a href="earnings.php"  class="current">Earnings</a>
    </div>
    <a href="login.php">
        <button class="logout-button">
            <img src="img/logout.png" alt="Logout">Logout
        </button>
    </a>
</div>

<div class="right-rectangle">
    <h2>Add Payment</h2>

    <div id="successPopup">
        <p id="successMessage"></p>
        <button onclick="closePopup()">Close</button>
    </div>

    <form action="" method="POST">
        <div class="form-group">
            <label for="uprice">Unit Price:</label>
            <input type="number" id="uprice" name="uprice" required>
        </div>
        <div class="form-group">
            <label for="qty">Quantity:</label>
            <input type="number" id="qty" name="qty" required>
        </div>
        <div class="button-container">
            <button type="submit">Submit</button>
            <a href="Earnings.php">Back</a>
        </div>
    </form>
</div>

</body>
</html>
