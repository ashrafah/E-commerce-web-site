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

if (isset($_GET['query'])) {
    $search = htmlspecialchars($_GET['query']); // Sanitize the input

    // Use a prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT IID, Name, OPrice, Image_1 FROM items WHERE Name LIKE ?");
    $likeSearch = $search . '%'; // Append wildcard
    $stmt->bind_param("s", $likeSearch);
    $stmt->execute();
    $resultItems = $stmt->get_result();
}

?>



<!-- navi bar -->



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Result</title>
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

        .Price {
            margin-top: 10px;
            text-decoration: line-through; 
            text-decoration-color: red;
        }

        .offer-price {
            margin-bottom: 10px;
        }

        .Price, .offer-price {
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
    <h1>Search Result</h1>
    <div class="container">

    </body>
</html>

        <?php
        if ($resultItems->num_rows > 0) {
            // Output data of each item
            while($row = $resultItems->fetch_assoc()) {
                // Convert BLOB image to base64 encoding for displaying the image
                $image = base64_encode($row['Image_1']);
                $itemName = $row['Name'];
                $oprice = $row['OPrice'];

                // Display the item inside a gradient rectangle
                echo '<div class="category-box">';
                echo '<img src="data:image/jpeg;base64,' . $image . '" alt="' . $itemName . '">';
                echo '<div class="category-name">' . $itemName . '</div>';
                echo '<div class="offer-price">' . "Rs. ". $oprice . ".00". '</div>';
                echo '</div>';
            }
        } else {
            echo "No mobile phones found with matching prices.";
        }
        ?>
    </div>



<?php
$conn->close();
?>
