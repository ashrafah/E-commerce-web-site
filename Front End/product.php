<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $current_date = date('Y-m-d H:i:s');

    $sql = "INSERT INTO cart (IID, Date) VALUES ('$product_id', '$current_date')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Product added to cart successfully.');</script>";
    } else {
        echo "<script>alert('Error adding product to cart: " . $conn->error . "');</script>";
    }
}

$product_id = $_GET['id'];
$sql = "SELECT * FROM items WHERE IID = '$product_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    echo "Product not found";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    
<div class="container my-5">
    <div class="row">
        <!-- Left: Image Section -->
        <div class="col-md-6">
            <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($product['Image_1']); ?>" class="d-block w-100 rounded" style="height: 400px; object-fit: cover;">
                    </div>
                    <div class="carousel-item">
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($product['Image_2']); ?>" class="d-block w-100 rounded" style="height: 400px; object-fit: cover;">
                    </div>
                    <div class="carousel-item">
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($product['Image_3']); ?>" class="d-block w-100 rounded" style="height: 400px; object-fit: cover;">
                    </div>
                    <?php if (!empty($product['Image_4'])): ?>
                        <div class="carousel-item">
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($product['Image_4']); ?>" class="d-block w-100 rounded" style="height: 400px; object-fit: cover;">
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($product['Image_5'])): ?>
                        <div class="carousel-item">
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($product['Image_5']); ?>" class="d-block w-100 rounded" style="height: 400px; object-fit: cover;">
                        </div>
                    <?php endif; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>

        <!-- Right: Product Details -->
        <div class="col-md-6">
            <h1 class="mb-3"><?php echo $product['Name']; ?></h1>

            <p class="fs-4">
                <?php if ($product['Price'] != $product['OPrice']): ?>
                    <span class="text-decoration-line-through text-danger fw-bold">Rs. <?php echo number_format($product['Price'], 2); ?></span>
                    <br>
                    <span class="text-success fw-bold">Now: Rs. <?php echo number_format($product['OPrice'], 2); ?></span>
                <?php else: ?>
                    <span class="text-dark fw-bold">Rs. <?php echo number_format($product['Price'], 2); ?></span>
                <?php endif; ?>
            </p>

            <!-- Add to Cart Form -->
            <form method="POST">
                <input type="hidden" name="product_id" value="<?php echo $product['IID']; ?>">
                <button type="submit" name="add_to_cart" class="btn btn-success w-100 mb-2">Add to Cart</button>
            </form>

            <!-- Buy Now Button -->
            <button class="btn btn-danger w-100" onclick="buyNow(<?php echo $product['IID']; ?>)">Buy Now</button>
        </div>
    </div>

    <!-- Description Section -->
    <div class="mt-5 p-4 bg-white rounded shadow">
        <h3 class="mb-3">Product Description</h3>
        <p><?php echo nl2br($product['Description']); ?></p>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function buyNow(productId) {
        window.location.href = 'payment.php?id=' + productId;
    }
</script>

</body>
</html>

<?php
$conn->close();
?>
