<?php
// Include necessary files and start the session
session_start();
include("../connect/connection.php");

// Assuming you're passing the year as a GET parameter
$selectedYear = isset($_GET['year']) ? $_GET['year'] : date('Y');
$yearlySelected = isset($_GET['getyear']) ? $_GET['getyear'] : date('Y');
$monthlySelected = isset($_GET['getmonth']) ? $_GET['getmonth'] : date('m');



// Query for monthly booked counts with corresponding start_datetime for the selected year
$monthlyBookedCounts = array_fill(1, 12, 0);
$monthlyBookedQuery = "SELECT MONTH(s.start_datetime) as month, COUNT(*) as count
                       FROM car_emission c
                       JOIN schedule_list s ON c.event_id = s.id
                       WHERE c.status = 'booked' AND YEAR(s.start_datetime) = $selectedYear
                       GROUP BY MONTH(s.start_datetime)";
$monthlyBookedResult = $connect->query($monthlyBookedQuery);

// Populate the array with fetched data
while ($row = $monthlyBookedResult->fetch_assoc()) {
    $month = $row['month'];
    $count = $row['count'];
    $monthlyBookedCounts[$month] = $count;
}

//new 
$monthlyandYearlyBooked = array_fill(1, 12, 0);
$getdataMonthlyAndYear = "SELECT MONTH(s.start_datetime) as month, COUNT(*) as count
                          FROM car_emission c
                          JOIN schedule_list s ON c.event_id = s.id
                          WHERE c.status = 'booked' AND YEAR(s.start_datetime) = $selectedYear AND MONTH(s.start_datetime) = $monthlySelected
                          GROUP BY MONTH(s.start_datetime)";
$yearlyandMonthResult = $connect->query($getdataMonthlyAndYear);

while ($row2 = $yearlyandMonthResult->fetch_assoc()) {
    $months = $row2['month'];
    $counts = $row2['count'];
    $monthlyandYearlyBooked[$months] = $counts;
}

echo json_encode(['monthlyandYearlyBooked' => $monthlyandYearlyBooked]);



// Query for monthly canceled counts with corresponding cancellation_timestamp for the selected year
$monthlyCanceledCounts = array_fill(1, 12, 0);
$monthlyCanceledQuery = "SELECT MONTH(cr.cancellation_timestamp) as month, COUNT(*) as count
                       FROM car_emission c
                       JOIN cancellation_reasons cr ON c.id = cr.booking_id
                       WHERE c.status = 'canceled' AND YEAR(cr.cancellation_timestamp) = $selectedYear
                       GROUP BY MONTH(cr.cancellation_timestamp)";
$monthlyCanceledResult = $connect->query($monthlyCanceledQuery);

// Populate the array with fetched data
while ($row = $monthlyCanceledResult->fetch_assoc()) {
    $month = $row['month'];
    $count = $row['count'];
    $monthlyCanceledCounts[$month] = $count;
}

// Query for monthly doned counts with corresponding payment_date for the selected year
$monthlyDonedCounts = array_fill(1, 12, 0);
$monthlyDonedQuery = "SELECT MONTH(payment_date) as month, COUNT(*) as count
                     FROM car_emission
                     WHERE status = 'doned' AND YEAR(payment_date) = $selectedYear
                     GROUP BY MONTH(payment_date)";
$monthlyDonedResult = $connect->query($monthlyDonedQuery);

// Populate the array with fetched data
while ($row = $monthlyDonedResult->fetch_assoc()) {
    $month = $row['month'];
    $count = $row['count'];
    $monthlyDonedCounts[$month] = $count;
}

// Query for monthly unpaid counts with corresponding start_datetime for the selected year
$monthlyUnpaidCounts = array_fill(1, 12, 0);
$monthlyUnpaidQuery = "SELECT MONTH(s.start_datetime) as month, COUNT(*) as count
                       FROM car_emission c
                       JOIN schedule_list s ON c.event_id = s.id
                       WHERE c.paymentStatus = 'unpaid' AND YEAR(s.start_datetime) = $selectedYear
                       GROUP BY MONTH(s.start_datetime)";
$monthlyUnpaidResult = $connect->query($monthlyUnpaidQuery);

// Populate the array with fetched data
while ($row = $monthlyUnpaidResult->fetch_assoc()) {
    $month = $row['month'];
    $count = $row['count'];
    $monthlyUnpaidCounts[$month] = $count;
}

// Return data as JSON
echo json_encode(['monthlyBookedCounts' => $monthlyBookedCounts, 'monthlyCanceledCounts' => $monthlyCanceledCounts, 'monthlyDonedCounts' => $monthlyDonedCounts, 'monthlyUnpaidCounts' => $monthlyUnpaidCounts]);
?>
