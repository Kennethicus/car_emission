<?php
// Include necessary files and start the session
session_start();
include("../connect/connection.php");

// Assuming you're passing the year as a GET parameter
$selectedYear = isset($_GET['year']) ? $_GET['year'] : date('Y');

// Initialize an array to store the data for all months, initially with counts set to 0
$data = array_fill(1, 12, array());

// SQL query to get monthly counts by vehicle type for the selected year
$query = "SELECT 
            MONTH(s.start_datetime) AS month, 
            c.mv_type,
            COUNT(*) AS count
          FROM 
            car_emission c
          JOIN 
            schedule_list s ON c.event_id = s.id
          WHERE 
            c.status = 'canceled' AND 
            YEAR(s.start_datetime) = $selectedYear
          GROUP BY 
            MONTH(s.start_datetime),
            c.mv_type";

$result = $connect->query($query);

// Loop through the result and update the counts in the data array
while ($row = $result->fetch_assoc()) {
    $month = $row['month'];
    $mvType = $row['mv_type'];
    $count = $row['count'];

    // Update the count for the corresponding month and MV type
    $data[$month][$mvType] = $count;
}

// Return data as JSON
echo json_encode($data);
?>
