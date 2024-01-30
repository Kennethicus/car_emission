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


// Initialize an array to store monthly doned counts
$monthlyDonedCounts = array_fill(1, 12, 0);

// Query for monthly doned counts with corresponding payment_date and year
$monthlyDonedQuery = "SELECT MONTH(payment_date) as month, COUNT(*) as count
                      FROM car_emission
                      WHERE status = 'doned'
                      GROUP BY MONTH(payment_date)";
$monthlyDonedResult = $connect->query($monthlyDonedQuery);

// Populate the array with fetched data considering both year and month
while ($row = $monthlyDonedResult->fetch_assoc()) {
    $month = $row['month'];
    $count = $row['count'];
    $monthlyDonedCounts["$month"] = $count;
}



// Initialize an array to store monthly unpaid counts
$monthlyUnpaidCounts = array_fill(1, 12, 0);

// Query for monthly unpaid counts with corresponding start_datetime
$monthlyUnpaidQuery = "SELECT MONTH(s.start_datetime) as month, COUNT(*) as count
                       FROM car_emission c
                       JOIN schedule_list s ON c.event_id = s.id
                       WHERE c.paymentStatus = 'unpaid'
                       GROUP BY MONTH(s.start_datetime)";
$monthlyUnpaidResult = $connect->query($monthlyUnpaidQuery);

// Populate the array with fetched data
while ($row = $monthlyUnpaidResult->fetch_assoc()) {
    $month = $row['month'];
    $count = $row['count'];
    $monthlyUnpaidCounts[$month] = $count;
}



// Initialize an array to store monthly half paid counts
$monthlyHalfPaidCounts = array_fill(1, 12, 0);

// Query for monthly half paid counts with corresponding start_datetime
$monthlyHalfPaidQuery = "SELECT MONTH(s.start_datetime) as month, COUNT(*) as count
                         FROM car_emission c
                         JOIN schedule_list s ON c.event_id = s.id
                         WHERE c.paymentStatus = 'half paid'
                         GROUP BY MONTH(s.start_datetime)";
$monthlyHalfPaidResult = $connect->query($monthlyHalfPaidQuery);

// Populate the array with fetched data
while ($row = $monthlyHalfPaidResult->fetch_assoc()) {
    $month = $row['month'];
    $count = $row['count'];
    $monthlyHalfPaidCounts[$month] = $count;
}


// Initialize an array to store monthly fully paid counts
$monthlyFullyPaidCounts = array_fill(1, 12, 0);

// Query for monthly fully paid counts with corresponding start_datetime
$monthlyFullyPaidQuery = "SELECT MONTH(s.start_datetime) as month, COUNT(*) as count
                         FROM car_emission c
                         JOIN schedule_list s ON c.event_id = s.id
                         WHERE c.paymentStatus = 'fully paid'
                         GROUP BY MONTH(s.start_datetime)";
$monthlyFullyPaidResult = $connect->query($monthlyFullyPaidQuery);

// Populate the array with fetched data
while ($row = $monthlyFullyPaidResult->fetch_assoc()) {
    $month = $row['month'];
    $count = $row['count'];
    $monthlyFullyPaidCounts[$month] = $count;
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
                <div class="row">    
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="mb-3">
    <label for="selectYear" class="form-label">Select Year:</label>
    <select class="form-select" id="selectYear">
        <?php
        $currentYear = date('Y');
        for ($year = $currentYear; $year >= $currentYear - 5; $year--) {
            echo "<option value=\"$year\">$year</option>";
        }
        ?>
    </select>
</div>
                </div>
                <div class="col-md-6 col-xl-3 mb-4">
<div class="mb-3">
    <label for="selectMonth" class="form-label">Select Month:</label>
    <select class="form-select" id="selectMonth">
        <option value="1">January</option>
        <option value="2">February</option>
        <option value="3">March</option>
        <option value="4">April</option>
        <option value="5">May</option>
        <option value="6">June</option>
        <option value="7">July</option>
        <option value="8">August</option>
        <option value="9">September</option>
        <option value="10">October</option>
        <option value="11">November</option>
        <option value="12">December</option>
    </select>   
</div>
</div>
                </div>
<div class="w-100">
    <div class="row">
    <div class="col-md-5 col-sm-6 col-lg-6" >
    <!-- By year -->
    <canvas id="lineChart"  ></canvas>
</div>


        <div class="col-md-6 col-sm-6 col-lg-6">
            <canvas id="monthlyLineChart"></canvas>
        </div>
    </div>
</div>

<div class="w-100">
<div class="row">
                    <div class="col-md-6 col-sm-6 col-lg-6">
    <canvas id="monthlyBarGraph"></canvas>
</div>
<div class="col-md-6 col-sm-6 col-lg-6">
    <canvas id="mostDoneByDayVehicle"></canvas>
</div>
</div>
</div>

<div class="w-100">
<div class="row">
                    <div class="col-md-6 col-sm-6 col-lg-6">
    <canvas id="monthlyCanceledBarGraph"></canvas>
</div>
<div class="col-md-6 col-sm-6 col-lg-6">
    <canvas id="mostCanceledByDayVehicle"></canvas>
</div>
</div>
</div>
<div class="w-100">
<div class="row">
                    <div class="col-md-6 col-sm-6 col-lg-6">
    <canvas id="gainLossBarChart"></canvas>
</div>
<div class="col-md-6 col-sm-6 col-lg-6">
    <canvas id="gainLossDayLineChart"></canvas>
</div>
</div>
</div>
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
    // Customize the width and height of the chart
    var chartWidth = 1000; // Set your custom width
    var chartHeight = 400; // Set your custom height

    var ctx = document.getElementById('lineChart').getContext('2d');
    ctx.canvas.width = chartWidth;
    ctx.canvas.height = chartHeight;

    // Function to fetch data for the selected year
    function fetchData(selectedYear) {
        var fetchDataUrl = 'fetch_year.php?year=' + selectedYear; // Change this URL to your server-side script

        fetch(fetchDataUrl)
            .then(response => response.json())
            .then(data => {
                updateChart(data);
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
    }

    // Initial data for the chart (you can update this with actual data from your PHP script)
    var initialData = {
        datasets: [{
                label: 'Booked Counts',
                data: [],
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                fill: false
            },
            {
                label: 'Canceled Counts',
                data: [],
                borderColor: 'rgba(255, 0, 0, 1)',
                borderWidth: 2,
                fill: false
            },
            {
                label: 'Doned Counts',
                data: [],
                borderColor: 'rgba(0, 128, 0, 1)',
                borderWidth: 2,
                fill: false
            },
            {
                label: 'Unpaid Counts',
                data: [],
                borderColor: 'rgba(255, 165, 0, 1)',
                borderWidth: 2,
                fill: false
            },
            {
                label: 'Half Paid Counts',
                data: [],
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2,
                fill: false
            },
            {
                label: 'Full Payment Counts',
                data: [],
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2,
                fill: false
            }
        ]
    };

    // Create a chart instance with initial data
    var lineChart = new Chart(ctx, {
        type: 'line',
        data: initialData,
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    // suggestedMin: 0,
                    // suggestedMax: 50 
                }
            },
            plugins: {
                legend: {
                    position: 'left',
                    align: 'start',
                    labels: {
                        boxWidth: 10,
                        padding: 20
                    }
                }
            }
        }
    });

    // Add a header above the chart
    var chartContainer = document.getElementById('lineChart').parentNode;
    var header = document.createElement('h2');
    header.textContent = 'Months';
    chartContainer.insertBefore(header, chartContainer.firstChild);

    // Event listener for the change in the dropdown
    document.getElementById('selectYear').addEventListener('change', function () {
        var selectedYear = this.value;
        fetchData(selectedYear);
    });

   // Function to update the chart with new data
function updateChart(data) {
    lineChart.data.datasets[0].data = data.monthlyBookedCounts;
    lineChart.data.datasets[1].data = data.monthlyCanceledCounts;
    lineChart.data.datasets[2].data = data.monthlyDonedCounts;
    lineChart.data.datasets[3].data = data.monthlyUnpaidCounts;
    lineChart.data.datasets[4].data = data.monthlyHalfPaidCounts;
    lineChart.data.datasets[5].data = data.monthlyFullyPaidCounts; // Add Fully Paid counts
    lineChart.update();
}


    // Initial fetch with the current year
    var currentYear = new Date().getFullYear();
    fetchData(currentYear);
});
</script>



<script>
$(document).ready(function() {
    // Function to fetch data and update chart
    function updateChart() {
        var selectedMonth = $('#selectMonth').val();
        var selectedYear = $('#selectYear').val();

        $.ajax({
            url: 'dailyBookedCount.php',
            type: 'GET',
            dataType: 'json',
            data: {year: selectedYear, month: selectedMonth},
            success: function(data) {
                // Update chart with new data
                updateMonthlyLineChart(data);
            },
            error: function(xhr, status, error) {
                console.error('Failed to fetch data');
            }
        });
    }

    // Function to update the monthly line chart
    function updateMonthlyLineChart(data) {
        var ctx = document.getElementById('monthlyLineChart').getContext('2d');
        if(window.myChart !== undefined)
            window.myChart.destroy();
        window.myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Booked Counts',
                    data: data.dailyBookedCounts,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }, {
                    label: 'Canceled Counts',
                    data: data.dailyCanceledCounts,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }, {
                    label: 'Done Counts',
                    data: data.dailyDonedCounts,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }, {
                    label: 'Fully Paid Counts', // Add label for Fully Paid Counts
                    data: data.dailyFullyPaidCounts, // Add data for Fully Paid Counts
                    backgroundColor: 'rgba(255, 205, 86, 0.2)',
                    borderColor: 'rgba(255, 205, 86, 1)',
                    borderWidth: 1
                }, {
                    label: 'Half Paid Counts', // Add label for Half Paid Counts
                    data: data.dailyHalfPaidCounts, // Add data for Half Paid Counts
                    backgroundColor: 'rgba(255, 159, 64, 0.2)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1
                }, {
                    label: 'Unpaid Counts', // Add label for Unpaid Counts
                    data: data.dailyUnpaidCounts, // Add data for Unpaid Counts
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        position: 'left',
                        align: 'start',
                        labels: {
                            boxWidth: 10,
                            padding: 20
                        }
                    }
                }
            }
        });
    }

    // Add a header above the chart
    var chartContainer = $('#monthlyLineChart').parent();
    var header = $('<h2>Days</h2>');
    chartContainer.prepend(header);

    // Initial chart update
    updateChart();

    // Event listener for month and year change
    $('#selectMonth, #selectYear').change(updateChart);
});
</script>


<script>
$(document).ready(function() {
    // Function to fetch data and update chart
    function updateChart() {
        var selectedYear = $('#selectYear').val();

        $.ajax({
            url: 'barChartData.php',
            type: 'GET',
            dataType: 'json',
            data: {year: selectedYear},
            success: function(data) {
                // Update bar graph with new data
                updateBarGraph(data);
            },
            error: function(xhr, status, error) {
                console.error('Failed to fetch bar chart data');
            }
        });
    }

    // Function to update the bar graph
    function updateBarGraph(data) {
        // Clear previous chart instance if exists
        if(window.barGraph !== undefined)
            window.barGraph.destroy();

        // Get context of canvas element
        var ctx = document.getElementById('monthlyBarGraph').getContext('2d');

        // Extract labels (months) and datasets (mv_types)
        var labels = [];
        var datasets = [];

        Object.keys(data).forEach(function(month) {
            labels.push(month);
            Object.keys(data[month]).forEach(function(type) {
                // Check if the dataset for this vehicle type exists, if not, create it
                if (!datasets[type]) {
                    datasets[type] = {
                        label: type, // Use mv_type as label
                        data: new Array(labels.length).fill(0), // Initialize array with 0 counts for all months
                        backgroundColor: 'rgba(' + (Math.floor(Math.random() * 256)) + ',' + (Math.floor(Math.random() * 256)) + ',' + (Math.floor(Math.random() * 256)) + ',0.2)',
                        borderColor: 'rgba(' + (Math.floor(Math.random() * 256)) + ',' + (Math.floor(Math.random() * 256)) + ',' + (Math.floor(Math.random() * 256)) + ',1)',
                        borderWidth: 1
                    };
                }
                // Update count for the mv_type in the corresponding month index
                datasets[type].data[labels.indexOf(month)] = data[month][type];
            });
        });

        // Populate datasets array
        var datasetsArray = Object.values(datasets);

        // Create new bar graph
        window.barGraph = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: datasetsArray
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Most Done Vehicle by Month', // Add title
                        font: {
                            size: 16
                        }
                    },
                    legend: {
                        position: 'left' // Set legend position to the right
                    }
                }
            }
        });
    }

    // Initial chart update
    updateChart();

    // Event listener for year change
    $('#selectYear').change(updateChart);
});
</script>



<script>
$(document).ready(function() {
    // Variable to store the chart instance
    var doneLineChart;

    // Function to fetch data and update chart
    function updateDoneChartDay() {
        var selectedYear = $('#selectYear').val();
        var selectedMonth = $('#selectMonth').val();

        $.ajax({
            url: 'barChartvehicleDoneDay.php',
            type: 'GET',
            dataType: 'json',
            data: {year: selectedYear, month: selectedMonth}, // Send both year and month
            success: function(data) {
                // Update done line graph with new data
                createDoneLineChartDay(data);
            },
            error: function(xhr, status, error) {
                console.error('Failed to fetch done line chart data');
            }
        });
    }

    // Function to create the done line chart
    function createDoneLineChartDay(data) {
        // Get context of canvas element
        var ctx = document.getElementById('mostDoneByDayVehicle').getContext('2d');

        // Check if there's an existing chart instance and destroy it
        if (doneLineChart) {
            doneLineChart.destroy();
        }

        // Extract labels (days) from the data
        var labels = Object.keys(data);

        // Create arrays to store datasets for different vehicle types
        var datasets = [];

        // Iterate over data and populate datasets
        Object.keys(data).forEach(function(day) {
            Object.keys(data[day]).forEach(function(mvType) {
                // Check if the dataset for this vehicle type exists, if not, create it
                if (!datasets[mvType]) {
                    datasets[mvType] = {
                        label: mvType,
                        data: new Array(labels.length).fill(0), // Initialize array with 0 counts for all days
                        borderColor: 'rgba(' + (Math.floor(Math.random() * 256)) + ',' + (Math.floor(Math.random() * 256)) + ',' + (Math.floor(Math.random() * 256)) + ',1)',
                        borderWidth: 1,
                        fill: false // Ensure it's a line chart without filling
                    };
                }
                // Update count for the mv_type in the corresponding day index
                datasets[mvType].data[parseInt(day) - 1] = data[day][mvType];
            });
        });

        // Populate datasets array
        var datasetsArray = Object.values(datasets);

        // Create new done line chart
        doneLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels, // Days of the month
                datasets: datasetsArray
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Done by Day', // Header title
                        font: {
                            size: 16
                        }
                    },
                    legend: {
                        position: 'left', // Position legend on the left side
                        align: 'center' // Align legend text to the center
                    }
                }
            }
        });
    }

    // Initial done chart update
    updateDoneChartDay();

    // Event listeners for year and month change for the done chart
    $('#selectYear').change(updateDoneChartDay);
    $('#selectMonth').change(updateDoneChartDay);
});
</script>



<script>
$(document).ready(function() {
    // Function to fetch data and update chart
    function updateCanceledChart() {
        var selectedYear = $('#selectYear').val();

        $.ajax({
            url: 'barChartCanceledByMonth.php',
            type: 'GET',
            dataType: 'json',
            data: {year: selectedYear},
            success: function(data) {
                // Update bar graph with new data
                updateCanceledBarGraph(data);
            },
            error: function(xhr, status, error) {
                console.error('Failed to fetch canceled bar chart data');
            }
        });
    }

    // Function to update the canceled bar graph
    function updateCanceledBarGraph(data) {
        // Clear previous chart instance if exists
        if(window.canceledBarGraph !== undefined)
            window.canceledBarGraph.destroy();

        // Get context of canvas element
        var ctx = document.getElementById('monthlyCanceledBarGraph').getContext('2d');

        // Extract labels (months) and datasets (mv_types)
        var labels = [];
        var datasets = [];

        Object.keys(data).forEach(function(month) {
            labels.push(month);
            Object.keys(data[month]).forEach(function(type) {
                // Check if the dataset for this vehicle type exists, if not, create it
                if (!datasets[type]) {
                    datasets[type] = {
                        label: type, // Use mv_type as label
                        data: new Array(labels.length).fill(0), // Initialize array with 0 counts for all months
                        backgroundColor: 'rgba(' + (Math.floor(Math.random() * 256)) + ',' + (Math.floor(Math.random() * 256)) + ',' + (Math.floor(Math.random() * 256)) + ',0.2)',
                        borderColor: 'rgba(' + (Math.floor(Math.random() * 256)) + ',' + (Math.floor(Math.random() * 256)) + ',' + (Math.floor(Math.random() * 256)) + ',1)',
                        borderWidth: 1
                    };
                }
                // Update count for the mv_type in the corresponding month index
                datasets[type].data[labels.indexOf(month)] = data[month][type];
            });
        });

        // Populate datasets array
        var datasetsArray = Object.values(datasets);

        // Create new canceled bar graph
        window.canceledBarGraph = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: datasetsArray
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Most Canceled Vehicle by Month', // Add title
                        font: {
                            size: 16
                        }
                    },
                    legend: {
                        position: 'left' // Set legend position to the right
                    }
                }
            }
        });
    }

    // Initial canceled chart update
    updateCanceledChart();

    // Event listener for year change
    $('#selectYear').change(updateCanceledChart);
});
</script>




<script>
$(document).ready(function() {
    // Variable to store the chart instance
    var canceledLineChart;

    // Function to fetch data and update chart
    function updateCanceledChartDay() {
        var selectedYear = $('#selectYear').val();
        var selectedMonth = $('#selectMonth').val();

        $.ajax({
            url: 'barChartvehicleCanceledDay.php',
            type: 'GET',
            dataType: 'json',
            data: {year: selectedYear, month: selectedMonth}, // Send both year and month
            success: function(data) {
                // Update canceled line graph with new data
                createCanceledLineChartDay(data);
            },
            error: function(xhr, status, error) {
                console.error('Failed to fetch canceled line chart data');
            }
        });
    }

    // Function to create the canceled line chart
    function createCanceledLineChartDay(data) {
        // Get context of canvas element
        var ctx = document.getElementById('mostCanceledByDayVehicle').getContext('2d');

        // Check if there's an existing chart instance and destroy it
        if (canceledLineChart) {
            canceledLineChart.destroy();
        }

        // Extract labels (days) from the data
        var labels = Object.keys(data);

        // Create arrays to store datasets for different vehicle types
        var datasets = [];

        // Iterate over data and populate datasets
        Object.keys(data).forEach(function(day) {
            Object.keys(data[day]).forEach(function(mvType) {
                // Check if the dataset for this vehicle type exists, if not, create it
                if (!datasets[mvType]) {
                    datasets[mvType] = {
                        label: mvType,
                        data: new Array(labels.length).fill(0), // Initialize array with 0 counts for all days
                        borderColor: 'rgba(' + (Math.floor(Math.random() * 256)) + ',' + (Math.floor(Math.random() * 256)) + ',' + (Math.floor(Math.random() * 256)) + ',1)',
                        borderWidth: 1,
                        fill: false // Ensure it's a line chart without filling
                    };
                }
                // Update count for the mv_type in the corresponding day index
                datasets[mvType].data[parseInt(day) - 1] = data[day][mvType];
            });
        });

        // Populate datasets array
        var datasetsArray = Object.values(datasets);

        // Create new canceled line chart
        canceledLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels, // Days of the month
                datasets: datasetsArray
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Canceled by Day', // Header title
                        font: {
                            size: 16
                        }
                    },
                    legend: {
                        position: 'left', // Position legend on the left side
                        align: 'center' // Align legend text to the center
                    }
                }
            }
        });
    }

    // Initial canceled chart update
    updateCanceledChartDay();

    // Event listeners for year and month change for the canceled chart
    $('#selectYear').change(updateCanceledChartDay);
    $('#selectMonth').change(updateCanceledChartDay);
});
</script>

<script>
$(document).ready(function() {
    // Variable to store the chart instance
    var barChart;

    // Function to fetch data and update chart
    function updateGainLossChart() {
        var selectedYear = $('#selectYear').val();

        $.ajax({
            url: 'barChartGainLoss.php',
            type: 'GET',
            dataType: 'json',
            data: {year: selectedYear}, // Send the selected year
            success: function(data) {
                // Update bar graph with new data
                createGainLossBarChart(data);
            },
            error: function(xhr, status, error) {
                console.error('Failed to fetch bar chart data');
            }
        });
    }

    // Function to create the bar chart for gain and loss due to cancellations
    function createGainLossBarChart(data) {
        // Get context of canvas element
        var ctx = document.getElementById('gainLossBarChart').getContext('2d');

        // Check if there's an existing chart instance and destroy it
        if (barChart) {
            barChart.destroy();
        }

        // Check if data is not empty and has the expected structure
        if (data && data.length > 0) {
            // Extract months, total amounts received, and losses due to cancellations from the data
            var months = data.map(function(item) {
                return item.month;
            });
            var totalAmountsReceived = data.map(function(item) {
                return item.total_amount_received;
            });
            var lossesDueToCancellations = data.map(function(item) {
                return item.loss_due_to_cancellation;
            });

            // Create new bar chart
            barChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: months, // Months
                    datasets: [{
                        label: 'Total Amount Received',
                        data: totalAmountsReceived,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Loss Due to Cancellation',
                        data: lossesDueToCancellations,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value, index, values) {
                                    return '₱' + value; // Add Philippine peso sign
                                }
                            }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Gain and Loss Due to Cancellations', // Header title
                            font: {
                                size: 16
                            }
                        },
                        legend: {
                            position: 'left' // Position legend on the left side
                        }
                    }
                }
            });
        } else {
            console.error('Data is empty or has an unexpected structure.');
        }
    }

    // Initial chart update
    updateGainLossChart();

    // Event listener for year change
    $('#selectYear').change(updateGainLossChart);
});
</script>

<script>
$(document).ready(function() {
    // Variable to store the chart instance
    var lineChart;

    // Function to fetch data and update chart for daily gain and loss
    function updateLoseGainDayChart() {
        var selectedYear = $('#selectYear').val();
        var selectedMonth = $('#selectMonth').val();

        $.ajax({
            url: 'barChartGainLossDay.php',
            type: 'GET',
            dataType: 'json',
            data: {year: selectedYear, month: selectedMonth}, // Send both year and month
            success: function(data) {
                // Update line chart with new data
                createLoseGainDayChart(data);
            },
            error: function(xhr, status, error) {
                console.error('Failed to fetch line chart data');
            }
        });
    }

    // Function to create the line chart for daily gain and loss
    function createLoseGainDayChart(data) {
        // Get context of canvas element
        var ctx = document.getElementById('gainLossDayLineChart').getContext('2d');

        // Check if there's an existing chart instance and destroy it
        if (lineChart) {
            lineChart.destroy();
        }

        // Extract labels (days), total amounts received, and losses due to cancellations from the data
        var days = data.map(function(item) {
            return item.day;
        });
        var totalAmountsReceived = data.map(function(item) {
            return item.total_amount_received;
        });
        var lossesDueToCancellations = data.map(function(item) {
            return item.loss_due_to_cancellation;
        });

        // Create new line chart for daily gain and loss
        lineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: days, // Days
                datasets: [{
                    label: 'Total Amount Received',
                    data: totalAmountsReceived,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    fill: false
                },
                {
                    label: 'Loss Due to Cancellation',
                    data: lossesDueToCancellations,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2,
                    fill: false
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value, index, values) {
                                return '₱' + value; // Add Philippine peso sign to y-axis labels
                            }
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Daily Gain and Loss Due to Cancellations', // Header title
                        font: {
                            size: 16
                        }
                    },
                    legend: {
                        position: 'left' // Set legend position to the left
                    }
                }
            }
        });
    }

    // Initial chart update
    updateLoseGainDayChart();

    // Event listeners for year and month change
    $('#selectYear').change(updateLoseGainDayChart);
    $('#selectMonth').change(updateLoseGainDayChart);
});
</script>



</body>

</html>