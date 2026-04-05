<?php
// Include DB connection
require_once __DIR__ . "/../db/db.php";

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch categories data from the database
$sql = "SELECT * FROM networkproviders";
$result = $conn->query($sql);
?>

<!-- Include Navbar -->
<?php include "include/nav.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Reload</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #F3EFF7;
        }
        .card-custom {
            background: linear-gradient(to bottom, #9B7EBD, #3B1E54);
            color: white;
            border: none;
            height: 100%;
        }
        .card-custom img {
            object-fit: cover;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            height: 300px;
        }
        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
        }
        .card-title {
            color: white !important; /* Ensures the text is white */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Mobile Reload</h1>
        <div class="row g-4">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $image = base64_encode($row['Image']);
                    $networkproviders = $row['Name'];
            ?>
                    <div class="col-md-4 col-sm-6 d-flex">
                        <!-- Redirecting to MobileReload-Details.php with the Name parameter -->
                        <a href="MobileReload-Details.php?Name=<?= urlencode($networkproviders) ?>" class="w-100 text-decoration-none">
                            <div class="card card-custom shadow-sm">
                                <img src="data:image/jpeg;base64,<?= $image ?>" class="card-img-top" alt="<?= $networkproviders ?>">
                                <div class="card-body">
                                    <h5 class="card-title text-center"><?= $networkproviders ?></h5>
                                </div>
                            </div>
                        </a>
                    </div>
            <?php
                }
            } else {
                echo "<p class='text-center'>No categories found.</p>";
            }
            ?>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

<!-- Include Footer -->
<?php include "include/footer.php"; ?>

<?php
$conn->close();
?>
