<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'shop');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the 'id' is set in the URL to delete a category
if (isset($_GET['id'])) {
    $categoryId = $_GET['id'];

    // SQL query to delete the category based on the IID
    $sql = "DELETE FROM items WHERE IID = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Bind the category ID to the SQL query
        $stmt->bind_param("i", $categoryId);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect back to the products page after successful deletion
            header("Location: products.php");
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }

        // Close the statement
        $stmt->close();
    }
}

// Fetch data from the items table
$sql = "SELECT IID, Image_1, Image_2, Image_3, Image_4, Image_5, Name, Price, Qty FROM items";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Items Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
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

        .right-rectangle {
            background-color: #E7E0EF;
            margin: 50px;
            margin-left: 300px;
            padding: 20px;
            box-sizing: border-box;
            border-radius: 15px;
        }

        .left-rectangle h2, .right-rectangle h2 {
            margin-top: 0;
            text-align: center;
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

        .logout-button img {
            width: 16px;
            height: 16px;
            margin-right: 10px;
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

        .add-product {
            color: white;
            background-color: #2BE970;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 15px;
            float: right;
            margin-bottom: 50px;
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

        img.item-image {
            width: 100px;
            height: 100px;
            margin: 5px;
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

        .popup-buttons {
            margin-top: 20px;
            text-align: center;
        }

        .popup-buttons button {
            margin: 0 10px;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .popup-buttons .yes {
            background-color: #28a745;
            color: white;
        }

        .popup-buttons .no {
            background-color: #dc3545;
            color: white;
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
        function confirmDelete(categoryName, categoryId) {
            document.getElementById('popup-text').innerText = `Do you really want to delete "${categoryName}" details?`;
            document.getElementById('popup').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('confirm-yes').onclick = function () {
                window.location.href = `products.php?id=${categoryId}`; // Redirect to the same page with the category ID to trigger deletion
            };
        }

        function closePopup() {
            document.getElementById('popup').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }
    </script>
</head>
<body>
<div id="overlay"></div>
<div class="popup" id="popup">
    <p id="popup-text"></p>
    <div class="popup-buttons">
        <button class="yes" id="confirm-yes">Yes</button>
        <button class="no" onclick="closePopup()">No</button>
    </div>
</div>

<div class="left-rectangle">
    <h2>Dashboard</h2>
    <div class="nav-links">
        <a href="dashboard.php">Dashboard</a>
        <a href="products.php" class="current">Products</a>
        <a href="categories.php">Category</a>
        <a href="user.php">Users</a>
        <a href="earnings.php">Earnings</a>
    </div>
    <a href="login.php"><button class="logout-button">
        <img src="img/logout.png" alt="Logout">Logout
    </button></a>
</div>

<div class="right-rectangle">
    <h2>Products</h2>
    <a href="AddProduct.php" class="add-product">Add Product</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Images</th>
                <th>Name</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['IID'] . "</td>";
                    echo "<td>";
                    for ($i = 1; $i <= 5; $i++) {
                        if (!empty($row["Image_$i"])) {
                            echo "<img src='data:image/jpeg;base64," . base64_encode($row["Image_$i"]) . "' class='item-image' alt='Item Image $i'>";
                        }
                    }
                    echo "</td>";
                    echo "<td>" . $row['Name'] . "</td>";
                    echo "<td>" . $row['Price'] . "</td>";
                    echo "<td>" . $row['Qty'] . "</td>";
                    echo "<td class='action-buttons'>";
                    echo "<a href='editproduct.php?id=" . $row['IID'] . "'><img src='img/edit.png' alt='Edit'></a>";
                    echo "<a href='#' onclick='confirmDelete(\"" . $row['Name'] . "\", " . $row['IID'] . ")'><img src='img/delete.png' alt='Delete'></a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No products found</td></tr>";
            }

            $conn->close();
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
