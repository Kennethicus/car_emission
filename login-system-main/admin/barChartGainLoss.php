<?php
// Include necessary files and start the session
session_start();
include("../connect/connection.php");

// Assuming you're passing the year as a GET parameter
$selectedYear = isset($_GET['year']) ? $_GET['year'] : date('Y');

// Initialize an array to store the data
$data = array();

// Create an array with all months
$allMonths = range(1, 12);

// SQL query to get data for the selected year
$query = "SELECT 
            MONTH(s.start_datetime) AS month, 
            COALESCE(SUM(CASE 
                            WHEN c.paymentStatus = 'fully paid' AND c.status != 'canceled' THEN c.amount
                            WHEN c.paymentStatus = 'fully paid' AND c.status = 'canceled' THEN c.amount / 2
                            WHEN c.paymentStatus = 'half paid' AND c.status = 'canceled' THEN c.amount
                            ELSE 0 
                        END), 0) AS total_amount_received,
            COALESCE(SUM(CASE 
                            WHEN c.paymentStatus = 'fully paid' AND c.status = 'canceled' THEN c.amount / 2
                            WHEN c.paymentStatus = 'half paid' AND c.status = 'canceled' THEN c.amount
                            ELSE 0 
                        END), 0) AS loss_due_to_cancellation
        FROM 
            car_emission c
        RIGHT JOIN 
            schedule_list s ON c.event_id = s.id AND YEAR(s.start_datetime) = $selectedYear
        GROUP BY 
            MONTH(s.start_datetime)
        ORDER BY 
            MONTH(s.start_datetime)";

$result = $connect->query($query);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . $connect->error);
}

// Loop through the result and organize the data
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Ensure all months are included with zero values
foreach ($allMonths as $month) {
    $found = false;
    foreach ($data as $item) {
        if ($item['month'] == $month) {
            $found = true;
            break;
        }
    }
    if (!$found) {
        // Add an entry with zero values for the month
        $data[] = array('month' => $month, 'total_amount_received' => 0, 'loss_due_to_cancellation' => 0);
    }
}

// Sort the data by month
usort($data, function($a, $b) {
    return $a['month'] - $b['month'];
});

// Return data as JSON
echo json_encode($data);
?>
