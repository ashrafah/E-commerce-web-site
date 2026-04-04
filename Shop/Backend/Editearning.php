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

// Fetch data from the payments table to pre-fill the form
if (isset($_GET['id'])) {
    $paymentID = $_GET['id'];

    // Fetch the existing payment details
    $sql = "SELECT PID, UPrice, Qty, Total, Type, Date FROM payments WHERE PID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $paymentID);
    $stmt->execute();
    $stmt->bind_result($pid, $uPrice, $qty, $total, $type, $date);
    $stmt->fetch();
    $stmt->close();
} else {
    header("Location: earnings.php");
    exit();
}

// Update the payment details when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $unitPrice = $_POST['unitPrice'];
    $quantity = $_POST['quantity'];
    $total = $unitPrice * $quantity; // Recalculate total
    $type = 'Cash'; // Type is fixed as 'Cash'
    $paymentID = $_POST['paymentID']; // Hidden field to hold payment ID

    // Update the payment in the database
    $sql = "UPDATE payments SET UPrice = ?, Qty = ?, Total = ?, Type = ? WHERE PID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('diisi', $unitPrice, $quantity, $total, $type, $paymentID);

    if ($stmt->execute()) {
        // Show success message in a popup
        echo "<script>
                window.onload = function() {
                    $('#successPopup').modal('show');
                    setTimeout(function() {
                        $('#successPopup').modal('hide');
                        window.location.href = 'earnings.php';
                    }, 3000);
                }
              </script>";
    } else {
        echo "Error updating payment: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Payment</title>
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

</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar d-flex flex-column">
            <h2>Dashboard</h2>
            </br>
            <a href="dashboard.php" class="nav-link">Dashboard</a>
            <a href="products.php" class="nav-link">Products</a>
            <a href="categories.php" class="nav-link">Category</a>
            <a href="user.php" class="nav-link">Users</a>
            <a href="earnings.php" class="nav-link active">Earnings</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>

        <!-- Right Content -->
        <div class="container form-container">
            <h2>Edit Payment</h2>

            <!-- Edit Payment Form -->
            <form action="" method="POST">
                <input type="hidden" name="paymentID" value="<?php echo $pid; ?>">

                <div class="form-group">
                    <label for="unitPrice">Unit Price (Rs.):</label>
                    <input type="number" id="unitPrice" name="unitPrice" class="form-control" value="<?php echo htmlspecialchars($uPrice); ?>" required>
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" class="form-control" value="<?php echo htmlspecialchars($qty); ?>" required>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success w-50">Update Payment</button>
                    <a href="earnings.php" class="btn btn-secondary w-50">Back</a>
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
                    <p>Payment updated successfully!</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
