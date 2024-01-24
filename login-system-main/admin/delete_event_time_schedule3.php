<?php
include("../connect/connection.php");

// Check if the scheduleId is provided in the POST request
if (isset($_POST['scheduleId'])) {
    $scheduleId = $_POST['scheduleId'];

    // Fetch start_datetime from schedule_list
    $fetchStartDatetimeSql = "SELECT DATE_FORMAT(`start_datetime`, '%Y-%m-%d') AS formatted_start_datetime FROM `schedule_list` WHERE `id` = '$scheduleId'";
    $startDatetimeResult = $connect->query($fetchStartDatetimeSql);

    if ($startDatetimeResult->num_rows > 0) {
        $row = $startDatetimeResult->fetch_assoc();
        $formattedStartDatetime = $row['formatted_start_datetime'];

        // Update car_emission table: set event_id to null
        $updateEventIdSql = "UPDATE `car_emission` SET `event_id` = NULL WHERE `event_id` = '$scheduleId'";
        $updateResult = $connect->query($updateEventIdSql);

        if ($updateResult !== false) {
            // Proceed with schedule deletion
            $deleteScheduleSql = "DELETE FROM `schedule_list` WHERE `id` = '$scheduleId'";
            $deleteResult = $connect->query($deleteScheduleSql);

            if ($deleteResult) {
                // Deletion successful, include formatted_start_datetime in the response
                echo json_encode(array('status' => 'success', 'formatted_start_datetime' => $formattedStartDatetime));
            } else {
                // Error in deletion
                echo json_encode(array('status' => 'error', 'message' => $connect->error));
            }
        } else {
            // Error in updating car_emission table
            echo json_encode(array('status' => 'error', 'message' => 'Error updating car_emission table: ' . $connect->error));
        }
    } else {
        // Schedule with provided ID not found
        echo json_encode(array('status' => 'error', 'message' => 'Error: Schedule not found.'));
    }
} else {
    // If scheduleId is not provided in the POST request, echo an error message
    echo json_encode(array('status' => 'error', 'message' => 'Error: Schedule ID not provided in the request.'));
}

// Close the database connection
$connect->close();
?>
