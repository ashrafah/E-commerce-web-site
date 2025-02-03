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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Styling the card */
        .card {
            background: linear-gradient(to bottom, #9B7EBD, #3B1E54);
            border-radius: 10px;
            overflow: hidden; /* Ensures everything stays inside the card */
            padding: 10px; /* Creates spacing inside the card */
        }

        /* Ensuring spacing around the image without adding a background */
        .category-img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 10px; /* Keeps rounded corners */
            padding: 10px; /* Adds spacing without affecting layout */
            background: transparent; /* Ensures transparency */
        }

        .card-body {
            text-align: center;
            color: white;
        }
    </style>
</head>
<body class="bg-light">
    <h1 class="text-center my-4 text-dark">Category</h1>
    <div class="container">
        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $image = base64_encode($row['Image']);
                    $categoryName = $row['Name'];
                    $sanitizedCategoryName = str_replace(' ', '', $categoryName);
                    
                    echo '<div class="col-md-4 col-sm-6 mb-4">';
                    echo '<a href="' . $sanitizedCategoryName . '.php" class="text-decoration-none">';
                    echo '<div class="card h-100">';
                    echo '<img src="data:image/jpeg;base64,' . $image . '" alt="' . $row['Name'] . '" class="category-img">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . $row['Name'] . '</h5>';
                    echo '</div>';
                    echo '</div>';
                    echo '</a>';
                    echo '</div>';
                }
            } else {
                echo "No categories found.";
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
