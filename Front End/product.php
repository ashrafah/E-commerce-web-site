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
    $current_date = date('Y-m-d H:i:s'); // Current date and time

    // Insert into the cart table
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
    <style>
        body {
            background-color: #F3EFF7;
            margin: 50px 150px;
        }
        .product-container {
            display: flex;
            justify-content: space-between;
        }
        .product-images {
            width: 40%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .product-details {
            width: 55%;
        }
        .small-image {
            width: 70px;
            height: 70px;
            cursor: pointer;
            margin: 5px;
            object-fit: cover;
            border: 1px solid #ccc;
        }
        .large-image {
            width: 80%;
            border: 2px solid #ccc;
            margin-bottom: 20px;
        }
        .price {
            font-size: 18px;
            color: #333;
        }
        .offer-price {
            text-decoration: line-through;
            color: red;
        }
        .description {
            margin-top: 20px;
            font-size: 16px;
            white-space: pre-wrap; /* Ensures line breaks in description */
        }

        .button-group {
            margin-top: 20px;
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .add-to-cart {
            background-color: #4CAF50; /* Green */
            color: white;
        }

        .buy-now {
            background-color: #FF5722; /* Orange */
            color: white;
        }

        .btn:hover {
            opacity: 0.8;
        }

    </style>
</head>
<body>

<div class="product-container">
    <!-- Left: Image Section -->
    <div class="product-images">
        <img id="large-image" class="large-image" src="data:image/jpeg;base64,<?php echo base64_encode($product['Image_1']); ?>" alt="Product Image">
        <div>
            <img class="small-image" src="data:image/jpeg;base64,<?php echo base64_encode($product['Image_1']); ?>" alt="Image 1" onclick="changeImage('<?php echo base64_encode($product['Image_1']); ?>')">
            <img class="small-image" src="data:image/jpeg;base64,<?php echo base64_encode($product['Image_2']); ?>" alt="Image 2" onclick="changeImage('<?php echo base64_encode($product['Image_2']); ?>')">
            <img class="small-image" src="data:image/jpeg;base64,<?php echo base64_encode($product['Image_3']); ?>" alt="Image 3" onclick="changeImage('<?php echo base64_encode($product['Image_3']); ?>')">
            <?php if (!empty($product['Image_4'])): ?>
                <img class="small-image" src="data:image/jpeg;base64,<?php echo base64_encode($product['Image_4']); ?>" alt="Image 4" onclick="changeImage('<?php echo base64_encode($product['Image_4']); ?>')">
            <?php endif; ?>
            <?php if (!empty($product['Image_5'])): ?>
                <img class="small-image" src="data:image/jpeg;base64,<?php echo base64_encode($product['Image_5']); ?>" alt="Image 5" onclick="changeImage('<?php echo base64_encode($product['Image_5']); ?>')">
            <?php endif; ?>
        </div>
    </div>

    <!-- Right: Details Section -->
    <div class="product-details">
        <h1><?php echo $product['Name']; ?></h1>
        
        <p class="price">
            <?php if ($product['Price'] != $product['OPrice']): ?>
                <span class="offer-price">Rs. <?php echo number_format($product['Price'], 2); ?></span>
                </br>
                <span>Rs. <?php echo number_format($product['OPrice'], 2); ?></span>
            

            <?php else: ?>
                <span>Rs. <?php echo number_format($product['Price'], 2); ?></span>

            <?php endif; ?>
        </p>

        <!-- Add to Cart Form -->
        <form method="POST">
            <input type="hidden" name="product_id" value="<?php echo $product['IID']; ?>">
            <button type="submit" name="add_to_cart" class="btn add-to-cart">Add to Cart</button>
        </form>

        <!-- Buy Now Button with Redirect -->
        <button class="btn buy-now" onclick="buyNow(<?php echo $product['IID']; ?>)">Buy Now</button>
    </div>
</div>

<!-- Description Section below the images -->
<div class="description">
    <p><?php echo nl2br($product['Description']); ?></p>
</div>

<script>

     // Store valid image sources in an array for easy cycling
     var images = [
        "<?php echo base64_encode($product['Image_1']); ?>",
        "<?php echo base64_encode($product['Image_2']); ?>",
        "<?php echo base64_encode($product['Image_3']); ?>",
        "<?php echo !empty($product['Image_4']) ? base64_encode($product['Image_4']) : ''; ?>",
        "<?php echo !empty($product['Image_5']) ? base64_encode($product['Image_5']) : ''; ?>"
    ];

    // Remove any empty or invalid image sources
    images = images.filter(function(image) {
        return image !== "";
    });

    var currentImageIndex = 0;

    function changeImage(imageSrc) {
        var largeImage = document.getElementById('large-image');
        largeImage.src = "data:image/jpeg;base64," + imageSrc;
    }

    // Auto change image every 3 seconds
    setInterval(function() {
        if (images.length > 0) {
            // Update the large image
            changeImage(images[currentImageIndex]);
            // Move to the next image
            currentImageIndex = (currentImageIndex + 1) % images.length;
        }
    }, 3000); // 3000ms = 3 seconds
    
    // Function to redirect to payment.php with product ID
    function buyNow(productId) {
        window.location.href = 'payment.php?id=' + productId; // Redirects to payment page with item ID
    }
</script>

</body>
</html>
