<?php
// Include DB connection
require_once __DIR__ . "/../db/db.php";

// Fetch banking services data
$sql = "SELECT * FROM bankingservices";
$result = $conn->query($sql);

// Check query
if (!$result) {
    die("Query Failed: " . $conn->error);
}
?>

<!-- Include Navbar -->
<?php include "include/nav.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banking Services</title>

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
        .card {
            background: linear-gradient(to bottom, #9B7EBD, #3B1E54);
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.2s ease-in-out, box-shadow 0.3s;
            cursor: pointer;
            height: 100%;
        }
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.2);
        }
        .image-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
            overflow: hidden;
        }
        .card-img-top {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            border-radius: 10px;
        }
        .card-body {
            background: transparent !important;
            text-align: center;
        }
        .card-title {
            font-size: 1rem;
            font-weight: bold;
            color: white !important;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">Banking Services</h1>
    <div class="row">

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

                $image = base64_encode($row['Image']);
                $serviceName = $row['Name'];
                $serviceID = $row['BSID'];

                $sanitizedServiceName = str_replace(' ', '', $serviceName);
        ?>

        <div class="col-md-4 col-sm-6 mb-4">
            <a href="<?php echo $sanitizedServiceName; ?>-Details.php" class="text-decoration-none">
                <div class="card text-center">
                    <div class="image-container">
                        <img src="data:image/jpeg;base64,<?php echo $image; ?>" class="card-img-top" alt="<?php echo $serviceName; ?>">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $serviceName; ?></h5>
                    </div>
                </div>
            </a>
        </div>

        <?php
            }
        } else {
            echo "<div class='col-12 text-center text-danger'><h4>No banking services found.</h4></div>";
        }
        ?>

    </div>
</div>

<!-- Bootstrap JS -->
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