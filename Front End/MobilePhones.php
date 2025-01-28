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
$sqlCategory = "SELECT CID FROM categories WHERE Name = 'Mobile Phones'";
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
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .category-box {
            width: 23%; /* width of each box */
            margin: 10px;
            background: linear-gradient(to bottom, #9B7EBD, #3B1E54);
            border-radius: 10px;
            text-align: center;
            box-sizing: border-box;
            overflow: hidden;
            height: ; /* fixed height for the box */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .category-box img {
            margin-top: 10px;
            margin-bottom: 10px;
            margin-left: 10px;
            margin-right: 10px;
            object-fit: cover; /* ensures the image is properly cropped */
            border-radius: 10px;
        }
        .category-name {
            margin-top: 10px;
            margin-bottom: 10px;
            margin-left: 10px;
            margin-right: 10px;
            font-weight: bold;
            color: #fff;
            font-size: 12px;
            align: center;
        }


        .offer-price {
            margin-bottom: 10px;
            color: white;
            display: inline-block;
            color: white;
            background-color: gray; 
            padding: 5px;
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
    <h1>Mobile Accessories</h1>
    <div class="container">
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

                // Display the item inside a gradient rectangle
                echo '<a href="Product.php?id=' . $iid . '" class="category-box" style="text-decoration: none;">';
                echo '<img src="data:image/jpeg;base64,' . $image . '" alt="' . $itemName . '">';
                echo '<div class="category-name">' . $itemName . '</div>';
                echo '<div class="offer-price">' . "Rs. ". $oprice . ".00". '</div>';
                echo '</a>';
            }
        } else {
            echo "No mobile Accessories found.";
        }
        ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>
