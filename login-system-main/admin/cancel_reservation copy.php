<?php
// Include necessary files and start the session
session_start();
include("../connect/connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reservationId = $_POST['reservationId'];
    $cancelReason = $_POST['cancelReason'];
    $adminId = $_POST['adminId']; // Assuming you have the admin ID in the session

    // Insert cancellation record into the database
    $insertQuery = "INSERT INTO cancellation_reasons (booking_id, reason, cancel_by_smurf_admin) 
                    VALUES ('$reservationId', '$cancelReason', '$adminId')";
    $result = $connect->query($insertQuery);

    if ($result) {
        // Update the car_emission table status to 'canceled'
        $updateQuery = "UPDATE car_emission SET status = 'canceled' WHERE id = '$reservationId'";
        $updateResult = $connect->query($updateQuery);

        if ($updateResult) {
            echo 'Reservation canceled successfully.';
        } else {
            echo 'Error updating reservation status.';
        }
    } else {
        echo 'Error inserting cancellation record.';
    }
} else {
    echo 'Invalid request.';
}
?>
