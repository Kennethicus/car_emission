<?php
// Include necessary files and start the session
session_start();
include("../connect/connection.php");

// Assuming you're passing the year and month as GET parameters
$selectedYear = isset($_GET['year']) ? $_GET['year'] : date('Y');
$selectedMonth = isset($_GET['month']) ? $_GET['month'] : date('m');

// Query for daily booked counts with corresponding start_datetime for the selected year and month
$dailyBookedCounts = array_fill(1, 31, 0);
$dailyBookedQuery = "SELECT DAY(s.start_datetime) as day, COUNT(*) as count
                     FROM car_emission c
                     JOIN schedule_list s ON c.event_id = s.id
                     WHERE c.status = 'booked' AND YEAR(s.start_datetime) = $selectedYear AND MONTH(s.start_datetime) = $selectedMonth
                     GROUP BY DAY(s.start_datetime)";
$dailyBookedResult = $connect->query($dailyBookedQuery);

// Populate the array with fetched data
while ($row = $dailyBookedResult->fetch_assoc()) {
    $day = $row['day'];
    $count = $row['count'];
    $dailyBookedCounts[$day] = $count;
}

// Similar logic for daily canceled and doned counts

// Return data as JSON
echo json_encode(['labels' => array_keys($dailyBookedCounts), 'dailyBookedCounts' => array_values($dailyBookedCounts), 'dailyCanceledCounts' => array_values($dailyCanceledCounts), 'dailyDonedCounts' => array_values($dailyDonedCounts)]);
?>
