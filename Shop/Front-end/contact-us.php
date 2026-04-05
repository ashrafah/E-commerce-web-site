<?php
session_start(); // Start session at the very top to access session variables
error_reporting(0);

// Include the Composer autoload file
require '../vendor/autoload.php';  // Adjust the path to 'autoload.php' based on your folder structure

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

// Include DB connection
require_once __DIR__ . "/../db/db.php";

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Ensure the user is logged in and the session contains a valid UID
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if user is not logged in
    exit();
}

$uid = $_SESSION['user_id']; // Get the user ID from the session

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = htmlspecialchars($_POST["first_name"]);
    $last_name = htmlspecialchars($_POST["last_name"]);
    $email = htmlspecialchars($_POST["email"]);
    $phone = htmlspecialchars($_POST["phone"]);
    $message = htmlspecialchars($_POST["message"]);

    // Validate input
    if (empty($first_name) || empty($last_name) || empty($email) || empty($message)) {
        header("Location: contact-us.php?status=error&message=Please%20fill%20in%20all%20required%20fields!");
        exit();
    } else {
        // Insert data into contacts table
        $stmt = $conn->prepare("INSERT INTO contacts (UID, first_name, last_name, email, phone, message) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $uid, $first_name, $last_name, $email, $phone, $message);
        $stmt->execute();
        $stmt->close();

        // Send email using Symfony Mailer
        try {
            // Create an SMTP transport instance using Gmail details with App Password
            $dsn = 'smtp://onlinegalaxy40@gmail.com:rsam%20wvzn%20zxdo%20ndwr@smtp.gmail.com:587';  // App password URL encoded

            // Create the mailer instance using the DSN
            $transport = Transport::fromDsn($dsn);
            $mailer = new Mailer($transport);

            // Create the email
            $email_message = (new Email())
                ->from('onlinegalaxy40@gmail.com')
                ->to('onlinegalaxy40@gmail.com')  // Your email address to receive the form submissions
                ->subject('New Contact Form Submission')
                ->text("Name: $first_name $last_name\nEmail: $email\nPhone: $phone\nMessage:\n$message");

            // Send the email
            $mailer->send($email_message);

            // Redirect with success message
            header("Location: contact-us.php?status=success&message=Message%20sent%20successfully!");
            exit();
        } catch (\Symfony\Component\Mailer\Exception\TransportExceptionInterface $e) {
            // Redirect with error message if sending email fails
            header("Location: contact-us.php?status=error&message=Failed%20to%20send%20message.%20Error%20Message:%20" . urlencode($e->getMessage()));
            exit();
        }
    }
}
?>

<!-- Include Navbar -->
<?php include "include/nav.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Contact Us</title>
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" />

  <style>
    body {
      background-color: #f3eff7;
      font-family: Arial, sans-serif;
    }
    .container {
        max-width: 1000px;
    }
    .contact-container {
      background: #e8e1f3;
      border-radius: 20px;
      padding: 30px;
    }
    .contact-container h2 {
      text-align: center;
      color: #3b1e54;
      font-weight: bold;
      margin-bottom: 20px;
    }
    .form-label {
      font-weight: 600;
      color: #333;
    }
    .form-control {
      border-radius: 10px;
    }
    .submit-btn {
      background-color: #5a3cc1;
      border: none;
      border-radius: 10px;
      color: #fff;
      padding: 10px 20px;
      width: 100%;
    }
    .info-box {
      background: #e8e1f3;
      border-radius: 10px;
      padding: 15px;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .info-box img {
      width: 75px;
      height: 75px;
    }
  </style>
</head>

<body>
  <div class="container py-5">
    <div class="row g-4">
      <!-- Left Column: Contact Form -->
      <div class="col-md-6">
        <div class="contact-container">
          <h2>Contact Us</h2>
          <!-- Show success or error message -->
          <?php if (isset($_GET['status'])): ?>
            <div class="alert alert-<?php echo $_GET['status'] == 'success' ? 'success' : 'danger'; ?> text-center">
              <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
          <?php endif; ?>
          <form action="" method="POST">
            <div class="mb-3">
              <label class="form-label">Name</label>
              <div class="row">
                <div class="col">
                  <input type="text" name="first_name" class="form-control" placeholder="First Name" required />
                </div>
                <div class="col">
                  <input type="text" name="last_name" class="form-control" placeholder="Last Name" required />
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" placeholder="your@email.com" required />
            </div>

            <div class="mb-3">
              <label class="form-label">Phone</label>
              <input type="text" name="phone" class="form-control" placeholder="+94" required />
            </div>

            <div class="mb-3">
              <label class="form-label">Message</label>
              <textarea name="message" class="form-control" rows="4" placeholder="Type Here..." required></textarea>
            </div>

            <button type="submit" class="submit-btn">SUBMIT</button>
          </form>
        </div>
      </div>

      <!-- Right Column: Contact Info -->
      <div class="col-md-6">
        <div class="mb-4">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.6295762020563!2d80.29885577366298!3d7.828971992192008!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3afccf94f0d6d76d%3A0x98083ce1d494c3de!2sOnline%20Galaxy!5e0!3m2!1sen!2slk!4v1741912588490!5m2!1sen!2slk" 
            width="100%" height="200" style="border:0;" allowfullscreen=""
            loading="lazy" class="rounded-5"></iframe>
        </div>

        <div class="info-box">
          <img src="../assets/contact/phone-con.png" alt="">
          <div><b>077 5050 505<br>071 2345 678</b></div>
        </div>

        <div class="info-box">
          <img src="../assets/contact/mail-con.png" alt="">
          <div><b>onlinegalaxy505@gmail.com</b></div>
        </div>

        <div class="info-box">
          <img src="../assets/contact/map-con.png" alt="">
          <div><b>Kingdom Streets, Yahapahuwa Junction, Maho.</b></div>
        </div>
      </div>
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
