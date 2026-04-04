<!-- nav bar -->
<?php include "include/nav.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Online Galaxy | Bank Services</title>

  <!-- Bootstrap css -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
        crossorigin="anonymous">

    <!-- custom css -->
    <style>
      @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');



      *{
          font-family: poppins ,sans-serif;
          margin: 0;
          padding: 0;
      }
      body {
          background-color: #fff;
      }

      .login-container{
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
</head>
<body>

  <div class="login-container mt-3">
    <!-- Login Form Section -->
    <div class="col-12 col-md-12 p-5 w-700 ">
      <h2 class="text-center register-title fw-bold py-3">Banking Services</h2>
      <!-- Form submits to payment.php with GET method -->
      <form id="bankingForm" action="payment.php" method="GET">
        
        <!-- Hidden input to send the type as "MoneyTransfer" -->
        <input type="hidden" name="Type" value="MoneyTransfer">

        <div class="row">
          <div class="col-md-6 my-3">
            <label for="rac" class="form-label fw-bold">Receiver's Account Number</label>
            <input type="text" class="form-control rounded-5" name="RAcc" id="RAcc" required>
          </div>
          <div class="col-md-6 my-3">
            <label for="rname" class="form-label fw-bold">Receiver's Name</label>
            <input type="text" class="form-control rounded-5" name="RName" id="RName" required>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 my-3">
            <label for="bank" class="form-label fw-bold">Bank</label>
            <select class="form-select rounded-5" name="Bank" id="Bank" required>
              <option value="">Select Your Bank</option>
              <option value="Peoples Bank">People's Bank</option>
              <option value="Bank of Ceylon">Bank of Ceylon</option>
              <option value="Sampath Bank">Sampath Bank</option>
            </select>
          </div>
          <div class="col-md-6 my-3">
            <label for="branch" class="form-label fw-bold">Branch</label>
            <select class="form-select rounded-5" name="Branch" id="Branch" required>
              <option value="">Select Your Branch</option>
              <option value="Kurunegala">Kurunegala</option>
              <option value="Colombo">Colombo</option>
              <option value="Maho">Maho</option>
            </select>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 my-3">
            <label for="sname" class="form-label fw-bold">Sender’s Name</label>
            <input type="text" class="form-control rounded-5" name="SName" id="SName" required>
          </div>
          <div class="col-md-6 my-3">
            <label for="snic" class="form-label fw-bold">Sender's NIC</label>
            <input type="text" class="form-control rounded-5" name="SNIC" id="SNIC" required>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 my-3">
            <label for="mobile" class="form-label fw-bold">Mobile Number</label>
            <input type="text" class="form-control rounded-5" name="Mobile" id="Mobile" value="+94 " required>
          </div>
          <div class="col-md-6 my-3">
            <label for="amount" class="form-label fw-bold">Amount</label>
            <input type="number" class="form-control rounded-5" name="Amount" id="Amount" placeholder="Enter Amount" required>
          </div>
        </div>

        <p class="text-center text-muted py-3">
          ** There will be an additional Charge of Rs.50.00 for each transfer **
        </p>

        <div class="d-grid my-3 d-flex justify-content-center">
          <button type="submit" class="btn btn-primary w-50 rounded-4">Proceed to Pay</button>
        </div>
      </form>
    </div>
  </div>
  <!-- log in form end -->

  <!-- JavaScript to calculate the total amount (entered value + Rs.50.00) -->
  <script>
    document.getElementById("bankingForm").addEventListener("submit", function(event) {
      // Get the entered amount from the input field
      var amountInput = document.getElementById("Amount");
      var enteredAmount = parseFloat(amountInput.value);
      
      // If the entered value is a number, add Rs.50.00 to it.
      if (!isNaN(enteredAmount)) {
        var totalAmount = enteredAmount + 50.00;
        // Update the input value to include the additional charge (formatted with 2 decimals)
        amountInput.value = totalAmount.toFixed(2);
      }
    });
  </script>

</body>
</html>

<!-- footer -->
<?php include "include/footer.php"; ?>
