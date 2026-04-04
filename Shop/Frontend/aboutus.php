
<!-- Include Navbar -->
<?php include "include/nav.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>


    <style>
        .logo1{
            height: 100px;
        }
        .about-section{
            background-color: #2F1843;
            color: #fff;
        }
        .card{
            background-color: #E7E0EF;
        }
        .card-icon{
            width: 50px;
            height: 50px;
        }
        .bg-color{
            background-color: #00FFFF;
        }
        .text-justify{
            text-align: justify;
            text-justify: inter-word;
        }
    </style>

    <!-- Bootstarp css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
<div class="container">
    <!-- jad coders logo start -->
    <div class="col-12 bg-black m-2 d-flex justify-content-center rounded-4 logo1">
        <img src="../assets/aboutus/JAD-Codes-Logo.ico" alt="" class="img-fluid">
    </div>
    <!-- jad coder end -->

    
    <!-- about paragarph start -->
    <div class="col-12 row">
        <div class="col-md-8">
            <div class="about-section p-5 mt-3 rounded-5 ">
                <h1 class="">About Us</h2>
                    <br>
                <p class="mt-3 text-justify">Welcome to Online Galaxy, your one-stop destination for all your essential needs! Located in the heart of Yapahuwa Junction, Maho,we offer a wide range of services, including mobile reloads, bill payments, banking services, life insurance payments, photocopying, printing, and the sale of brand-new and used mobile phones and accessories.Our extensive collection of accessories includes memory chips, pen-drives, batteries, chargers, tempered glasses, back covers, earphones, and mini speakers. </p><br>
                <p class="mt-3 text-justify">
                    At Online Galaxy, we pride ourselves on providing efficient, reliable, <br>
                    and customer-friendly services to meet your everyday needs. <br>
                    With a focus on quality and convenience, <br>
                    we are here to make your life easier. <br>
                    Visit us today and experience the difference!
                </p>
            </div>
        </div>
        <!-- about paragraph end -->


        <!-- short card start -->
        <div class="col-md-4">
            <div class="row fcard my-3 p-3 shadow rounded-4 d-flex align-items-center">
                <div class="col-4 text-center">
                    <img src="../assets/aboutus/delivary.png" alt="Free Delivery" class="img-fluid shadow rounded-4 bg-color">
                </div>
                <div class="col-8">
                    <h5 class="fw-bold text-dark">FREE DELIVERY</h5>
                    <p class="text-muted mb-0">Free Shipping on all orders</p>
                </div>
            </div>

            <div class="row fcard my-3 p-3 shadow rounded-4 d-flex align-items-center">
                <div class="col-4 text-center">
                    <img src="../assets/aboutus/return.png" alt="return" class="img-fluid shadow rounded-4 bg-color">
                </div>
                <div class="col-8">
                    <h5 class="fw-bold text-dark">RETURNS</h5>
                    <p class="text-muted mb-0">Back guarantee under 7 days</p>
                </div>
            </div>

            <div class="row fcard my-3 p-3 shadow rounded-4 d-flex align-items-center">
                <div class="col-4 text-center">
                    <img src="../assets/aboutus/support.png" alt="support" class="img-fluid shadow rounded-4 bg-color">
                </div>
                <div class="col-8">
                    <h5 class="fw-bold text-dark">SUPPORT 24/7</h5>
                    <p class="text-muted mb-0">Free Shipping on all orders</p>
                </div>
            </div>

            <div class="row fcard my-3 p-3 shadow rounded-4 d-flex align-items-center">
                <div class="col-4 text-center">
                    <img src="../assets/aboutus/payment.png" alt="Free Delivery" class="img-fluid shadow rounded-4 bg-color">
                </div>
                <div class="col-8">
                    <h5 class="fw-bold text-dark">PAYMENT</h5>
                    <p class="text-muted mb-0">100% Payment Security</p>
                </div>
            </div>

        </div>

        <!-- short card end -->

            

           

        
    </div>
</div>
</body>
</html>

<!-- Include Footer -->
<?php include "include/footer.php"; ?>