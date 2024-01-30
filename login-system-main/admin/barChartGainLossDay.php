<?php
// Include necessary files and start the session
session_start();
include("../connect/connection.php");

// Assuming you're passing the year and month as GET parameters
$selectedYear = isset($_GET['year']) ? $_GET['year'] : date('Y');
$selectedMonth = isset($_GET['month']) ? $_GET['month'] : date('m');

// SQL query to get the data
$query = "SELECT 
DAY(s.start_datetime) AS day,
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
schedule_list s ON c.event_id = s.id AND YEAR(s.start_datetime) = $selectedYear AND MONTH(s.start_datetime) = $selectedMonth AND DAY(s.start_datetime) <= 10
GROUP BY 
 DAY(s.start_datetime)

";

$result = $connect->query($query);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . $connect->error);
}

// Initialize an empty array to store the data
$data = [];

// Loop through the result and add each row to the data array
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Return data as JSON
echo json_encode($data);
?>
