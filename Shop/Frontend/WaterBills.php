<?php
// Include DB connection
require_once __DIR__ . "/../db/db.php";

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get BPID from the URL
$BPID = isset($_GET['BPID']) ? $_GET['BPID'] : '';

// Check if BPID is provided
if ($BPID != '') {
    // Fetch insurance data based on BPID
    $sql = "SELECT * FROM services WHERE BPID = '$BPID'";
    $result = $conn->query($sql);
} else {
    // If no BPID provided, show an error message or handle accordingly
    echo "<div class='col-12 text-center text-danger'><h4>BPID not found.</h4></div>";
    exit; // Stop the execution if BPID is not found
}
?>

<!-- Include Navbar -->
<?php include "include/nav.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insurance</title>
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
            object-fit: contain; /* Prevents distortion */
            border-radius: 10px;
        }
        .card-body {
            background: transparent !important;
            text-align: center;
        }
        .card-title {
            font-size: 1rem;
            font-weight: bold;
            color: white !important; /* ✅ Fix: Insurance name in white */
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">Water Bill</h1>
    <div class="row">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $image = base64_encode($row['Image']);
                $insuranceName = $row['Name'];
                $SeviceID = $row['SID'];

                echo '<div class="col-md-4 col-sm-6 mb-4">';
                echo '<a href="WaterBill-Details.php?SID=' . $SeviceID . '" class="text-decoration-none">';
                echo '<div class="card text-center">';
                echo '<div class="image-container">';
                echo '<img src="data:image/jpeg;base64,' . $image . '" class="card-img-top" alt="' . $insuranceName . '">';
                echo '</div>';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . $insuranceName . '</h5>';
                echo '</div>';
                echo '</div>';
                echo '</a>';
                echo '</div>';
            }
        } else {
            echo "<div class='col-12 text-center text-danger'><h4>No insurance plans found for the given BPID.</h4></div>";
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
