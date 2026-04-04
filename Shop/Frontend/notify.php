<?php
// Include DB connection
require_once __DIR__ . "/../db/db.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// PayHere Merchant Secret
$merchant_secret = "NzAzMzQ2MDQ3MTg2OTU5NDYxMTEyNDk5MzMxODIyOTY1OTk1NTY1";

// Get the POST data from PayHere
$payhere_data = $_POST;

// Validate the hash
$hash_data = $payhere_data['merchant_id'] . $payhere_data['order_id'] . number_format($payhere_data['amount'], 2, '.', '') . $payhere_data['currency'] . strtoupper(md5(trim($merchant_secret)));
$received_hash = strtoupper(md5($hash_data));

// Check if the received hash matches
if ($payhere_data['hash'] !== $received_hash) {
    die("Hash mismatch. Payment verification failed.");
}

// Check payment status
if ($payhere_data['status_code'] == 2) { // Payment success (status_code 2)
    // Payment is successful, proceed to insert into the database

    // Get the payment data
    $order_id = $conn->real_escape_string($payhere_data['order_id']);
    $item_name = $conn->real_escape_string($payhere_data['items']);
    $amount = $payhere_data['amount'];
    $currency = $conn->real_escape_string($payhere_data['currency']);
    $first_name = $conn->real_escape_string($payhere_data['first_name']);
    $last_name = $conn->real_escape_string($payhere_data['last_name']);
    $email = $conn->real_escape_string($payhere_data['email']);
    $phone = $conn->real_escape_string($payhere_data['phone']);
    $address = $conn->real_escape_string($payhere_data['address']);
    $city = $conn->real_escape_string($payhere_data['city']);
    $country = $conn->real_escape_string($payhere_data['country']);
    
    // Prepare the SQL query to insert payment data into the database
    $sql = "INSERT INTO orders (order_id, item_name, amount, currency, first_name, last_name, email, phone, address, city, country, payment_status)
            VALUES ('$order_id', '$item_name', '$amount', '$currency', '$first_name', '$last_name', '$email', '$phone', '$address', '$city', '$country', 'Completed')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Payment Successful. Order has been placed!";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    // If payment failed, insert a failed payment record
    $order_id = $conn->real_escape_string($payhere_data['order_id']);
    $sql = "INSERT INTO orders (order_id, payment_status)
            VALUES ('$order_id', 'Failed')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Payment Failed. Please try again.";
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
