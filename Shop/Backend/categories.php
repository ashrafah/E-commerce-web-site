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

// Check if 'id' is set in URL to delete category
if (isset($_GET['id'])) {
    $categoryId = intval($_GET['id']); // Ensure it's an integer

    // SQL query to delete category
    $sql = "DELETE FROM categories WHERE CID = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $categoryId);
        if ($stmt->execute()) {
            header("Location: categories.php"); // Redirect after deletion
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }
        $stmt->close();
    }
}

// Fetch categories from database
$sql = "SELECT CID, Image, Name FROM categories";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #F8F8FF;
        }

        .sidebar {
            background-color: #E7E0EF;
            width: 250px;
            height: 100vh;
            position: fixed;
            padding: 20px;
            border-radius: 15px;
        }

        .sidebar h2 {
            text-align: center;
        }

        .nav-link {
            color: white !important;
            background-color: #472BE9;
            border-radius: 15px;
            margin-bottom: 10px;
            padding: 10px;
            text-align: center;
        }

        .nav-link.active {
            background-color: #2BBDE9;
        }

        .logout-btn {
            background-color: #D9534F;
            color: white;
            border-radius: 15px;
            text-align: center;
            padding: 10px;
            display: block;
            margin-top: auto;
        }

        .content-container {
            margin-left: 300px;
            margin-right: 50px;
            margin-top: 50px;
            padding: 30px;
            border-radius: 15px;
            background-color: #E7E0EF;
        }

        .category-image {
            width: 150px;
            height: 120px;
        }

        .action-buttons img {
            width: 25px;
            cursor: pointer;
            margin-right: 10px;
        }
    </style>

    <script>
        function confirmDelete(categoryName, categoryId) {
            document.getElementById('deleteCategoryName').innerText = categoryName;
            document.getElementById('confirmDeleteBtn').setAttribute("data-id", categoryId);
            $('#deleteModal').modal('show'); // Use Bootstrap modal
        }

        function deleteCategory() {
            let categoryId = document.getElementById('confirmDeleteBtn').getAttribute("data-id");
            window.location.href = `categories.php?id=${categoryId}`;
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
                Are you sure you want to delete <b><span id="deleteCategoryName"></span></b>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn" onclick="deleteCategory()">Delete</button>
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
        <a href="products.php" class="nav-link">Products</a>
        <a href="categories.php" class="nav-link active">Category</a>
        <a href="user.php" class="nav-link">Users</a>
        <a href="earnings.php" class="nav-link">Earnings</a>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <!-- Right Content -->
    <div class="container content-container">
        <h2 class="text-center">Categories</h2>
        <a href="AddCategory.php" class="btn btn-success mb-3 float-right">Add Category</a>

        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
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
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['CID'] . "</td>";
                        echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['Image']) . "' class='category-image' alt='Category'></td>";
                        echo "<td>" . $row['Name'] . "</td>";
                        echo "<td class='action-buttons'>";
                        echo "<a href='editcategory.php?id=" . $row['CID'] . "'><img src='img/edit.png' alt='Edit'></a>";
                        echo "<a href='#' onclick='confirmDelete(\"" . $row['Name'] . "\", " . $row['CID'] . ")'><img src='img/delete.png' alt='Delete'></a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>No categories found</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
