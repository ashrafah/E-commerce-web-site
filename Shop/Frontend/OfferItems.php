<?php
// Include DB connection
require_once __DIR__ . "/../db/db.php";

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch offer items (where Price is different from OPrice)
$sqlItems = "SELECT IID, Name, Price, OPrice, Image_1 FROM items WHERE Price != OPrice";
$resultItems = $conn->query($sqlItems);
?>

<!-- Include Navbar -->
<?php include "include/nav.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exclusive Offers</title>
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
            color: #FFFFFF !important;
        }
        .item-price {
            font-size: 1rem;
            font-weight: bold;
            color: #FFD700;
            margin-top: 5px;
        }
        .discounted-price {
            font-size: 1rem;
            color: red;
            text-decoration: line-through;
            margin-right: 10px;
        }
        .original-price {
            font-size: 1.3rem;
            font-weight: bold;
            color: #FFD700;
        }
        a {
            text-decoration: none !important;
            color: inherit !important;
        }
        a:hover {
            text-decoration: none !important;
            color: #FFFFFF !important;
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
    <h1>Exclusive Offers</h1>
    <br>
    <div class="container">
        <div class="row align-items-stretch">
            <?php
            if ($resultItems->num_rows > 0) {
                while($row = $resultItems->fetch_assoc()) {
                    $image = base64_encode($row['Image_1']);
                    $itemName = $row['Name'];
                    $oPrice = number_format($row['OPrice'], 2);
                    $price = number_format($row['Price'], 2);
                    $itemID = $row['IID']; 

                    echo '<div class="col-md-3 col-sm-6 col-12">';
                    echo '<a href="product.php?item_id=' . $itemID . '">';
                    echo '<div class="category-box">';
                    echo '<div class="image-container">';
                    echo '<img src="data:image/jpeg;base64,' . $image . '" alt="' . $itemName . '">';
                    echo '</div>';
                    echo '<div class="item-details">';
                    echo '<div class="item-name">' . $itemName . '</div>';
                    echo '<div class="item-price">';
                    echo '<span class="discounted-price">Rs. ' . $price . '</span>';
                    echo '</br>';
                    echo '<span class="original-price">Rs. ' . $oPrice . '</span>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</a>';
                    echo '</div>';
                }
            } else {
                echo "<div class='text-center text-danger w-100'><h4>No offers available.</h4></div>";
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
