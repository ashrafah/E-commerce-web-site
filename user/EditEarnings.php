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

// Retrieve product ID (PID) from URL query string
if (isset($_GET['id'])) {
    $pid = $_GET['id'];

    // Fetch product details from the database
    $sql = "SELECT * FROM payments WHERE PID = $pid";
    $result = $conn->query($sql);
    $product = $result->fetch_assoc();

    if (!$product) {
        die("Product not found.");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $pid = $_POST['pid'];
    $uPrice = $_POST['uPrice'];
    $qty = $_POST['qty'];

    // Calculate total
    $total = $uPrice * $qty;

    // Update product data in the database
    $sql = "UPDATE payments SET 
                `UPrice` = '$uPrice', 
                `Qty` = '$qty', 
                `Total` = '$total' 
            WHERE PID = $pid";

    if ($conn->query($sql) === TRUE) {
        $successMessage = "Earnings updated successfully!";
        $isSuccess = true;
    } else {
        $successMessage = "Error: " . $sql . "<br>" . $conn->error;
        $isSuccess = false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Earnings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
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
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
        }

        .form-actions button,
        .form-actions a {
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

        .update-btn {
            background-color: #28a745;
        }

        .back-btn {
            background-color: #007bff;
        }

        .success-message {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #28a745;
            border-radius: 5px;
            background-color: #d4edda;
            color: #155724;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="left-rectangle">
        <h2>Edit Earnings</h2>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="products.php" >Products</a>
            <a href="categories.php">Category</a>
            <a href="user.php">Users</a>
            <a href="earnings.php" class="current">Earnings</a>
        </div>
        <a href="login.php"><button class="logout-button">Logout</button></a>
    </div>

    <div class="right-rectangle">
        <h2>Edit Earning</h2>

        <?php if ($isSuccess): ?>
            <div class="success-message">
                <?php echo $successMessage; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="hidden" name="pid" value="<?php echo $product['PID']; ?>">

            <div class="form-group">
                <label for="uPrice">Unit Price:</label>
                <input type="number" id="uPrice" name="uPrice" value="<?php echo $product['UPrice']; ?>" required>
            </div>

            <div class="form-group">
                <label for="qty">Quantity:</label>
                <input type="number" id="qty" name="qty" value="<?php echo $product['Qty']; ?>" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="update-btn">Update</button>
                <a href="Earnings.php" class="back-btn">Back</a>
            </div>
        </form>
    </div>
</body>
</html>
