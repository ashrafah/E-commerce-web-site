<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'shop');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the 'id' is set in the URL to delete a user
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // SQL query to delete the user based on the UID
    $sql = "DELETE FROM users WHERE UID = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Bind the user ID to the SQL query
        $stmt->bind_param("i", $userId);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect back to the users page after successful deletion
            header("Location: user.php");
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }

        // Close the statement
        $stmt->close();
    }
}

// Fetch data from the users table
$sql = "SELECT UID, CONCAT(FirstName, ' ', LastName) AS Name, `P.Town` AS Town, `MobileNo.` AS Mobile, Email FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
            margin-left: 300px; /* Space for the left rectangle + margin */
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

        .add-user {
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
    </style>
    <script>
        function confirmDelete(userName, userId) {
            document.getElementById('popup-text').innerText = `Do you really want to delete "${userName}" details?`;
            document.getElementById('popup').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('confirm-yes').onclick = function () {
                window.location.href = `user.php?id=${userId}`; // Redirect to the same page with the user ID to trigger deletion
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
        <a href="categories.php">Category</a>
        <a href="user.php" class="current">Users</a>
        <a href="earnings.php">Earnings</a>
    </div>
    <a href="login.php"><button class="logout-button">
        <img src="img/logout.png" alt="Logout">Logout
    </button></a>
</div>

<div class="right-rectangle">
    <h2>Users</h2>
    <a href="AddUser.php" class="add-user">Add User</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Town</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['UID'] . "</td>";
                    echo "<td>" . $row['Name'] . "</td>";
                    echo "<td>" . $row['Town'] . "</td>";
                    echo "<td>" . $row['Mobile'] . "</td>";
                    echo "<td>" . $row['Email'] . "</td>";
                    echo "<td class='action-buttons'>";
                    echo "<a href='edituser.php?id=" . $row['UID'] . "'><img src='img/edit.png' alt='Edit'></a>";
                    echo "<a href='#' onclick='confirmDelete(\"" . $row['Name'] . "\", " . $row['UID'] . ")'><img src='img/delete.png' alt='Delete'></a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No users found</td></tr>";
            }

            $conn->close();
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
