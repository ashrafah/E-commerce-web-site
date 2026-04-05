<?php
// Start the session
session_start();
error_reporting(0);

// Flag to check login status
$showPopup = false;

// Include DB connection
require_once __DIR__ . "/../db/db.php";

// Check if connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, set the flag to true to show the login/register modal
    $showPopup = true;
}

// Handle the form submission when POST request is made
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user ID from session
    $UID = $_SESSION['user_id'];

    // Set CID (assumed to be fixed as 6)
    $CID = 6;

    // Get form data
    $NoOfCopies = $_POST['NoOfCopies'];
    $Title = $_POST['Title'];
    $Description = isset($_POST['Description']) ? $_POST['Description'] : ''; // Optional field

    // Validate required fields
    if (empty($NoOfCopies) || empty($Title) || empty($_FILES['Document']['name'])) {
        echo '<div class="alert alert-danger">Please fill in all required fields.</div>';
    } else {
        // Get the file's binary data
        $fileData = file_get_contents($_FILES['Document']['tmp_name']);

        // Check for file upload errors
        if ($fileData === false) {
            echo '<div class="alert alert-danger">Error reading the uploaded file.</div>';
        } else {
            // Insert the file and other data into the database
            $stmt = $conn->prepare("INSERT INTO printouts (CID, UID, NoOfCopies, Title, Description, File) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiisss", $CID, $UID, $NoOfCopies, $Title, $Description, $fileData);

            if ($stmt->execute()) {
                echo '<div class="alert alert-success">Printout request has been submitted successfully.</div>';
            } else {
                echo '<div class="alert alert-danger">Error submitting printout request. Please try again.</div>';
            }

            // Close the prepared statement
            $stmt->close();
        }
    }
}

// Close the database connection when done
$conn->close();
?>

<!-- Include Navbar -->
<?php include "include/nav.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Galaxy | Printout</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        * {
            font-family: poppins ,sans-serif;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #fff;
        }

        .login-container {
            max-width: 900px;
            margin: 20px auto;
            background: #E7E0EF;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .login-form {
            padding: 30px;
        }

        .btn-login:hover {
            background-color: #36194C;
        }

        .logo-color {
            background-color: #D4BEE4;
        }
    </style>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Wait for the document to load
        $(document).ready(function() {
            <?php if ($showPopup): ?>
                // Show the popup if user is not logged in
                $('#loginModal').modal('show');
            <?php endif; ?>
        });
    </script>
</head>
<body>

<!-- Main content -->
<div class="login-container">
    <div class="row mt-5">
        <!-- PrintOuts Form Section -->
        <div class="col-12 col-md-12 p-5">
            <h2 class="text-center register-title fw-bold">PrintOuts</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 my-3">
                        <label for="Date" class="form-label fw-bold">Need Before</label>
                        <input type="date" class="form-control rounded-5" name="Date" id="Date" required>
                    </div>
                    <div class="col-md-6 my-3">
                        <label for="No. of copies" class="form-label fw-bold">Number of copies</label>
                        <input type="number" class="form-control rounded-5" name="NoOfCopies" id="NoOfCopies" required>
                    </div>
                </div>
                <div class="my-3">
                    <label for="Title" class="form-label fw-bold">Title</label>
                    <input type="text" class="form-control rounded-5" name="Title" id="Title" placeholder="Enter Title for your Printout" required>
                </div>

                <div class="my-3">
                    <label for="Description" class="form-label fw-bold">Description (Optional)</label>
                    <input type="text" class="form-control rounded-5" name="Description" id="Description" placeholder="Enter anything else to do">
                </div>

                <div class="my-3">
                    <label for="Document" class="form-label fw-bold">Upload Your Document:</label>
                    <input type="file" name="Document" id="Document" required>
                </div>

                <div class="d-grid my-3 d-flex justify-content-center pt-5">
                    <button type="submit" class="btn btn-primary w-50 rounded-4">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for login/register popup if user is not logged in -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Please Login or Register</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                You need to <strong>login</strong> or <strong>register</strong> to submit a printout request.
            </div>
            <div class="modal-footer">
                <a href="login.php" class="btn btn-primary">Login</a>
                <a href="register.php" class="btn btn-secondary">Register</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>

<!-- Include Footer -->
<?php include "include/footer.php"; ?>
