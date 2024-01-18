<?php
// Include necessary files and start the session
session_start();
include("../connect/connection.php");

// Assuming you're passing the year as a GET parameter
$selectedYear = isset($_GET['year']) ? $_GET['year'] : date('Y');

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

// Return data as JSON
echo json_encode(['labels' => array_values($monthlyBookedCounts), 'monthlyBookedCounts' => $monthlyBookedCounts, 'monthlyCanceledCounts' => $monthlyCanceledCounts, 'monthlyDonedCounts' => $monthlyDonedCounts]);
?>
