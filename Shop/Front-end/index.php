<!-- Include Navbar -->
<?php include "include/nav.php"; ?>

<?php
// Include DB connection
require_once __DIR__ . "/../db/db.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch Categories (limit to 9)
$sqlCategories = "SELECT * FROM categories LIMIT 9";
$resultCategories = $conn->query($sqlCategories);

// Fetch Offer Items (Price != OPrice)
$sqlOffers = "SELECT IID, Name, Price, OPrice, Image_1 FROM items WHERE Price < OPrice ORDER BY RAND() LIMIT 9";
$resultOffers = $conn->query($sqlOffers);

// Fetch New Arrivals (latest Date)
$sqlNewArrivals = "SELECT IID, Name, Price, OPrice, Image_1 FROM items ORDER BY Date DESC LIMIT 9";
$resultNewArrivals = $conn->query($sqlNewArrivals);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Galaxy</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

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
        .section-heading {
            position: sticky;
            top: 120px; /* Adjust based on navbar height */
            background: white;
            z-index: 10;
            padding: 15px 0;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            border-bottom: 2px solid #9B7EBD;
        }
        .card {
            background: linear-gradient(to bottom, #9B7EBD, #3B1E54);
            border-radius: 12px;
            text-align: center;
            padding: 15px;
            overflow: hidden;
            color: #fff;
        }
        .image-container img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 10px;
        }
        .item-name {
          margin-top : 10px;
          margin-bottom : 10px;
            font-size: 1rem;
            font-weight: bold;
            text-align: center;
            color: #fff !important;
        }
        .item-price .original-price {
            text-decoration: line-through;
            color: red;
            font-size: 0.9rem; /* Smaller font for the original price */
        }

        .item-price .discounted-price {
            font-size: 1.2rem;
            font-weight: bold;
            color: #FFD700;
        }

        .see-more {
            text-align: center;
            font-weight: bold;
            font-size: 1.2rem;
            padding: 40px;
            color: #FFD700;
            cursor: pointer;
            border-radius: 12px;
        }
    </style>

</head>
<body>

    <!-- Carousel Section -->
    <div class="container mt-5">
        <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active"><img src="../assets/index/JAD Banners 1.jpg" class="d-block w-100"></div>
                <div class="carousel-item"><img src="../assets/index/JAD Banners 2.jpg" class="d-block w-100"></div>
                <div class="carousel-item"><img src="../assets/index/JAD Banners 3.jpg" class="d-block w-100"></div>
                <div class="carousel-item"><img src="../assets/index/JAD Banners 4.jpg" class="d-block w-100"></div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </div>

    

    <!-- Categories Section -->
    <div class="container mt-5">
        <h1 class="section-heading">Categories</h1>
        <div class="row">
            <?php
            $count = 0;
            while ($row = $resultCategories->fetch_assoc()) {
                if ($count == 8) break;
                echo '<div class="col-md-4 mb-3"><a href="categories.php" class="text-decoration-none"><div class="card">';
                echo '<div class="image-container"><img src="data:image/jpeg;base64,' . base64_encode($row['Image']) . '"></div>';
                echo '<div class="item-name">' . $row['Name'] . '</div>';
                echo '</div></a></div>';
                $count++;
            }
            ?>

    <!-- Offers Section -->
<div class="container mt-5">
    <h1 class="section-heading">Offers</h1>
    <div class="row align-items-stretch">
        <?php
        // Fetch Offer Items
        $sqlOffers = "SELECT IID, Name, Price, OPrice, Image_1 FROM items WHERE Price != OPrice LIMIT 9";
        $resultOffers = $conn->query($sqlOffers) or die("SQL Error: " . $conn->error);

        $count = 0;
        if ($resultOffers->num_rows > 0) {
            while ($row = $resultOffers->fetch_assoc()) {
                if ($count == 8) break; // Show only up to 8 items, last slot reserved for "See More"
                $image = base64_encode($row['Image_1']);
                $itemName = $row['Name'];
                $price = number_format($row['Price'], 2);
                $oPrice = number_format($row['OPrice'], 2);
                $itemID = $row['IID']; 

                echo '<div class="col-md-4 col-sm-6 col-12 mb-3">';  // Adjusted to 3 items per row
                echo '<a href="product.php?item_id=' . $itemID . '">';
                echo '<div class="card">';
                echo '<div class="image-container">';
                echo '<img src="data:image/jpeg;base64,' . $image . '" alt="' . $itemName . '">';
                echo '</div>';
                echo '<div class="item-details">';
                echo '<div class="item-name">' . $itemName . '</div>';
                echo '<div class="item-price">';
                
                echo '<span class="original-price">Rs. ' . $price . '</span><br>';
                echo '<span class="discounted-price">Rs. ' . $oPrice . '</span>';
                echo '</div>';

                echo '</div>';
                echo '</div>';
                echo '</a>';
                echo '</div>';

                $count++;
            }

            // Add a "See More" button in the last slot
            echo '<div class="col-md-4 col-sm-6 col-12 mb-3">';
            echo '<a href="OfferItems.php">';
            echo '<div class="card d-flex align-items-center justify-content-center" style="height: 100%;">';
            echo '<div class="see-more">...See More</div>';
            echo '</div>';
            echo '</a>';
            echo '</div>';
        } else {
            echo "<div class='col-12 text-center text-danger'><h4>No offers available at the moment.</h4></div>";
        }
        ?>
    </div>
</div>

<!-- New Arrivals Section -->
<div class="container mt-5">
    <h1 class="section-heading">New Arrivals</h1>
    <div class="row">
        <?php
        $count = 0;
        while ($row = $resultNewArrivals->fetch_assoc()) {
            if ($count == 8) break; // Show only up to 8 items, last slot reserved for "See More"
            echo '<div class="col-md-4 mb-3"><a href="NewArrivals.php" class="text-decoration-none"><div class="card">';
            echo '<div class="image-container"><img src="data:image/jpeg;base64,' . base64_encode($row['Image_1']) . '"></div>';
            echo '<div class="item-name">' . $row['Name'] . '</div>';
            echo '</div></a></div>';
            $count++;
        }
        ?>
        <!-- Add a "See More" button for New Arrivals -->
        <div class="col-md-4 mb-3">
            <a href="NewArrivals.php" class="text-decoration-none">
                <div class="card d-flex align-items-center justify-content-center" style="height: 100%;">
                    <div class="see-more">...See More</div>
                </div>
            </a>
        </div>
    </div>
</div>

</html>

<!-- Include Footer -->
<?php include "include/footer.php"; ?>

<?php $conn->close(); ?>
