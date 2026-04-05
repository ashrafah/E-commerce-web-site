<?php

session_start();

// Access user ID (UID) from the session
if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
}

// Database connection
$servername = "localhost"; // Replace with your database server details
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "shop"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Access user ID (UID) from the session
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Query to get the cart items for the user (limit to 4 items for now)
    $cart_query = "SELECT * FROM cart WHERE UID = $user_id LIMIT 4"; // Limit to 4 items initially
    $cart_result = mysqli_query($conn, $cart_query);

    // Store cart items in an array
    $cart_items = [];
    while ($cart_row = mysqli_fetch_assoc($cart_result)) {
        $cart_items[] = $cart_row;
    }

    // If there are more than 4 items, allow loading more
    $more_items_available = mysqli_num_rows($cart_result) > 4;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- Bootstrap css -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

    *{
        font-family: poppins ,sans-serif;
    }

    .navbar-custom {
        background-color: #3B1E54; /* Purple bar at the top */
        color: white;
        font-size: 11px;
    }
    .navbar-custom a{
        text-decoration: none !important;
    }
    .navbar-search {
        background-color: #b19cd9; /* Light purple for the main area */
    }
    .navbar-search input {
        border-radius: 20px;
    }
    .navbar-search button{
        border-radius: 20px;
        background-color: #fff;
    }
    .navbar-search button:hover{
        background-color: #D4BEE4; ;
    }
    .navbar {
        background-color: #D4BEE4;
        height: 40px;
    }
    .navbar-nav a {
        color: black;
        font-weight: 450;
    }
    .navbar-nav a:hover{
        color: #9B7EBD;
        font-weight: 450;
        text-decoration: underline;
    }

    /* Style for Cart Items in Dropdown */
    .cart-item {
        display: flex;
        margin-bottom: 10px;
        padding: 5px;
        text-decoration: none;
    }

    .cart-item img {
        margin-right: 15px;
        max-width: 50px;
        max-height: 50px;
    }

    .cart-item .item-details {
        flex-grow: 1; /* Makes sure the details take the remaining space */
    }

    .cart-item span {
        font-size: 14px;
        color: #333;
    }

    /* Quantity and Price Style - Inline */
    .cart-item .details {
        font-size: 12px;
        color: #777;
        margin-top: 5px;
        display: flex;
        justify-content: space-between; /* Spread quantity and price across the line */
        gap: 10px; /* Add space between qty and price */
    }

    /* Hover effect for the cart item */
    .cart-item:hover {
        background-color:rgb(116, 199, 134);
        cursor: pointer;
    }

    /* ===== footer ====== */
    .footer-primary{
        background-color: #D4BEE4;
    }

  </style>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
  <!-- nav bar start -->
  
  <!-- top header start -->
  <div class="navbar-custom py-2">
    <div class="container d-flex justify-content-between">
      <span class="fw-bold">Dream in Touch</span>
      <span>
        <i class="bi bi-telephone"></i> <a href="contact-us.php" class="text-white">+94 77 9197191 </a>
        <span>|</span>
        <i class="bi bi-geo-alt"></i> <a href="contact-us.php" class="text-white">Location</a>
      </span>
    </div>
  </div>
  <!-- top header end -->

  <!-- main nav bar start -->
  <div class="sticky-top">
    <div class="navbar-search py-3">
      <div class="container d-flex align-items-center justify-content-between">
        <!-- Logo -->
        <div class="d-flex align-items-center logo">
          <a href="AboutUs.php">
            <img src="../assets/nav-bar-icons/nav-logo.png" alt="Logo" style="height: 50px;">
          </a>
        </div>

        <!-- Search Bar -->
        <div class="container-fluid">
          <div class="row">
            <div class="col-12 col-md-8 col-lg-6 mx-auto my-2">
              <!-- Form for search -->
              <form action="SearchResult.php" method="GET" class="input-group">
                <input type="text" class="form-control" name="query" placeholder="Search..." required>
                <button class="btn" type="submit">
                  <img src="../assets/nav-bar-icons/search.svg" alt="Search">
                </button>
              </form>
            </div>
          </div>
        </div>

        <!-- Icons -->
        <div class="d-flex align-items-center justify-content-end gap-3 ">

          <!-- Cart Icon with Dropdown -->
          <a href="#" class="navbar-icon" id="navbarCartDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="../assets/nav-bar-icons/cart-icon.svg" alt="Cart">
          </a>

          <!-- Cart Dropdown Menu -->
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarCartDropdown">
              <?php if (isset($_SESSION['user_id'])): ?>
                  <li><a class="dropdown-item fw-bold" href="cart.php">Cart</a></li>
                  <div class="dropdown-item">
                      <?php if (!empty($cart_items)): ?>
                          <?php foreach ($cart_items as $cart_item): ?>
                              <?php
                              $iid = $cart_item['IID'];
                              $qty = $cart_item['Qty'];  // Fetching quantity
                              
                              // Fetch the price from the `items` table and calculate total price
                              $item_query = "SELECT Name, Price, Image_1 FROM items WHERE IID = '$iid'";
                              $item_result = mysqli_query($conn, $item_query);
                              $item = mysqli_fetch_assoc($item_result);
                              
                              // Calculate the total price
                              $price = $item['Price'];
                              $total_price = $price * $qty;  // Multiply by quantity
                              
                              $image_data = base64_encode($item['Image_1']);
                              $image_src = 'data:image/jpeg;base64,' . $image_data;
                              ?>
                              <a href="cart.php" class="cart-item">
                                  <img src="<?= $image_src ?>" alt="Item Image">
                                  <div class="item-details">
                                    <span><?= substr($item['Name'], 0, 50) . (strlen($item['Name']) > 50 ? '...' : '') ?></span>
                                    <!-- Quantity and Price on the same line -->
                                    <div class="details">
                                      <span>Qty: <?= $qty ?></span>
                                      <span>Price: $<?= number_format($total_price, 2) ?></span>
                                    </div>
                                  </div>
                              </a>
                          <?php endforeach; ?>
                      <?php else: ?>
                          <p class="text-center text-muted">Your cart is empty</p>
                      <?php endif; ?>
                  </div>
                  <li><a class="dropdown-item text-center" href="cart.php">Show More...</a></li>
              <?php else: ?>
                  <li class="text-center p-3">
                      <p class="mb-2">Login to view your cart</p>
                      <a href="login.php" class="btn btn-primary btn-sm">Login</a>
                  </li>
              <?php endif; ?>
          </ul>

          <a href="" class="navbar-icon">
            <img src="../assets/nav-bar-icons/message-bell-icon.svg" alt="">
          </a>

          <!-- Profile Icon Dropdown -->
          <a href="#" class="navbar-icon" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="../assets/nav-bar-icons/log-icon.svg" alt="">
          </a>

          <!-- Dropdown Menu -->
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
            <?php if (isset($_SESSION['user_id'])): ?>
              <li><a class="dropdown-item" href="profile.php">Profile</a></li>
              <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            <?php else: ?>
              <li><a class="dropdown-item" href="login.php">Login</a></li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </div>

    <!-- secondary nav bar start -->
    <nav class="navbar navbar-expand-lg mb-3" style="background-color: #D4BEE4">
      <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav mx-auto my-2 my-lg-0 gap-3 navbar-nav-scroll">
            <li class="nav-item">
              <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="categories.php">Category</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="OfferItems.php">Offer</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="contact-us.php">Contact</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="AboutUs.php">About</a>
            </li>
          </ul>
        </div>
      </div>
  </div>
  </nav>
  <!-- secondary nav bar end -->

  <!-- main nav bar end -->

  <!-- nav bar end -->

  <!-- Bootstrap js -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
