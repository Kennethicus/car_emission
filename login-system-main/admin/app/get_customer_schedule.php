<?php
require_once('../db-connect.php');

// Check if the date parameter is set
if (isset($_GET['date'])) {
    $selectedDate = $_GET['date'];

    // Query the database to get events on the selected date
    $query = "SELECT * FROM `schedule_list` WHERE DATE(start_datetime) = '$selectedDate'";
    $result = $conn->query($query);

    $eventsOnSelectedDate = [];
    while ($row = $result->fetch_assoc()) {
        $eventsOnSelectedDate[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'qty_of_person' => $row['qty_of_person'],
            'start' => $row['start_datetime'],
            'end' => $row['end_datetime'],
            'availability' => $row['availability'],
        ];
    }

    // Return the events as JSON
    header('Content-Type: application/json');
    echo json_encode($eventsOnSelectedDate);
} else {
    // If date parameter is not set, return an error message
    echo 'Error: Date parameter is missing.';
}

// Close the database connection
$conn->close();
?>
