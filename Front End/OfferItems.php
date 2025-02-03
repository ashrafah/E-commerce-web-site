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

// Fetch items data where Price is not equal to OPrice (Offer Price)
$sqlItems = "SELECT IID, Name, Price, OPrice, Image_1 FROM items WHERE Price != OPrice";
$resultItems = $conn->query($sqlItems);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offer Items</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <h1 class="text-center my-4 text-dark">Offer Items</h1>
    
    <div class="container">
        <div class="row">
            <?php
            if ($resultItems->num_rows > 0) {
                while ($row = $resultItems->fetch_assoc()) {
                    // Convert BLOB image to base64 encoding for displaying the image
                    $image = base64_encode($row['Image_1']);
                    $itemName = $row['Name'];
                    $price = $row['Price'];
                    $oprice = $row['OPrice'];
                    $iid = $row['IID']; // ✅ Defined correctly

                    // Display each item inside a Bootstrap card
                    echo '<div class="col-md-4 col-sm-6 mb-4">';
                    echo '<a href="Product.php?id=' . $iid . '" class="text-decoration-none">';
                    echo '<div class="card h-100 text-center text-white" style="background: linear-gradient(to bottom, #9B7EBD, #3B1E54);">';
                    echo '<div class="p-3">'; // Padding around the image
                    echo '<img src="data:image/jpeg;base64,' . $image . '" alt="' . $itemName . '" class="card-img-top img-fluid" style="height: 250px; object-fit: cover; border-radius: 10px;">';
                    echo '</div>';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . $itemName . '</h5>';
                    echo '<p class="text-decoration-line-through text-danger fw-bold">Rs. ' . number_format($price, 2) . '</p>';
                    echo '<p class="text-white fw-bold bg-success p-2 d-inline-block rounded">Now: Rs. ' . number_format($oprice, 2) . '</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '</a>';
                    echo '</div>';
                }
            } else {
                echo "<p class='text-center'>No Offer Items found.</p>";
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
