<?php

session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: ../Front-end/login.php"); // Redirect if not logged in as admin
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- chat js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <style>
        body { background-color: #F8F8FF; }

        /* Sidebar Styling */
        .sidebar {
            background-color: #E7E0EF;
            width: 250px;
            height: 100vh;
            position: fixed;
            padding: 20px;
            border-radius: 15px;
        }

        .nav-link { 
            color: white !important; 
            background-color: #472BE9; 
            border-radius: 15px; 
            margin-bottom: 10px; 
            padding: 10px; 
            text-align: center; 
            display: block;
            text-decoration: none;
        }

        .nav-link.active { background-color: #2BBDE9; }

        .logout-btn { 
            background-color: #D9534F; 
            color: white; 
            border-radius: 15px; 
            text-align: center; 
            padding: 10px; 
            display: block; 
            margin-top: auto;
            text-decoration: none;
        }

        .content-container {
            margin-left: 300px;
            margin-right: 50px;
            margin-top: 50px;
            padding: 30px;
            border-radius: 15px;
            background-color: #E7E0EF;
        }
        .dashboard-card {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .chart-container {
            width: 100%;
            max-width: 500px;
            margin: auto;
            height: 400px;
        }
    </style>

</head>
<body>

<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column">
        <h2>Dashboard</h2>
        <br><br>
        <a href="dashboard.php" class="nav-link active">Dashboard</a>
        <a href="products.php" class="nav-link">Products</a>
        <a href="categories.php" class="nav-link">Category</a>
        <a href="user.php" class="nav-link">Users</a>
        <a href="earnings.php" class="nav-link">Earnings</a>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

        <!-- Right Content -->
        <div class="container content-container">
            <h2 class="text-center">Dashboard</h2>
            

            <div class="row my-4 text-center">
            <div class="col-md-4">
                <div class="dashboard-card bg-white p-3">
                    <h5>Products</h5>
                    <h1 class="fw-bold">31</h1>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-card bg-white p-3">
                    <h5>Earnings</h5>
                    <h3 class="fw-bold">345,710.00</h3>
                    <p>Sri Lankan Rupees</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-card bg-white p-3">
                    <h5>Holds</h5>
                    <h1 class="fw-bold">04</h1>
                </div>
            </div>

            <div class="chart-container">
                    <canvas id="myChart"></canvas>
                </div>

                <script>
                    const ctx = document.getElementById('myChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['January', 'February', 'March', 'April', 'May'],
                            datasets: [{
                                label: 'Sales',
                                data: [10, 20, 15, 25, 30],
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: { beginAtZero: true }
                            }
                        }
                    });
                </script>


        </div>
        </div>
   
</div>



    
    

<!-- Bootstrap & jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
