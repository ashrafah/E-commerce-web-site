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

// Fetch categories data from the database
$sql = "SELECT * FROM categories";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
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
            width: 30%;
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
            height: 250px; /* fixed height for the image */
            object-fit: cover; /* ensures the image is properly cropped */
            border-radius: 10px;
        }
        .category-name {
            margin-top: 30px;
            font-weight: bold;
            color: #fff;
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
    <h1>Category</h1>
    <div class="container">
        <?php
        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                // Convert BLOB to base64 encoding for displaying the image
                $image = base64_encode($row['Image']);
                $categoryName = $row['Name'];
                // Remove spaces from category name (no encoding needed)
                $sanitizedCategoryName = str_replace(' ', '', $categoryName);
                echo '<a href="' . $sanitizedCategoryName . '.php">';
                echo '<div class="category-box">';
                echo '<img src="data:image/jpeg;base64,' . $image . '" alt="' . $row['Name'] . '">';
                echo '<div class="category-name">' . $row['Name'] . '</div>';
                echo '</div>';
                echo '</a>';
            }
        } else {
            echo "No categories found.";
        }
        ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>
