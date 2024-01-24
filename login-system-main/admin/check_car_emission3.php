<?php
include("../connect/connection.php");

if (isset($_POST['scheduleId'])) {
    $scheduleId = $_POST['scheduleId'];

    // Check if the schedule ID exists in the car_emission table
    $sqlCheckCarEmission = "SELECT COUNT(*) as count FROM car_emission WHERE event_id = $scheduleId";
    $resultCheckCarEmission = $connect->query($sqlCheckCarEmission);

    if ($resultCheckCarEmission->num_rows > 0) {
        $rowCheckCarEmission = $resultCheckCarEmission->fetch_assoc();
        $count = $rowCheckCarEmission['count'];

        // Send the result as a JSON response
        echo json_encode($count > 0 ? 'true' : 'false');
    } else {
        echo json_encode('false');
    }
} else {
    echo json_encode('false');
}

// Close the database connection
$connect->close();
?>
