<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Select the CID for 'Mobile Accessories' from categories table
$sqlCategory = "SELECT CID FROM categories WHERE Name = 'Mobile Accessories'";
$resultCategory = $conn->query($sqlCategory);

$categoryID = null;
if ($resultCategory->num_rows > 0) {
    $category = $resultCategory->fetch_assoc();
    $categoryID = $category['CID']; // Get the CID for Mobile Accessories
}

// Fetch items data from the items table for the selected category
$sqlItems = "SELECT IID, Name, Price, OPrice, Image_1 FROM items WHERE CID = $categoryID";
$resultItems = $conn->query($sqlItems);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Accessories</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <h1 class="text-center my-4 text-dark">Mobile Accessories</h1>
    <div class="container">
        <div class="row">
            <?php
            if ($resultItems->num_rows > 0) {
                // Output data of each item
                while($row = $resultItems->fetch_assoc()) {
                    // Convert BLOB image to base64 encoding for displaying the image
                    $image = base64_encode($row['Image_1']);
                    $itemName = $row['Name'];
                    $price = $row['Price'];
                    $oprice = $row['OPrice'];
                    $iid = $row['IID']; // Get the product ID

                    // Display the item inside a Bootstrap card component
                    echo '<div class="col-md-3 col-sm-6 mb-4 d-flex">';
                    echo '<a href="Product.php?id=' . $iid . '" class="text-decoration-none w-100">';
                    echo '<div class="card h-100" style="background: linear-gradient(to bottom, #9B7EBD, #3B1E54);">';
                    echo '<img src="data:image/jpeg;base64,' . $image . '" alt="' . $itemName . '" class="card-img-top img-fluid" style="height: 250px; object-fit: cover;">';
                    echo '<div class="card-body d-flex flex-column justify-content-center align-items-center text-white">';
                    echo '<h5 class="card-title text-center mb-2">' . $itemName . '</h5>';
                    echo '<p class="card-text text-center mb-0">' . "Rs. ". $oprice . ".00" . '</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '</a>';
                    echo '</div>';
                }
            } else {
                echo "<p>No mobile Accessories found.</p>";
            }
            ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
