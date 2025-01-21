<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'shop');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the 'id' is set in the URL to delete a category
if (isset($_GET['id'])) {
    $categoryId = $_GET['id'];

    // SQL query to delete the category based on the CID
    $sql = "DELETE FROM categories WHERE CID = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Bind the category ID to the SQL query
        $stmt->bind_param("i", $categoryId);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect back to the categories page after successful deletion
            header("Location: categories.php");
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }

        // Close the statement
        $stmt->close();
    }
}

// Fetch data from the categories table
$sql = "SELECT CID, Image, Name FROM categories";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories Dashboard</title>
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

        .add-category {
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

        img.category-image {
            width: 320px;
            height: 250px;
        }
    </style>
    <script>
        function confirmDelete(categoryName, categoryId) {
            document.getElementById('popup-text').innerText = `Do you really want to delete "${categoryName}" details?`;
            document.getElementById('popup').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('confirm-yes').onclick = function () {
                window.location.href = `categories.php?id=${categoryId}`; // Redirect to the same page with the category ID to trigger deletion
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
        <a href="products.php">Products</a>
        <a href="categories.php" class="current">Category</a>
        <a href="user.php">Users</a>
        <a href="earnings.php">Earnings</a>
    </div>
    <a href="login.php"><button class="logout-button">
        <img src="img/logout.png" alt="Logout">Logout
    </button></a>
</div>

<div class="right-rectangle">
    <h2>Categories</h2>
    <a href="AddCategory.php" class="add-category">Add Category</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['CID'] . "</td>";
                    echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['Image']) . "' class='category-image' alt='Category Image'></td>";
                    echo "<td>" . $row['Name'] . "</td>";
                    echo "<td class='action-buttons'>";
                    echo "<a href='editcategory.php?id=" . $row['CID'] . "'><img src='img/edit.png' alt='Edit'></a>";
                    echo "<a href='#' onclick='confirmDelete(\"" . $row['Name'] . "\", " . $row['CID'] . ")'><img src='img/delete.png' alt='Delete'></a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No categories found</td></tr>";
            }

            $conn->close();
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
