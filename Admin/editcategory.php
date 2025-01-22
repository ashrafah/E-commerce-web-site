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
    $categoryID = $_POST['categoryID'];
    $categoryName = $_POST['categoryName'];

    // Handle the uploaded image
    if (isset($_FILES['categoryImage']) && $_FILES['categoryImage']['error'] == 0) {
        $categoryImage = $_FILES['categoryImage']['tmp_name']; // Temporary file path

        // Read the image file content as a binary string
        $imageData = file_get_contents($categoryImage);
    } else {
        // If no new image is uploaded, use the existing image data
        $imageData = null;
    }

    // Update data in the database
    if ($imageData) {
        // Update with the new image
        $sql = "UPDATE `categories` SET `Name` = ?, `Image` = ? WHERE `CID` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sbi', $categoryName, $null, $categoryID);
        $stmt->send_long_data(1, $imageData);
    } else {
        // Update without the image
        $sql = "UPDATE `categories` SET `Name` = ? WHERE `CID` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $categoryName, $categoryID);
    }

    if ($stmt->execute()) {
        $successMessage = "Category updated successfully!";
        $isSuccess = true;
    } else {
        $successMessage = "Error: " . $stmt->error;
        $isSuccess = false;
    }

    $stmt->close();
}

// Fetch category details if editing an existing category
if (isset($_GET['id'])) {
    $categoryID = $_GET['id'];
    $sql = "SELECT `Name`, `Image` FROM `categories` WHERE `CID` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $categoryID);
    $stmt->execute();
    $stmt->bind_result($categoryName, $categoryImage);
    $stmt->fetch();
    $stmt->close();
} else {
    // Redirect to categories page if no ID is provided
    header("Location: categories.php");
    exit();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Category</title>

    <style>
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

        /* Styling for the Add Category Form */
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
            <a href="categories.php" class="current">Category</a>
            <a href="user.php">Users</a>
            <a href="earnings.php">Earnings</a>
        </div>
        <a href="login.php"><button class="logout-button">
            <img src="img/logout.png" alt="Logout">Logout
        </button></a>
    </div>

    <div class="right-rectangle">
        <h2>Update Category</h2>

        <!-- Success Message Popup -->
        <div id="successPopup">
            <p id="successMessage"></p>
            <button onclick="closePopup()">Close</button>
            <a href="categories.php">Back to Categories</a>
        </div>

        <!-- Update Category Form -->
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="categoryID" value="<?php echo $categoryID; ?>">

            <div class="form-group">
                <label for="categoryName">Category Name:</label>
                <input type="text" id="categoryName" name="categoryName" value="<?php echo htmlspecialchars($categoryName); ?>" required>
            </div>

            <div class="form-group">
                <label for="categoryImage">Category Current Image:</label>
                <?php if ($categoryImage): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($categoryImage); ?>" alt="Category Image" width="100">
                <?php endif; ?>
                <input type="file" id="categoryImage" name="categoryImage" accept="image/*">
            </div>

            <div class="button-container">
                <button type="submit">Update</button>
                <a href="categories.php" class="back-button">Back</a>
            </div>
        </form>
    </div>
</body>
</html>
