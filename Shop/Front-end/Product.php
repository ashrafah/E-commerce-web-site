<?php
session_start();

// Enable error reporting for debugging
error_reporting(0);

// Include DB connection
require_once __DIR__ . "/../db/db.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('❌ You need to log in first!');</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle Add to Cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $current_date = date('Y-m-d H:i:s');

    // Check if product ID is valid
    if (empty($product_id) || $product_id == 0) {
        echo "<script>alert('❌ Invalid product ID!');</script>";
        exit;
    }

    // Check if the product is already in the cart for this user
    $check_sql = "SELECT * FROM cart WHERE IID = '$product_id' AND UID = '$user_id'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // Product is already in the cart, update the quantity
        $row = $check_result->fetch_assoc();
        $new_qty = $row['Qty'] + 1;  // Increment the quantity by 1
        $update_sql = "UPDATE cart SET Qty = '$new_qty' WHERE IID = '$product_id' AND UID = '$user_id'";
        
        if ($conn->query($update_sql) === TRUE) {
            echo "<script>alert('✅ Product quantity updated in the cart!');</script>";
        } else {
            echo "<script>alert('❌ Error updating product quantity: " . $conn->error . "');</script>";
        }
    } else {
        // Product is not in the cart, insert new row with Qty = 1
        $insert_sql = "INSERT INTO cart (IID, UID, Date, Qty) VALUES ('$product_id', '$user_id', '$current_date', 1)";
        
        if ($conn->query($insert_sql) === TRUE) {
            echo "<script>alert('✅ Product added to cart successfully!');</script>";
        } else {
            echo "<script>alert('❌ Error adding product to cart: " . $conn->error . "');</script>";
        }
    }
}

// Fetch Product Details
$product_id = $_GET['item_id'];
$sql = "SELECT * FROM items WHERE IID = '$product_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    echo "<div class='text-center text-danger'><h3>Product not found ❌</h3></div>";
    exit;
}

?>

<!-- Include Navbar -->
<?php include "include/nav.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['Name']; ?> - Product Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .product-container {
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }
        .product-images img {
            border-radius: 12px;
            transition: transform 0.3s ease-in-out;
        }
        .product-images img:hover {
            transform: scale(1.05);
        }
        .small-image {
            cursor: pointer;
            border: 2px solid transparent;
            border-radius: 8px;
            transition: 0.3s;
        }
        .small-image:hover, .small-image.active {
            border-color: #007bff;
            transform: scale(1.1);
        }
        .price {
            font-size: 1.5rem;
            color: #333;
        }
        .offer-price {
            text-decoration: line-through;
            color: red;
            font-size: 1.2rem;
        }
        .btn-custom {
            font-size: 1.2rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .btn-custom:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<div class="container product-container">
    <div class="row">
        <!-- Image Section -->
        <div class="col-md-6 text-center product-images">
            <img id="large-image" class="img-fluid mb-3 shadow-lg" src="data:image/jpeg;base64,<?php echo base64_encode($product['Image_1']); ?>" alt="Product Image">

            <!-- Thumbnails -->
            <div class="d-flex justify-content-center">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <?php if (!empty($product['Image_' . $i])): ?>
                        <img class="small-image mx-1 shadow-sm" width="70" height="70" src="data:image/jpeg;base64,<?php echo base64_encode($product['Image_' . $i]); ?>" onclick="changeImage(this, '<?php echo base64_encode($product['Image_' . $i]); ?>')">
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        </div>

        <!-- Details Section -->
        <div class="col-md-6">
            <h2 class="fw-bold"><?php echo $product['Name']; ?></h2>
            <p class="price">
                <?php if ($product['Price'] != $product['OPrice']): ?>
                    <span class="offer-price">Rs. <?php echo number_format($product['Price'], 2); ?></span><br>
                    <span class="text-success fw-bold">Rs. <?php echo number_format($product['OPrice'], 2); ?></span>
                <?php else: ?>
                    <span class="text-dark fw-bold">Rs. <?php echo number_format($product['Price'], 2); ?></span>
                <?php endif; ?>
            </p>

            <!-- Add to Cart & Buy Now -->
            <form method="POST">
                <input type="hidden" name="product_id" value="<?php echo $product['IID']; ?>">
                <button type="submit" name="add_to_cart" class="btn btn-success btn-lg mb-2 w-100 btn-custom">
                    🛒 Add to Cart
                </button>
            </form>
            <button class="btn btn-warning btn-lg w-100 btn-custom" onclick="buyNow(<?php echo $product['IID']; ?>)">
                ⚡ Buy Now
            </button>
        </div>
    </div>

    <!-- Description Section -->
    <div class="mt-4">
        <h4 class="fw-bold">Product Description</h4>
        <p class="text-muted"><?php echo nl2br($product['Description']); ?></p>
    </div>
</div>

<script>
    function changeImage(selectedImage, imageSrc) {
        document.getElementById('large-image').src = "data:image/jpeg;base64," + imageSrc;
        
        // Highlight active thumbnail
        let thumbnails = document.querySelectorAll('.small-image');
        thumbnails.forEach(img => img.classList.remove('active'));
        selectedImage.classList.add('active');
    }

    function buyNow(productId) {
        window.location.href = 'payment.php?id=' + productId;
    }
</script>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<!-- Include Footer -->
<?php include "include/footer.php"; ?>
