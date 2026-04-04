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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $categoryName = $_POST['categoryName'];

    // Handle the uploaded image
    if (isset($_FILES['categoryImage']) && $_FILES['categoryImage']['error'] == 0) {
        $categoryImage = $_FILES['categoryImage']['tmp_name']; // Temporary file path
        $imageData = file_get_contents($categoryImage);
    } else {
        $imageData = null;
    }

    // Insert data into the database
    $sql = "INSERT INTO `categories` (`Name`, `Image`) VALUES (?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sb', $categoryName, $null);
    
    if ($imageData) {
        $stmt->send_long_data(1, $imageData);
    }

    if ($stmt->execute()) {
        $successMessage = "Category added successfully!";
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
    <title>Add Category</title>
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
            margin-top: 50px;
            padding: 30px;
            border-radius: 15px;
            background-color: #E7E0EF;
        }
    </style>

    <script>
        window.onload = function() {
            <?php if (isset($isSuccess) && $isSuccess): ?>
                $('#successModal').modal('show'); // Trigger the modal to show
                setTimeout(function() {
                    $('#successModal').modal('hide'); // Hide the modal
                    window.location.href = 'categories.php'; // Redirect to categories page
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
            <a href="categories.php" class="nav-link active">Category</a>
            </br>
            <a href="user.php" class="nav-link">Users</a>
            </br>
            <a href="earnings.php" class="nav-link">Earnings</a>
            </br>
            <a href="logout.php" class="logout-btn">Logout</a>
            
        </div>

        <!-- Right Content -->
        <div class="container form-container">
            <h2 class="text-center">Add Category</h2>

            <!-- Add Category Form -->
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="categoryName">Category Name:</label>
                    <input type="text" id="categoryName" name="categoryName" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="categoryImage">Category Image:</label>
                    <input type="file" id="categoryImage" name="categoryImage" class="form-control-file" accept="image/*" required>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success w-50">Submit</button>
                    <a href="categories.php" class="btn btn-primary w-50 text-center">Back</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Success Modal (No buttons, auto-closes after 3 seconds) -->
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
