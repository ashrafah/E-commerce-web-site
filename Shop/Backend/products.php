<?php
// Include DB connection
require_once __DIR__ . "/../db/db.php";

session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: ../Front-end/login.php"); // Redirect if not logged in as admin
    exit();
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if 'id' is set in URL for deletion
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // SQL query to delete the product
    $sql = "DELETE FROM items WHERE IID = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $productId);

        if ($stmt->execute()) {
            // Redirect back to the products page after deletion
            header("Location: products.php");
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }

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
    <title>Products Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background-color: #F8F8FF; }

        .sidebar {
            background-color: #E7E0EF;
            width: 250px;
            height: 100vh;
            position: fixed;
            padding: 20px;
            border-radius: 15px;
        }

        .nav-link { color: white !important; background-color: #472BE9; border-radius: 15px; margin-bottom: 10px; padding: 10px; text-align: center; }
        .nav-link.active { background-color: #2BBDE9; }
        .logout-btn { background-color: #D9534F; color: white; border-radius: 15px; text-align: center; padding: 10px; display: block; margin-top: auto; }

        .content-container {
            margin-left: 300px;
            margin-right: 50px;
            margin-top: 50px;
            padding: 30px;
            border-radius: 15px;
            background-color: #E7E0EF;
        }

        .table-container { 
            padding: 20px;
            border-radius: 15px;
        }

        .action-buttons img { width: 25px; cursor: pointer; margin-right: 10px; }
        img.item-image { width: 80px; height: 80px; margin: 5px; border-radius: 10px; }
    </style>

    <script>
        function confirmDelete(productName, productId) {
            document.getElementById('deleteProductName').innerText = productName;
            document.getElementById('confirmDeleteBtn').setAttribute("data-id", productId);
            $('#deleteModal').modal('show');
        }

        function deleteProduct() {
            let productId = document.getElementById('confirmDeleteBtn').getAttribute("data-id");
            window.location.href = `products.php?id=${productId}`;
        }
    </script>
</head>
<body>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete <b><span id="deleteProductName"></span></b>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn" onclick="deleteProduct()">Delete</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column">
        <h2>Dashboard</h2>
    </br>
    </br>
        <a href="dashboard.php" class="nav-link">Dashboard</a>
        <a href="products.php" class="nav-link active">Products</a>
        <a href="categories.php" class="nav-link">Category</a>
        <a href="user.php" class="nav-link">Users</a>
        <a href="earnings.php" class="nav-link">Earnings</a>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <!-- Right Content -->
    <div class="container content-container">
        <h2 class="text-center">Products</h2>
        <a href="AddProduct.php" class="btn btn-success mb-3 float-right">Add Product</a>

        <div class="table-container">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
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
                        echo "<tr><td colspan='6' class='text-center'>No products found</td></tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
