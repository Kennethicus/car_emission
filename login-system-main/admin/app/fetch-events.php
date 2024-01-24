<?php
// Include your database connection file
include '../db-connect.php';

// Fetch events from the database
$query = "SELECT * FROM schedule_list";
$result = mysqli_query($connection, $query);

$events = array();

while ($row = mysqli_fetch_assoc($result)) {
    // Format the start date as per Pikaday's expected format
    $start = date('m/d/Y', strtotime($row['start_datetime']));

    $events[] = array(
        'title' => $row['title'],
        'start' => $start,
        'description' => $row['description'],
    );
}

// Set the Content-Type header to JSON
header('Content-Type: application/json');
echo json_encode($events);

// Close the database connection
mysqli_close($connection);
?>
