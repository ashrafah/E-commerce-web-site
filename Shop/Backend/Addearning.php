<?php
// Include DB connection
require_once __DIR__ . "/../db/db.php";

session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: ../Front-end/login.php"); // Redirect if not logged in as admin
    exit();
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $unitPrice = $_POST['unitPrice'];
    $quantity = $_POST['quantity'];
    $total = $unitPrice * $quantity; // Calculate total
    $date = date('Y-m-d'); // Current date
    $type = 'Cash'; // Type is fixed as 'Cash'

    // Insert data into the payments table
    $sql = "INSERT INTO payments (UPrice, Qty, Total, Date, Type)
            VALUES ('$unitPrice', '$quantity', '$total', '$date', '$type')";

    if ($conn->query($sql) === TRUE) {
        $successMessage = "Earning added successfully!";
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
    <title>Add Earnings</title>
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
                showSuccessMessage("<?php echo $successMessage; ?>");
            <?php endif; ?>
        };

        function showSuccessMessage(message) {
            const popup = document.getElementById("successPopup");
            document.getElementById("successMessage").innerText = message;
            popup.style.display = "block";

            // Auto-close the modal after 3 seconds
            setTimeout(function() {
                popup.style.display = "none";
                window.location.href = 'earnings.php'; // Redirect to earnings page
            }, 3000);
        }
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
            <a href="user.php" class="nav-link">Users</a>
            </br>
            <a href="earnings.php" class="nav-link active">Earnings</a>
            </br>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>

        <!-- Right Content -->
        <div class="container form-container">
            <h2 class="text-center">Add Earnings</h2>

            <form action="" method="POST">
                <div class="form-group">
                    <label for="unitPrice">Unit Price (Rs.):</label>
                    <input type="number" id="unitPrice" name="unitPrice" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" class="form-control" required>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success w-50">Submit</button>
                    <a href="earnings.php" class="btn btn-primary w-50 text-center">Back</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Success Popup Modal -->
    <div id="successPopup" class="modal" tabindex="-1" role="dialog" style="display: none;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Success</h5>
                    <button type="button" class="close" data-dismiss="modal" onclick="closePopup()">&times;</button>
                </div>
                <div class="modal-body">
                    <p id="successMessage"></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
