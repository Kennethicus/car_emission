<?php
// Include necessary files and start the session
session_start();
include("../connect/connection.php");

// Check if the admin is logged in, if not, redirect to the login page
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}

// Fetch admin details based on the session information (you may customize this part)
$username = $_SESSION['admin'];
$query = "SELECT * FROM smurf_admin WHERE username = '$username'";
$result = $connect->query($query);

if ($result->num_rows == 1) {
    $admin = $result->fetch_assoc();
} else {
    // Handle the case where admin details are not found
    echo "Admin details not found!";
    exit();
}



// Query for booked bookings
$bookedQuery = "SELECT COUNT(*) as totalBooked FROM car_emission WHERE status = 'booked'";
$bookedResult = $connect->query($bookedQuery);
$totalBooked = ($bookedResult) ? $bookedResult->fetch_assoc()['totalBooked'] : 0;

// Query for doned bookings
$donedQuery = "SELECT COUNT(*) as totalDoned FROM car_emission WHERE status = 'doned'";
$donedResult = $connect->query($donedQuery);
$totalDoned = ($donedResult) ? $donedResult->fetch_assoc()['totalDoned'] : 0;

// Query for canceled bookings
$canceledQuery = "SELECT COUNT(*) as totalCanceled FROM car_emission WHERE status = 'canceled'";
$canceledResult = $connect->query($canceledQuery);
$totalCanceled = ($canceledResult) ? $canceledResult->fetch_assoc()['totalCanceled'] : 0;




// Initialize an array to store monthly booked counts
$monthlyBookedCounts = array_fill(1, 12, 0);

// Query for monthly booked counts with corresponding start_datetime
$monthlyBookedQuery = "SELECT MONTH(s.start_datetime) as month, COUNT(*) as count
                       FROM car_emission c
                       JOIN schedule_list s ON c.event_id = s.id
                       WHERE c.status = 'booked'
                       GROUP BY MONTH(s.start_datetime)";
$monthlyBookedResult = $connect->query($monthlyBookedQuery);

// Populate the array with fetched data
while ($row = $monthlyBookedResult->fetch_assoc()) {
    $month = $row['month'];
    $count = $row['count'];
    $monthlyBookedCounts[$month] = $count;
}




// Initialize an array to store monthly canceled counts
$monthlyCanceledCounts = array_fill(1, 12, 0);

// Query for monthly canceled counts with corresponding cancellation_timestamp
$monthlyCanceledQuery = "SELECT MONTH(cr.cancellation_timestamp) as month, COUNT(*) as count
                       FROM car_emission c
                       JOIN cancellation_reasons cr ON c.id = cr.booking_id
                       WHERE c.status = 'canceled'
                       GROUP BY MONTH(cr.cancellation_timestamp)";
$monthlyCanceledResult = $connect->query($monthlyCanceledQuery);

// Populate the array with fetched data
while ($row = $monthlyCanceledResult->fetch_assoc()) {
    $month = $row['month'];
    $count = $row['count'];
    $monthlyCanceledCounts[$month] = $count;
}

?>

<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Dashboard - Brand</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/Bootstrap-4-Calendar-No-Custom-Code.css">
    <link rel="stylesheet" href="assets/css/Bootstrap-Calendar.css">
    <link rel="stylesheet" href="assets/css/Continue-Button.css">
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include 'partials/nav.php'?>
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
       <?php include 'partials/nav-2.php' ?>
                <div class="container-fluid">
                    <div class="d-sm-flex justify-content-between align-items-center mb-4">
                        <h3 class="text-dark mb-0"><span style="color: rgb(31, 72, 43);">Analytics</span></h3><a class="btn btn-primary btn-sm d-none d-sm-inline-block" role="button" href="#"><i class="fas fa-download fa-sm text-white-50"></i>&nbsp;Generate Report</a>
                    </div>
                    <div class="row justify-content-evenly align-content-center">
                  <div class="col-md-6 col-xl-3 mb-4">
    <div class="card shadow border-start-success py-2" style="height: 150px; width: 280px;">
        <div class="card-body">
            <div class="row align-items-center no-gutters">
                <div class="col me-2 text-center">
                    <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span style="font-size: 20px;">NEW BOOKING</span></div>
                    <div class="text-dark fw-bold h5 mb-0"><span><?php echo $totalBooked; ?></span></div>
                </div>
            </div>
        </div>
    </div>
</div>

                        <div class="col-md-6 col-xl-3 mb-4">
    <div class="card shadow border-start-success py-2" style="height: 150px; width: 280px;">
        <div class="card-body">
            <div class="row align-items-center no-gutters">
                <div class="col me-2 text-center">
                    <div class="text-uppercase text-success fw-bold text-xs mb-1"><span style="font-size: 20px;">FINISH BOOKING</span></div>
                    <div class="text-dark fw-bold h5 mb-0"><span><?php echo $totalDoned; ?></span></div>
                </div>
            </div>
        </div>
    </div>
</div>

                        <div class="col-md-6 col-xl-3 mb-4">
                            <div class="card shadow border-start-info py-2" style="height: 150px;width: 280px;">
                                <div class="card-body">
                                    <div class="row align-items-center no-gutters">
                                        <div class="col me-2">
                                            <div class="text-uppercase text-info fw-bold text-xs mb-1" style="text-align: center;"><span style="font-size: 20px;text-align: justify;">CANCELED BOOKING</span></div>
                                        </div>
                                    </div>
                                    <div class="text-dark fw-bold h5 mb-0" style="text-align: center;"><span style="text-align: center;"><?php echo $totalCanceled; ?></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                   


                    <canvas id="lineChart"></canvas>


                </div>
            </div>
            <?php include 'partials/footer.php'?>
        </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/chart.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/theme.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    var ctx = document.getElementById('lineChart').getContext('2d');

    var lineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [{
                label: 'Booked Counts',
                data: <?php echo json_encode(array_values($monthlyBookedCounts)); ?>,
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                fill: false
            },
            {
                label: 'Canceled Counts',
                data: <?php echo json_encode(array_values($monthlyCanceledCounts)); ?>,
                borderColor: 'rgba(255, 0, 0, 1)',
                borderWidth: 2,
                fill: false
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>

</body>

</html>