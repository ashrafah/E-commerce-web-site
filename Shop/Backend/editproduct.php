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
    $itemID = $_POST['itemID'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $oprice = $_POST['oprice'];
    $warranty = $_POST['warranty'];
    $qty = $_POST['qty'];
    $description = $_POST['description'];

    // Handle image uploads (Image_1 to Image_5)
    $imageFields = ['image_1', 'image_2', 'image_3', 'image_4', 'image_5'];
    $images = [];
    
    // Retrieve current images if not updating
    $sql = "SELECT `Image_1`, `Image_2`, `Image_3`, `Image_4`, `Image_5` FROM `items` WHERE `IID` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $itemID);
    $stmt->execute();
    $stmt->bind_result($current_image_1, $current_image_2, $current_image_3, $current_image_4, $current_image_5);
    $stmt->fetch();
    $stmt->close();

    // Check if an image is uploaded, else keep current image
    foreach ($imageFields as $imageField) {
        if (isset($_FILES[$imageField]) && $_FILES[$imageField]['error'] == 0) {
            $images[$imageField] = file_get_contents($_FILES[$imageField]['tmp_name']);
        } else {
            // Keep the current image if no new image is uploaded
            $images[$imageField] = ${'current_' . $imageField}; // Accessing current image dynamically
        }
    }

    // Update the product in the database
    $sql = "UPDATE `items` SET `Name` = ?, `Price` = ?, `OPrice` = ?, `Warranty` = ?, `Qty` = ?, `Description` = ?, 
            `Image_1` = ?, `Image_2` = ?, `Image_3` = ?, `Image_4` = ?, `Image_5` = ? WHERE `IID` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sdidissssssi', $name, $price, $oprice, $warranty, $qty, $description, 
        $images['image_1'], $images['image_2'], $images['image_3'], $images['image_4'], $images['image_5'], $itemID);
    
    if ($stmt->execute()) {
        $isSuccess = true;
    } else {
        $isSuccess = false;
    }

    $stmt->close();
}

// Fetch product details
if (isset($_GET['id'])) {
    $itemID = $_GET['id'];
    $sql = "SELECT `Name`, `Price`, `OPrice`, `Warranty`, `Qty`, `Description`, `Image_1`, `Image_2`, `Image_3`, `Image_4`, `Image_5` FROM `items` WHERE `IID` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $itemID);
    $stmt->execute();
    $stmt->bind_result($name, $price, $oprice, $warranty, $qty, $description, $image_1, $image_2, $image_3, $image_4, $image_5);
    $stmt->fetch();
    $stmt->close();
} else {
    header("Location: products.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
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

        .category-image {
            width: 150px;
            height: 120px;
            display: block;
            margin-bottom: 10px;
        }
    </style>

    <script>
        window.onload = function() {
            <?php if (isset($isSuccess) && $isSuccess): ?>
                $('#successModal').modal('show'); // Trigger the modal to show
                setTimeout(function() {
                    $('#successModal').modal('hide'); // Hide the modal after 3 seconds
                    window.location.href = 'products.php'; // Redirect to products page
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
            <a href="products.php" class="nav-link active">Products</a>
            </br>
            <a href="categories.php" class="nav-link">Category</a>
            </br>
            <a href="user.php" class="nav-link">Users</a>
            </br>
            <a href="earnings.php" class="nav-link">Earnings</a>
            </br>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>

        <!-- Right Content -->
        <div class="container form-container">
            <h2>Edit Product</h2>

            <!-- Edit Product Form -->
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="itemID" value="<?php echo $itemID; ?>">

                <div class="form-group">
                    <label for="name">Product Name:</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>" required>
                </div>

                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="text" id="price" name="price" class="form-control" value="<?php echo htmlspecialchars($price); ?>" required>
                </div>

                <div class="form-group">
                    <label for="oprice">Original Price:</label>
                    <input type="text" id="oprice" name="oprice" class="form-control" value="<?php echo htmlspecialchars($oprice); ?>" required>
                </div>

                <div class="form-group">
                    <label for="warranty">Warranty:</label>
                    <input type="text" id="warranty" name="warranty" class="form-control" value="<?php echo htmlspecialchars($warranty); ?>" required>
                </div>

                <div class="form-group">
                    <label for="qty">Quantity:</label>
                    <input type="number" id="qty" name="qty" class="form-control" value="<?php echo htmlspecialchars($qty); ?>" required>
                </div>

                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" class="form-control" required><?php echo htmlspecialchars($description); ?></textarea>
                </div>

                <div class="form-group">
                    <label>Current Images:</label><br>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <div class="form-group">
                            <label for="image_<?php echo $i; ?>">Image <?php echo $i; ?>:</label><br>
                            <?php
                            $image = ${"image_$i"};
                            if ($image): ?>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($image); ?>" class="img-thumbnail" width="150" height="120" alt="Image <?php echo $i; ?>"><br><br>
                            <?php else: ?>
                                <p>No image available</p>
                            <?php endif; ?>
                            <input type="file" id="image_<?php echo $i; ?>" name="image_<?php echo $i; ?>" class="form-control-file" accept="image/*">
                        </div>
                    <?php endfor; ?>
                </div>

                <button type="submit" class="btn btn-primary">Update Product</button>
                <a href="products.php" class="btn btn-secondary">Back</a>
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
                    Product updated successfully!
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
