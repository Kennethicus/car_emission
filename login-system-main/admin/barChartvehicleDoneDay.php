<?php
// Include necessary files and start the session
session_start();
include("../connect/connection.php");

// Assuming you're passing the year and month as GET parameters
$selectedYear = isset($_GET['year']) ? $_GET['year'] : date('Y');
$selectedMonth = isset($_GET['month']) ? $_GET['month'] : date('m');

// Get the number of days in the selected month
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $selectedYear);

// Initialize an array to store the data for all days of the month
$data = array_fill(1, $daysInMonth, array());

// SQL query to get data for the selected year and month
$query = "SELECT 
    DAY(s.start_datetime) AS day_of_month, 
    c.mv_type,
    COUNT(*) AS count
FROM 
    schedule_list s
LEFT JOIN 
    car_emission c ON c.event_id = s.id AND c.status = 'doned' 
WHERE 
    YEAR(s.start_datetime) = $selectedYear AND 
    MONTH(s.start_datetime) = $selectedMonth AND
    c.mv_type IS NOT NULL -- Exclude rows where mv_type is null
GROUP BY 
    DAY(s.start_datetime),
    c.mv_type;
";

$result = $connect->query($query);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . $connect->error);
}

// Loop through the result and organize the data
while ($row = $result->fetch_assoc()) {
    $dayOfMonth = $row['day_of_month'];
    $mvType = $row['mv_type'];
    $count = $row['count'];

    // Add the data to the array
    if (!isset($data[$dayOfMonth])) {
        $data[$dayOfMonth] = array();
    }
    // Add count for the mv_type to the corresponding day
    $data[$dayOfMonth][$mvType] = $count;
}

// Return data as JSON
echo json_encode($data);
?>
