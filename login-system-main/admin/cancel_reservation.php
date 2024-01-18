<?php
// Include necessary files and start the session
session_start();
include("../connect/connection.php");

// Check if the reservation ID is provided
if (isset($_POST['reservationId']) && isset($_POST['reason']) && isset($_POST['adminId'])) {
    $reservationId = $_POST['reservationId'];
    $reason = $_POST['reason'];
    $adminId = $_POST['adminId'];

    // Update car_emission table
    $updateQuery = "UPDATE car_emission SET status = 'canceled' WHERE id = $reservationId";
    $connect->query($updateQuery);

    // Insert into cancellation_reasons table
    $insertQuery = "INSERT INTO cancellation_reasons (booking_id, reason, canceled_by_user, cancel_by_smurf_admin, cancellation_timestamp)
                    VALUES ($reservationId, '$reason', NULL, $adminId, CURRENT_TIMESTAMP)";
    $connect->query($insertQuery);

    // Return success response to the AJAX request
    echo json_encode(['success' => true]);
} else {
    // Return error response if required parameters are not provided
    echo json_encode(['success' => false]);
}
?>
