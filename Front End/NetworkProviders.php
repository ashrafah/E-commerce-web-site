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
$sql = "SELECT * FROM networkproviders";
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
        .networkproviders-box {
            display: inline-flex; /* Changed to flex for alignment */
            flex-direction: column; /* Ensures content stacks vertically */
            justify-content: center; /* Vertical alignment */
            align-items: center; /* Horizontal alignment */
            width: 30%;
            margin-top: 10px;
            margin-left: 10px;
            margin-right: 10px;
            background: linear-gradient(to bottom, #9B7EBD, #3B1E54);
            border-radius: 10px;
            padding: 10px;
            text-align: center;
            box-sizing: border-box;
            overflow: hidden;
            align: center;
        }
        .networkproviders-box img {
           /* Fixed height for the image */
            object-fit: cover; /* Ensures the image is properly cropped */
            border-radius: 10px;
        }
        @media screen and (max-width: 768px) {
            .networkproviders-box {
                width: 45%;
            }
        }
        @media screen and (max-width: 480px) {
            .networkproviders-box {
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
            while ($row = $result->fetch_assoc()) {
                // Convert BLOB to base64 encoding for displaying the image
                $image = base64_encode($row['Image']);
                $networkproviders = $row['Name'];
                // Remove spaces from category name (no encoding needed)
                $sanitizednetworkproviderName = str_replace(' ', '', $networkproviders);
                echo '<a href="' . $sanitizednetworkproviderName . '.php">';
                echo '<div class="networkproviders-box">';
                echo '<img src="data:image/jpeg;base64,' . $image . '" alt="' . $row['Name'] . '">';
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
