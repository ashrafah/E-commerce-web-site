<?php
session_start(); // Start session at the very top, before any HTML or output
error_reporting(0);

// Access user ID (UID) from the session
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}

// Include DB connection
require_once __DIR__ . "/../db/db.php";

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete action
if (isset($_GET['delete_item_id'])) {
    $delete_item_id = $_GET['delete_item_id'];

    // Delete the item from the cart based on CAID
    $sql_delete = "DELETE FROM cart WHERE CAID = '$delete_item_id'";
    if ($conn->query($sql_delete) === TRUE) {
        // Reload the page to reflect the changes
        header("Location: cart.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Fetch cart items for the current user
$sql_cart = "SELECT * FROM cart WHERE UID = '$user_id'";
$result_cart = $conn->query($sql_cart);

// Initialize total price and items array
$total_price = 0;
$selected_items = array();

// Check if any cart items exist
if ($result_cart->num_rows > 0) {
    // Loop through each cart item
    while ($row_cart = $result_cart->fetch_assoc()) {
        $IID = $row_cart['IID'];
        $Qty = $row_cart['Qty']; // Quantity in the cart

        // Fetch item details from Items table
        $sql_item = "SELECT Name, Oprice, Image_1 FROM Items WHERE IID = '$IID'";
        $result_item = $conn->query($sql_item);
        $row_item = $result_item->fetch_assoc();

        // Store item details in an array
        $selected_items[] = array(
            'CAID' => $row_cart['CAID'],
            'IID' => $row_cart['IID'],
            'Name' => $row_item['Name'],
            'Oprice' => $row_item['Oprice'],
            'Qty' => $Qty,
            'Image' => base64_encode($row_item['Image_1']), // Encode the image to base64
        );
        // Add item price to total
        $total_price += $row_item['Oprice'] * $Qty; // Multiply Oprice by quantity for total price
    }
}
?>

<!-- Include Navbar -->
<?php include "include/nav.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
        .cart-item {
            border: 1px solid #ddd;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            transition: background-color 0.3s ease;
        }
        .cart-item img {
            max-width: 100px;
            max-height: 100px;
            margin-left: 15px;
        }
        .cart-item .item-details {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            padding-left: 15px;
        }
        .cart-item .item-actions {
            display: flex;
            align-items: center;
        }
        .cart-item .details {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            font-size: 14px;
            color: #777;
            margin-top: 5px;
        }
        .cart-item .details .price {
            font-weight: bold;
        }
        .cart-item.selected {
            background-color: #d3f9d8;
        }
        .cart-item input[type="checkbox"] {
            transform: scale(1.5);
            margin-right: 15px;
        }
        .total {
            font-size: 1.2rem;
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
        }
        .checkout-btn {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            font-size: 1.1rem;
            margin-top: 20px;
            cursor: pointer;
        }
        .checkout-btn:hover {
            background-color: #0056b3;
        }
        .delete-btn {
            cursor: pointer;
            margin-left: 10px;
            width: 20px;
            height: 20px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">Your Cart</h1>
    <?php
    if (!empty($selected_items)) {
        // Display cart items
        foreach ($selected_items as $item) {
            $total_item_price = $item['Oprice'] * $item['Qty']; // Price = Qty * Oprice
            echo '<div class="cart-item" id="item-' . $item['IID'] . '">';
            // Checkbox first
            echo '<input type="checkbox" class="item-checkbox" value="' . $item['IID'] . '" data-price="' . $total_item_price . '" checked>';
            // Image
            echo '<img src="data:image/jpeg;base64,' . $item['Image'] . '" alt="' . $item['Name'] . '">';
            // Item details (Name, Qty, Price)
            echo '<div class="item-details">';
            echo '<strong>' . $item['Name'] . '</strong>';
            echo '<div class="details">';
            echo '<span class="qty">Qty: ' . $item['Qty'] . '</span>';
            echo '<span class="price">Rs. ' . number_format($total_item_price, 2) . '</span>';
            echo '</div>';
            echo '</div>';
            // Delete icon
            echo '<div class="item-actions">';
            echo '<a href="?delete_item_id=' . $item['CAID'] . '"><img src="img/delete.png" alt="Delete" class="delete-btn"></a>';
            echo '</div>';
            echo '</div>';
        }
        // Display total price
        echo '<div class="total" id="total-price">Total: Rs. ' . number_format($total_price, 2) . '</div>'; // Format total price
    } else {
        echo "<div class='col-12 text-center text-danger'><h4>Your cart is empty.</h4></div>";
    }
    ?>
    
    <!-- Checkout Button -->
    <form action="payment.php" method="GET" id="checkout-form">
        <input type="hidden" name="Type" value="checkout">
        <input type="hidden" name="total" id="total-price-input" value="<?php echo $total_price; ?>"> <!-- Correct total value -->
        <input type="hidden" name="selected_items" id="selected-items" value="<?php echo implode(',', array_column($selected_items, 'IID')); ?>">
        <button type="submit" class="checkout-btn">Proceed to Checkout</button>
    </form>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // Function to update the total price based on selected checkboxes
    $(document).ready(function() {
        function updateTotalPrice() {
            var totalPrice = 0;
            $('.item-checkbox:checked').each(function() {
                totalPrice += parseFloat($(this).data('price')); // Add the data-price value
            });
            $('#total-price').text('Total: Rs. ' + totalPrice.toFixed(2)); // Update the displayed total
            $('#total-price-input').val(totalPrice.toFixed(2)); // Update hidden field
        }

        // Trigger the total price update when a checkbox is checked or unchecked
        $('.item-checkbox').on('change', function() {
            updateTotalPrice();
        });

        // Call the function initially to set the correct total price when the page loads
        updateTotalPrice();
    });
</script>

</body>
</html>

<!-- Include Footer -->
<?php include "include/footer.php"; ?>

<?php
$conn->close();
?>
