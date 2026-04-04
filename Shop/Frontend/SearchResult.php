<?php
// Include DB connection
require_once __DIR__ . "/../db/db.php";

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user search query
$searchQuery = isset($_GET['query']) ? trim($_GET['query']) : "";
$searchQuery = $conn->real_escape_string($searchQuery);

// Fetch matching items
$sqlItems = "SELECT IID, Name, Price, OPrice, Image_1 FROM items WHERE Name LIKE '%$searchQuery%'";
$resultItems = $conn->query($sqlItems);
?>

<!-- Include Navbar -->
<?php include "include/nav.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results for "<?php echo htmlspecialchars($searchQuery); ?>"</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* Gradient card styling */
        .category-box {
            background: linear-gradient(to bottom, #9B7EBD, #3B1E54);
            border-radius: 12px;
            transition: transform 0.2s ease-in-out, box-shadow 0.3s;
            overflow: hidden;
        }
        .category-box:hover {
            transform: scale(1.05);
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4">
        <h1 class="text-center text-dark fw-bold mb-4">Search Results for "<?php echo htmlspecialchars($searchQuery); ?>"</h1>
        
        <div class="row g-4">
            <?php
            if ($resultItems->num_rows > 0) {
                while ($row = $resultItems->fetch_assoc()) {
                    $image = base64_encode($row['Image_1']);
                    $itemName = $row['Name'];
                    $oPrice = number_format($row['OPrice'], 2);
                    $itemID = $row['IID'];
            ?>
            
            <div class="col-md-3 col-sm-6 col-12">
                <a href="product.php?item_id=<?= $itemID ?>" class="text-decoration-none">
                    <div class="card category-box text-white text-center p-3">
                        <img src="data:image/jpeg;base64,<?= $image ?>" class="card-img-top img-fluid rounded" alt="<?= htmlspecialchars($itemName) ?>" style="height: 220px; object-fit: contain;">
                        <div class="card-body">
                            <h5 class="card-title text-truncate"><?= htmlspecialchars($itemName) ?></h5>
                            <p class="fs-5 fw-bold text-warning mb-0">RS. <?= $oPrice ?></p>
                        </div>
                    </div>
                </a>
            </div>
            
            <?php 
                } // End while loop
            } else { 
            ?>
                <div class="col-12 text-center">
                    <h4 class="text-danger">No items found for "<?= htmlspecialchars($searchQuery) ?>"</h4>
                </div>
            <?php 
            } 
            ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<!-- Include Footer -->
<?php include "include/footer.php"; ?>

<?php
$conn->close();
?>
