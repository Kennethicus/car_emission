
<?php

include('connect/connection.php');

// Check if the date parameter is set
if (isset($_GET['date'])) {
    $selectedDate = $_GET['date'];

    // Query the database to get events on the selected date, including car_emission status
    // $query = "SELECT sl.*, ce.status AS car_emission_status 
    //           FROM `schedule_list` sl
    //           LEFT JOIN `car_emission` ce ON sl.id = ce.event_id 
    //           WHERE DATE(sl.start_datetime) = '$selectedDate'";
    
    $query = "SELECT sl.*, ce.status AS car_emission_status 
          FROM `schedule_list` sl
          LEFT JOIN `car_emission` ce ON sl.id = ce.event_id 
          WHERE DATE(sl.start_datetime) = '$selectedDate'
          GROUP BY sl.id";

    $result = $connect->query($query);

    if ($result === false) {
        // Handle database query error
        $error = ['error' => 'Database query error: ' . $connect->error];
        header('Content-Type: application/json');
        echo json_encode($error);
        exit();
    }

    $eventsOnSelectedDate = [];
    while ($row = $result->fetch_assoc()) {
        $eventsOnSelectedDate[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'qty_of_person' => $row['qty_of_person'],
            'reserve_count' => $row['reserve_count'],
            'price_1' => $row['price_1'],
            'start' => $row['start_datetime'],
            'end' => $row['end_datetime'],
            'availability' => $row['availability'],
            'car_emission_status' => $row['car_emission_status']
        ];
    }

    // Return the events as JSON
    header('Content-Type: application/json');
    echo json_encode($eventsOnSelectedDate);
} else {
    // If date parameter is not set, return an error message
    $error = ['error' => 'Date parameter is missing.'];
    header('Content-Type: application/json');
    echo json_encode($error);
}

$connect->close();
?>
