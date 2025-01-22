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

// Fetch categories from the database
// Fetch categories from the database
$categories = [];
$categoryQuery = "SELECT CID, Name FROM categories WHERE Name IN ('Mobile Phones', 'Mobile Accessories')";
$result = $conn->query($categoryQuery);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row; // Store each category
    }
}


// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $price = $_POST['price'];
    $qty = $_POST['qty'];
    $oprice = isset($_POST['no_offer']) ? $price : $_POST['oprice']; // Set offer price
    $description = $_POST['description'];
    $cid = $_POST['cid'];
    $warranty = $_POST['warranty'];
    $sold = $_POST['sold'];

    // Handle uploaded images
    $images = [];
    for ($i = 1; $i <= 5; $i++) {
        $imageField = "image$i";
        if (isset($_FILES[$imageField]) && $_FILES[$imageField]['error'] == 0) {
            $images[] = file_get_contents($_FILES[$imageField]['tmp_name']); // Read image as binary data
        } else {
            $images[] = null; // Set null for optional images
        }
    }

    // Insert data into the database
    $sql = "INSERT INTO items (Image_1, Image_2, Image_3, Image_4, Image_5, Name, Price, Qty, Description, CID, OPrice, Warranty, Sold)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Preparation failed: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param(
        'bbbbbsddsdsii',
        $images[0],
        $images[1],
        $images[2],
        $images[3],
        $images[4],
        $name,
        $price,
        $qty,
        $description,
        $cid,
        $oprice,
        $warranty,
        $sold
    );

    // Send binary data for images
    for ($i = 0; $i < 5; $i++) {
        if ($images[$i]) {
            $stmt->send_long_data($i, $images[$i]);
        }
    }

    // Execute the query
    if ($stmt->execute()) {
        echo "<script>alert('Item added successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
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
    <style>
        /* Styling remains unchanged */
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
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input, 
        .form-group select, 
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        .back-button {
        display: inline-block;
        padding: 10px 20px;
        text-align: center;
        background-color: #007bff; /* Blue color */
        color: white;
        text-decoration: none; /* Remove underline */
        border-radius: 5px;
        margin-left: 10px; /* Add some spacing from the submit button */
        cursor: pointer;
        }

        .back-button:hover {
        background-color: #0056b3; /* Darker blue on hover */
        }
        </style>
</head>
<body>

<script>
    function setOfferPrice() {
        const price = document.getElementById('price').value;
        const oprice = document.getElementById('oprice');
        const opriceLabel = document.querySelector("label[for='oprice']");
        const checkbox = document.getElementById('no_offer');
        
        if (checkbox.checked && price) {
            oprice.value = price;
            oprice.readOnly = true;
            oprice.style.display = 'none'; // Hide Offer Price input
            opriceLabel.style.display = 'none'; // Hide Offer Price label
        } else {
            oprice.readOnly = false;
            oprice.style.display = 'block'; // Show Offer Price input
            opriceLabel.style.display = 'block'; // Show Offer Price label
        }
    }
</script>



    <!-- Left Rectangle -->
    <div class="left-rectangle">
        <h2>Dashboard</h2>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="products.php" class="current">Products</a>
            <a href="categories.php">Category</a>
            <a href="user.php">Users</a>
            <a href="earnings.php">Earnings</a>
        </div>
    </div>

    <!-- Right Rectangle -->
    <div class="right-rectangle">
        <h2>Add New Item</h2>

        <form action="" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="name">Item Name:</label>
        <input type="text" id="name" name="name" required>
    </div>
    <div class="form-group">
        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" required>
    </div>
    <div>
            <input type="checkbox" id="no_offer" name="no_offer" onclick="setOfferPrice()">
            <label for="no_offer">No Offer</label>
        </div>
</br>
    <div class="form-group">
        <label for="oprice">Offer Price:</label>
        <input type="number" id="oprice" name="oprice" step="0.01" required>

    </div>
    <div class="form-group">
        <label for="warranty">Warranty (in months):</label>
        <input type="number" id="warranty" name="warranty" required>
    </div>
    <div class="form-group">
        <label for="qty">Quantity:</label>
        <input type="number" id="qty" name="qty" required>
    </div>
    <div class="form-group">
        <label for="sold">Sold:</label>
        <input type="number" id="sold" name="sold" required>
    </div>
    <div class="form-group">
        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4" required></textarea>
    </div>
    <div class="form-group">
        <label for="cid">Category:</label>
        <select id="cid" name="cid" required>
            <option value="">Select Category</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['CID']; ?>"><?php echo $category['Name']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php for ($i = 1; $i <= 5; $i++): ?>
    <div class="form-group">
        <label for="image<?php echo $i; ?>">Image <?php echo $i; ?><?php echo $i <= 3 ? ' (Required)' : ' (Optional)'; ?>:</label>
        <input type="file" id="image<?php echo $i; ?>" name="image<?php echo $i; ?>" accept="image/*" <?php echo $i <= 3 ? 'required' : ''; ?>>
    </div>
    <?php endfor; ?>
    <button type="submit">Submit</button>
    <a href="products.php" class="back-button">Back</a>
</form>




    </div>
</body>
</html>
