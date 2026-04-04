<?php
// Include DB connection
require_once __DIR__ . "/../db/db.php";

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

// Fetch items data from the items table for the selected category where Price = OPrice
$sqlItems = "SELECT IID, Name, Price, OPrice, Image_1 FROM items WHERE CID = $categoryID AND Price = OPrice";
$resultItems = $conn->query($sqlItems);
?>

<!-- Include Navbar -->
<?php include "include/nav.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Accessories</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #F3EFF7;
            font-family: Arial, sans-serif;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .category-box {
            background: linear-gradient(to bottom, #9B7EBD, #3B1E54);
            border-radius: 12px;
            text-align: center;
            box-sizing: border-box;
            min-height: 460px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 15px;
            transition: transform 0.2s ease-in-out, box-shadow 0.3s;
            margin-bottom: 30px;
            cursor: pointer;
        }
        .category-box:hover {
            transform: scale(1.05);
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.2);
        }
        .row {
            row-gap: 40px;
            display: flex;
            align-items: stretch;
        }
        .image-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 220px;
            overflow: hidden;
            border-radius: 10px;
        }
        .image-container img {
            max-width: 100%;
            height: auto;
            object-fit: contain;
            border-radius: 10px;
            display: block;
        }
        .item-details {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .item-name {
            font-size: 1rem;
            font-weight: bold;
            text-align: center;
            padding: 5px;
            max-width: 90%;
            max-height: 80px;
            overflow: hidden;
            white-space: normal;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            color: #FFFFFF !important; /* ✅ Fix: Ensure text is white */
        }
        .item-price {
            font-size: 1.3rem;
            font-weight: bold;
            color: #FFD700;
            margin-top: 5px;
        }
        a {
            text-decoration: none !important; /* ✅ Fix: Completely removes underline */
            color: inherit !important; /* Ensures it does not inherit blue link color */
        }
        a:hover {
            text-decoration: none !important;
            color: #FFFFFF !important; /* Ensures hover does not change color */
        }
        @media (max-width: 768px) {
            .category-box {
                min-height: 350px;
            }
        }
    </style>
</head>
<body>
    <br>
    <h1>Mobile Accessories</h1>
    <br>
    <div class="container">
        <div class="row align-items-stretch">
            <?php
            if ($resultItems->num_rows > 0) {
                while($row = $resultItems->fetch_assoc()) {
                    $image = base64_encode($row['Image_1']);
                    $itemName = $row['Name'];
                    $oPrice = number_format($row['OPrice'], 2);
                    $itemID = $row['IID']; 

                    echo '<div class="col-md-3 col-sm-6 col-12">';
                    echo '<a href="product.php?item_id=' . $itemID . '">';
                    echo '<div class="category-box">';
                    echo '<div class="image-container">';
                    echo '<img src="data:image/jpeg;base64,' . $image . '" alt="' . $itemName . '">';
                    echo '</div>';
                    echo '<div class="item-details">';
                    echo '<div class="item-name">' . $itemName . '</div>';
                    echo '<div class="item-price">RS. ' . $oPrice . '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</a>';
                    echo '</div>';
                }
            } else {
                echo "<div class='text-center text-danger w-100'><h4>No mobile accessories found.</h4></div>";
            }
            ?>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<!-- Include Footer -->
<?php include "include/footer.php"; ?>

<?php
$conn->close();
?>
