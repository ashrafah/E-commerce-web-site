<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'shop');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if 'id' is set in the URL to delete a payment record
if (isset($_GET['id'])) {
    $paymentId = $_GET['id'];

    // SQL query to delete the payment based on the PID
    $sql = "DELETE FROM payments WHERE PID = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Bind the payment ID to the SQL query
        $stmt->bind_param("i", $paymentId);

        // Execute the query
        if ($stmt->execute()) {
            echo "<script>alert('Payment record deleted successfully!'); window.location.href='Earnings.php';</script>";
        } else {
            echo "<script>alert('Error deleting record! Please try again.'); window.location.href='Earnings.php';</script>";
        }

        // Close the statement
        $stmt->close();
    }
}

// Fetch data from the payments table
$sql = "SELECT PID, Type AS PaymentType, UPrice AS UnitPrice, Qty AS Quantity, Total, Date FROM payments";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Earnings</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
        }

        .left-rectangle {
            background-color: #E7E0EF;
            position: fixed;
            top: 50px;
            left: 50px;
            bottom: 50px;
            width: 200px;
            padding: 20px;
            box-sizing: border-box;
            border-radius: 15px;
        }

        .left-rectangle h2 {
            margin-top: 0;
            text-align: center;
        }

        .nav-links a {
            display: block;
            margin: 15px 0;
            text-decoration: none;
            color: white;
            text-align: center;
            padding: 10px;
            background-color: #472BE9;
            border-radius: 15px;
        }

        .nav-links a.current {
            background-color: #2BBDE9;
        }

        .logout-button {
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 10px 20px;
            background-color: #D9534F;
            color: white;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            text-align: center;
        }

        .right-rectangle {
            background-color: #E7E0EF;
            margin: 50px;
            margin-left: 300px;
            padding: 20px;
            box-sizing: border-box;
            border-radius: 15px;
            width: calc(100% - 350px);
        }

        .left-rectangle h2, .right-rectangle h2 {
            margin-top: 0;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        .action-buttons img {
            width: 20px;
            height: 20px;
            cursor: pointer;
            margin-right: 10px;
        }

        .add-earnings {
            color: white;
            background-color: #2BE970;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 15px;
            float: right;
            margin-bottom: 50px;
        }

        .add-earnings:hover {
            background-color: #45a049;
        }

        .popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: none;
            z-index: 1000;
        }

        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            z-index: 999;
        }
    </style>
    <script>
        function confirmDelete(paymentId) {
            if (confirm("Are you sure you want to delete this payment?")) {
                window.location.href = `Earnings.php?id=${paymentId}`;
            }
        }
    </script>
</head>
<body>
<div class="left-rectangle">
    <h2>Dashboard</h2>
    <div class="nav-links">
        <a href="dashboard.php">Dashboard</a>
        <a href="products.php">Products</a>
        <a href="categories.php">Category</a>
        <a href="user.php">Users</a>
        <a href="Earnings.php" class="current">Earnings</a>
    </div>
    <a href="login.php">
        <button class="logout-button">Logout</button>
    </a>
</div>

<div class="right-rectangle">
    <h2>Earnings</h2>
    <a href="AddEarnings.php" class="add-earnings">Add Offline Earnings</a>
    <table>
        <thead>
            <tr>
                <th>PID</th>
                <th>PaymentType</th>
                <th>Unit Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['PID'] . "</td>";
                    echo "<td>" . $row['PaymentType'] . "</td>";
                    echo "<td>" . $row['UnitPrice'] . "</td>";
                    echo "<td>" . $row['Quantity'] . "</td>";
                    echo "<td>" . $row['Total'] . "</td>";
                    echo "<td>" . $row['Date'] . "</td>";
                    echo "<td class='action-buttons'>";
                    echo "<a href='EditEarnings.php?id=" . $row['PID'] . "'><img src='img/edit.png' alt='Edit'></a>";
                    echo "<a href='#' onclick='confirmDelete(\"" . $row['PID'] . ")'><img src='img/delete.png' alt='Delete'></a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No payments found</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>
</div>
</body>
</html>
