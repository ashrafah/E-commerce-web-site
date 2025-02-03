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

// Select the CID for 'Mobile Phones' from categories table
$sqlCategory = "SELECT CID FROM categories WHERE Name = 'Mobile Phones'";
$resultCategory = $conn->query($sqlCategory);

$categoryID = null;
if ($resultCategory->num_rows > 0) {
    $category = $resultCategory->fetch_assoc();
    $categoryID = $category['CID']; // Get the CID for Mobile Phones
}

// Fetch items data from the items table for the selected category where Price = OPrice
$sqlItems = "SELECT IID, Name, Price, OPrice, Image_1 FROM items WHERE CID = $categoryID AND Price = OPrice";
$resultItems = $conn->query($sqlItems);
?>

<!-- nav bar -->
<?php include "include/nav.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Phones</title>
    <style>
        body {
            background-color: #F3EFF7;
            font-family: Arial, sans-serif;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .container {
            margin: 50px 100px;
            background-color: #F3EFF7;
            border-radius: 10px;
            padding: 20px;
            box-sizing: border-box;
            overflow: hidden;
        }
        .category-box {
            display: inline-block;
            width: 23%;
            margin: 10px;
            background: linear-gradient(to bottom, #9B7EBD, #3B1E54);
            border-radius: 10px;
            padding: 10px;
            text-align: center;
            box-sizing: border-box;
            overflow: hidden;
            height: 350px; /* fixed height for the box */
        }
        .category-box img {
            width: 100%;
            height: 200px; /* fixed height for the image */
            object-fit: cover; /* ensures the image is properly cropped */
            border-radius: 10px;
        }
        .category-name {
            margin-top: 20px;
            font-weight: bold;
            color: #fff;
        }
        .price {
            color: #fff;
            margin-top: 10px;
            font-size: 1.2em;
        }
        @media screen and (max-width: 768px) {
            .category-box {
                width: 45%;
            }
        }
        @media screen and (max-width: 480px) {
            .category-box {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <h1>Mobile Phones</h1>
    <div class="container">
        <?php
        if ($resultItems->num_rows > 0) {
            // Output data of each item
            while($row = $resultItems->fetch_assoc()) {
                // Convert BLOB image to base64 encoding for displaying the image
                $image = base64_encode($row['Image_1']);
                $itemName = $row['Name'];
                $price = $row['Price'];

                echo '<div class="category-box">';
                echo '<img src="data:image/jpeg;base64,' . $image . '" alt="' . $itemName . '">';
                echo '<div class="category-name">' . $itemName . '</div>';
                echo '<div class="price">Price: $' . $price . '</div>';
                echo '</div>';
            }
        } else {
            echo "No mobile phones found with matching prices.";
        }
        ?>
    </div>
</body>
</html>

<!-- footer -->
<?php include "include/footer.php"; ?>

<?php
$conn->close();
?>
