<?php
// Include DB connection
require_once __DIR__ . "/../db/db.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get parameters from URL
$type = isset($_GET['Type']) ? $_GET['Type'] : null;
$name = isset($_GET['Name']) ? $_GET['Name'] : null;
$num = isset($_GET['Num']) ? $_GET['Num'] : null;
$cname = isset($_GET['Cname']) ? $_GET['Cname'] : null;
$mobile = isset($_GET['Mobile']) ? $_GET['Mobile'] : null;
$amount = isset($_GET['Amount']) ? floatval($_GET['Amount']) : 0;

// If it's a bill payment, set the item name accordingly
if ($type && $name && $num && $cname && $mobile && $amount) {
    $item_name = "$type Bill Payment - $name (Account: $num)";
} elseif ($type === "checkout") {
    // Checkout from cart.php - Get the total and selected_items
    $total = isset($_GET['total']) ? floatval($_GET['total']) : 0;
    $selected_items = isset($_GET['selected_items']) ? $_GET['selected_items'] : '';

    if ($total > 0 && $selected_items) {
        $item_name = "Checkout - Items: $selected_items (Total: LKR $total)";
        $amount = $total; // Set the amount to the total
    } else {
        die("Invalid checkout data. Payment request canceled.");
    }
} else {
    // Money Transfer Handling
    if ($type === "MoneyTransfer") {
        // Get Money Transfer parameters from the URL
        $RAcc = isset($_GET['RAcc']) ? $_GET['RAcc'] : null;
        $RName = isset($_GET['RName']) ? $_GET['RName'] : null;
        $Bank = isset($_GET['Bank']) ? $_GET['Bank'] : null;
        $Branch = isset($_GET['Branch']) ? $_GET['Branch'] : null;
        $SName = isset($_GET['SName']) ? $_GET['SName'] : null;
        $SNIC = isset($_GET['SNIC']) ? $_GET['SNIC'] : null;

        // Money Transfer Item Name
        if ($RAcc && $RName && $Bank && $Branch && $SName && $SNIC && $amount > 0) {
            $item_name = "$type - Transfer to $RName (Account: $RAcc) via $Bank ($Branch)";
        } else {
            die("Missing parameters for Money Transfer.");
        }
    } else {
        // Normal product purchase - Get item ID from URL
        $item_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        // Fetch item details from the database
        $sql = "SELECT OPrice, Name FROM items WHERE IID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $item_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $item = $result->fetch_assoc();

        if (!$item) {
            die("Invalid item ID");
        }

        $amount = $item['OPrice']; // Get OPrice from database
        $item_name = $item['Name']; // Get item name

        $stmt->close();
    }
}
$conn->close();

// Validate amount
if ($amount <= 0) {
    die("Invalid amount. Payment request canceled.");
}

// PayHere Merchant Credentials
$merchant_id = "1229430"; // Your PayHere Merchant ID
$merchant_secret = "NzAzMzQ2MDQ3MTg2OTU5NDYxMTEyNDk5MzMxODIyOTY1OTk1NTY1"; // Your PayHere Secret

$order_id = uniqid("PAY");
$currency = "LKR";

$return_url = "index.php";
$cancel_url = "javascript:alert('Payment Canceled');";
$notify_url = "notify.php";

// Get user details from URL or set defaults
$first_name = isset($_GET['first_name']) ? $_GET['first_name'] : 'John';
$last_name = isset($_GET['last_name']) ? $_GET['last_name'] : 'Doe';
$email = isset($_GET['email']) ? $_GET['email'] : 'john.doe@example.com';
$phone = isset($_GET['phone']) ? $_GET['phone'] : '0771234567';
$address = isset($_GET['address']) ? $_GET['address'] : 'No 1, Colombo';
$city = isset($_GET['city']) ? $_GET['city'] : 'Colombo';

// Payment Data
$payment_data = [
    "merchant_id" => $merchant_id,
    "return_url" => $return_url,
    "cancel_url" => $cancel_url,
    "notify_url" => $notify_url,
    "order_id" => $order_id,
    "items" => $item_name,
    "amount" => $amount,
    "currency" => $currency,
    "first_name" => $first_name,
    "last_name" => $last_name,
    "email" => $email,
    "phone" => $phone,
    "address" => $address,
    "city" => $city,
    "country" => "Sri Lanka"
];

// Generate hash for security
$hash_data = $merchant_id . $order_id . number_format($amount, 2, '.', '') . $currency . strtoupper(md5(trim($merchant_secret)));
$payment_data["hash"] = strtoupper(md5($hash_data));

// Redirect to PayHere checkout
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to PayHere</title>
</head>
<body onload="document.getElementById('payhere_form').submit();">
    <h2>Redirecting to PayHere...</h2>
    <form id="payhere_form" method="post" action="https://sandbox.payhere.lk/pay/checkout">
        <?php
        // Include the money transfer details if it's a Money Transfer
        if ($type === "MoneyTransfer") {
            $payment_data['RAcc'] = $RAcc;
            $payment_data['RName'] = $RName;
            $payment_data['Bank'] = $Bank;
            $payment_data['Branch'] = $Branch;
            $payment_data['SName'] = $SName;
            $payment_data['SNIC'] = $SNIC;
        }
        // Loop through the payment data to create the hidden inputs
        foreach ($payment_data as $key => $value) {
            echo "<input type='hidden' name='$key' value='$value'>";
        }
        ?>
        <noscript>
            <input type="submit" value="Click here if you are not redirected">
        </noscript>
    </form>
</body>
</html>
