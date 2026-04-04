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

// Fetch categories from the database
$categories = [];
$categoryQuery = "SELECT CID, Name FROM categories WHERE Name IN ('Mobile Phones', 'Mobile Accessories')";
$result = $conn->query($categoryQuery);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $qty = $_POST['qty'];
    $oprice = isset($_POST['no_offer']) ? $price : $_POST['oprice'];
    $description = $_POST['description'];
    $cid = $_POST['cid'];
    $warranty = $_POST['warranty'];
    $sold = $_POST['sold'] ? $_POST['sold'] : 0;
    $date = date('Y-m-d'); // Set current date

    $images = [];
    for ($i = 1; $i <= 5; $i++) {
        $imageField = "image$i";
        if (isset($_FILES[$imageField]) && $_FILES[$imageField]['error'] == 0) {
            $images[] = file_get_contents($_FILES[$imageField]['tmp_name']);
        } else {
            $images[] = null;
        }
    }

    // Insert data into database
    $sql = "INSERT INTO items (Image_1, Image_2, Image_3, Image_4, Image_5, Name, Price, Qty, Description, CID, OPrice, Warranty, Sold, Date)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Preparation failed: " . $conn->error);
    }

    $stmt->bind_param(
        'bbbbbsddsdsdss',
        $images[0], $images[1], $images[2], $images[3], $images[4],
        $name, $price, $qty, $description, $cid, $oprice, $warranty, $sold, $date
    );

    for ($i = 0; $i < 5; $i++) {
        if ($images[$i]) {
            $stmt->send_long_data($i, $images[$i]);
        }
    }

    if ($stmt->execute()) {
        $successMessage = "Item added successfully!";
        $isSuccess = true;
    } else {
        $successMessage = "Error: " . $stmt->error;
        $isSuccess = false;
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
    <title>Add Item</title>
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

        .image-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-top: 10px;
        }
    </style>

    <script>
        function setOfferPrice() {
            const price = document.getElementById('price').value;
            const oprice = document.getElementById('oprice');
            const opriceLabel = document.querySelector("label[for='oprice']");
            const checkbox = document.getElementById('no_offer');

            if (checkbox.checked && price) {
                oprice.value = price;
                oprice.readOnly = true;
                oprice.style.display = 'none';
                opriceLabel.style.display = 'none';
            } else {
                oprice.readOnly = false;
                oprice.style.display = 'block';
                opriceLabel.style.display = 'block';
            }
        }

        // Image preview function
        function previewImages(input, previewId) {
            const preview = document.getElementById(previewId);
            const files = input.files;

            // Clear existing previews
            preview.innerHTML = '';

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();

                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('image-preview');
                    preview.appendChild(img);
                };

                reader.readAsDataURL(file);
            }
        }

        window.onload = function() {
            <?php if (isset($isSuccess) && $isSuccess): ?>
                $('#successModal').modal('show'); // Trigger the modal to show
                setTimeout(function() {
                    $('#successModal').modal('hide'); // Hide the modal
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
            <h2 class="text-center">Add New Item</h2>

            <!-- Add Category Form -->
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Item Name:</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="number" id="price" name="price" step="0.01" class="form-control" required>
                </div>
                <div>
                    <input type="checkbox" id="no_offer" name="no_offer" onclick="setOfferPrice()">
                    <label for="no_offer">No Offer</label>
                </div>
                <br>
                <div class="form-group">
                    <label for="oprice">Offer Price:</label>
                    <input type="number" id="oprice" name="oprice" step="0.01" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="warranty">Warranty (in months):</label>
                    <input type="number" id="warranty" name="warranty" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="qty">Quantity:</label>
                    <input type="number" id="qty" name="qty" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="sold">Sold:</label>
                    <input type="number" id="sold" name="sold" class="form-control" value="0">
                </div>
                <div class="form-group">
                    <label for="cid">Category:</label>
                    <select id="cid" name="cid" class="form-control" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['CID']; ?>"><?php echo $category['Name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Image Upload Fields -->
                <div class="form-group">
                    <label for="image1">Image 1 (Required):</label>
                    <input type="file" id="image1" name="image1" class="form-control-file" accept="image/*" onchange="previewImages(this, 'preview1')" required>
                    <div id="preview1"></div>
                </div>

                <div class="form-group">
                    <label for="image2">Image 2 (Required):</label>
                    <input type="file" id="image2" name="image2" class="form-control-file" accept="image/*" onchange="previewImages(this, 'preview2')" required>
                    <div id="preview2"></div>
                </div>

                <div class="form-group">
                    <label for="image3">Image 3 (Required):</label>
                    <input type="file" id="image3" name="image3" class="form-control-file" accept="image/*" onchange="previewImages(this, 'preview3')" required>
                    <div id="preview3"></div>
                </div>

                <div class="form-group">
                    <label for="image4">Image 4 (Optional):</label>
                    <input type="file" id="image4" name="image4" class="form-control-file" accept="image/*" onchange="previewImages(this, 'preview4')">
                    <div id="preview4"></div>
                </div>

                <div class="form-group">
                    <label for="image5">Image 5 (Optional):</label>
                    <input type="file" id="image5" name="image5" class="form-control-file" accept="image/*" onchange="previewImages(this, 'preview5')">
                    <div id="preview5"></div>
                </div>

                <button type="submit" class="btn btn-success w-50">Submit</button>
                <a href="products.php" class="btn btn-primary w-50 text-center">Back</a>
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
