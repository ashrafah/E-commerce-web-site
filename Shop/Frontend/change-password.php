<?php
// Start the session to access session data
session_start();
error_reporting(0);

// Initialize the message variable
$message = '';

// Check if UID is set in the session
if (!isset($_SESSION['user_id'])) {
    echo "You need to log in first.";
    exit();
}

// Include DB connection
require_once __DIR__ . "/../db/db.php";

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch the UID from the session
$uid = $_SESSION['user_id'];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentPassword = mysqli_real_escape_string($conn, $_POST['current_password']);
    $newPassword = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Fetch the user's current password from the database
    $query = "SELECT Password FROM users WHERE UID = '$uid'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    // Check if the current password matches the stored password
    if ($currentPassword === $user['Password']) { // No hashing for comparison
        // Check if the new password and confirm password match
        if ($newPassword === $confirmPassword) {
            // Update the password in the database (without hashing)
            $updateQuery = "UPDATE users SET Password = '$newPassword' WHERE UID = '$uid'";
            if (mysqli_query($conn, $updateQuery)) {
                $message = "Password updated successfully.";
            } else {
                $message = "Error updating password: " . mysqli_error($conn);
            }
        } else {
            $message = "New password and confirm password do not match.";
        }
    } else {
        $message = "Current password is incorrect.";
    }
}

?>

<!-- Include Navbar -->
<?php include "include/nav.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        
        * {
            font-family: poppins, sans-serif;
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

        .log-in-container {
            background: #E7E0EF;
            max-width: 600px;
            padding: 50px;
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

        /* Centering the modal content in the viewport */
        .modal-dialog {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .modal-content {
            width: 100%;
            max-width: 500px;
            margin: auto;
            text-align: center; /* Center the text */
        }

        /* Styling for the close button */
        .btn-close {
            background-color: #dc3545; /* Change this to any color you prefer */
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            border-radius: 4px;
        }

        /* Ensure the modal text is centered */
        .modal-header, .modal-body {
            text-align: center;
        }

        /* Remove the 2 lines (borders) inside modal */
        .modal-header {
            border-bottom: none;
        }

        .modal-body {
            border-top: none;
        }
    </style>

    <!-- Add the JS for Bootstrap Modal -->
    <script>
        // Display the message in a modal if there is any message
        window.onload = function() {
            var message = "<?php echo $message; ?>";
            if (message !== '') {
                // Show the modal with the message
                document.getElementById('messageModalLabel').innerText = message;
                document.getElementById('messageContent').innerText = message; // Set content of message
                var myModal = new bootstrap.Modal(document.getElementById('messageModal'));
                myModal.show();
            }
        };
    </script>
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center mt">
        <div class="log-in-container py-3 shadow rounded-4 w-100">
            <h3 class="text-center">Change Password</h3>
            <form action="" method="POST">
                <div class="row my-4">
                    <label class="col-md-4 col-form-label fw-light text-start">Current Password:</label>
                    <div class="col-md-8">
                        <input type="password" class="form-control rounded-5" name="current_password" required>
                    </div>
                </div>

                <div class="row my-4">
                    <label class="col-md-4 col-form-label fw-light text-start">New Password:</label>
                    <div class="col-md-8">
                        <input type="password" class="form-control rounded-5" name="new_password" required>
                    </div>
                </div>

                <div class="row my-4">
                    <label class="col-md-4 col-form-label fw-light text-start">Confirm New Password:</label>
                    <div class="col-md-8">
                        <input type="password" class="form-control rounded-5" name="confirm_password" required>
                    </div>
                </div>

                <div class="row my-4 text-center">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary w-75">Update Password</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Modal -->
    <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- The message will be displayed here dynamically -->
                    <p id="messageContent"></p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</body>

</html>

<!-- Include Footer -->
<?php include "include/footer.php"; ?>
