<?php
// Include necessary files and start the session
session_start();
include("../connect/connection.php");

// Assuming you're passing the year as a GET parameter
$selectedYear = isset($_GET['year']) ? $_GET['year'] : date('Y');

// Initialize an array to store the data for the pie chart
$pieChartData = array();

// Query to fetch the data for the pie chart
$pieChartQuery = "SELECT 
                        MONTH(s.start_datetime) as month, 
                        COUNT(*) as count,
                        c.mv_type
                  FROM 
                        car_emission c
                  JOIN 
                        schedule_list s ON c.event_id = s.id
                  WHERE 
                        c.status = 'doned' AND 
                        YEAR(s.start_datetime) = $selectedYear
                  GROUP BY 
                        MONTH(s.start_datetime), 
                        c.mv_type";

$pieChartResult = $connect->query($pieChartQuery);

// Populate the array with fetched data
while ($row = $pieChartResult->fetch_assoc()) {
    $month = $row['month'];
    $count = $row['count'];
    $mvType = $row['mv_type'];

    // Create an associative array to store count for each vehicle type in each month
    $pieChartData[$month][$mvType] = $count;
}

// Return data as JSON
echo json_encode($pieChartData);
?>
