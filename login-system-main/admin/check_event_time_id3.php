<?php
include("../connect/connection.php");

// Check if the scheduleId is provided in the POST request
if (isset($_POST['scheduleId'])) {
    $scheduleId = $_POST['scheduleId'];

    // Query to check if the scheduleId exists as event_id in the car_emission table
    $checkEventIdSql = "SELECT COUNT(*) AS count FROM `car_emission` WHERE `event_id` = '$scheduleId'";

    $result = $connect->query($checkEventIdSql);

    if ($result) {
        $row = $result->fetch_assoc();
        $count = $row['count'];

        // Create a response object
        $response = array(
            'count' => $count,
            'status' => ($count > 0) ? 'true' : 'false'
        );

        // Output the response as JSON for debugging
        header('Content-Type: application/json'); // Set content type
        echo json_encode($response);
    } else {
        // If there is an error in the query, output the error message
        header('Content-Type: application/json'); // Set content type
        echo json_encode(array('error' => 'Error: ' . $conn->error));
    }
} else {
    // If scheduleId is not provided in the POST request, echo an error message
    header('Content-Type: application/json'); // Set content type
    echo json_encode(array('error' => 'Error: Schedule ID not provided in the request.'));
}

// Close the database connection
$connect->close();
?>
