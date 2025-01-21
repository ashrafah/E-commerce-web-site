<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- Bootstrap css -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <!-- Custom Css file -->
  <link rel="stylesheet" href="./css/nav-style.css">

</head>
<body>
  <!-- nav bar start -->
  
  <!-- top header start -->
  <div class="navbar-custom py-2">
    <div class="container d-flex justify-content-between">
      <span class="fw-bold">Dream in Touch</span>
      <span>
        <i class="bi bi-telephone"></i> <a href="#" class="text-white">+94 77 9197191 </a>
        <span>|</span>
        <i class="bi bi-geo-alt"></i> <a href="#" class="text-white">Location</a>
      </span>
    </div>
  </div>
  <!-- top header end -->



  <!-- main nav bar start -->
  <div class="navbar-search py-3 sticky-top">
    <div class="container d-flex align-items-center justify-content-between">
      <!-- Logo -->
      <div class="d-flex align-items-center logo">
        <img src="../assets/nav-bar-icons/nav-logo.png" alt="Logo" style="height: 50px;">
      </div>

      <!-- Search Bar -->
      <div class="container-fluid">
        <div class="row">
          <div class="col-12 col-md-8 col-lg-6 mx-auto my-2">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Search...">
              <button class="btn" type="button">
                <img src="../assets/nav-bar-icons/search.svg" alt="Search">
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Icons -->
      <div class="d-flex align-items-center justify-content-end gap-3 ">
        <a href="" class="navbar-icon">
          <img src="../assets/nav-bar-icons/cart-icon.svg" alt="">
        </a>
        <a href="" class="navbar-icon">
          <img src="../assets/nav-bar-icons/message-bell-icon.svg" alt="">
        </a>
        <a href="" class="navbar-icon">
          <img src="../assets/nav-bar-icons/log-icon.svg" alt="">
        </a>
      </div>
    </div>
  </div>
   
  <!-- secondary nav bar start -->

  <nav class="navbar navbar-expand-lg sticky-top" style="background-color: #D4BEE4">
    <div class="container">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mx-auto my-2 my-lg-0 navbar-nav-scroll">
          <li class="nav-item">
            <a class="nav-link" href="#home">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Category</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Offer</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Contact</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">About</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- secondary nav bar end -->

  <!-- main nav bar end -->

  <!-- nav bar end -->




  <!-- Bootstrap js -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>