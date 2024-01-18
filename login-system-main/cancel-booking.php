<?php
include("connect/connection.php");

// Check if the booking ID, reason, and canceled by are provided
if (isset($_POST['bookingId']) && isset($_POST['cancelReason']) && isset($_POST['canceledBy'])) {
    $bookingId = $_POST['bookingId'];
    $reason = $_POST['cancelReason'];
    $canceledBy = $_POST['canceledBy'];

    // Update the booking status to 'canceled'
    $cancelQuery = $connect->prepare("UPDATE car_emission SET status = 'canceled' WHERE id = ?");
    
    if (!$cancelQuery) {
        die("Error in cancel query: " . $connect->error);
    }

    $cancelQuery->bind_param("i", $bookingId);

    if ($cancelQuery->execute()) {
        // Store the cancellation reason, canceled by, and timestamp in the new table
        $reasonQuery = $connect->prepare("INSERT INTO cancellation_reasons (booking_id, reason, canceled_by_user, cancellation_timestamp) VALUES (?, ?, ?, NOW())");
       
        if (!$reasonQuery) {
            die("Error in reason query: " . $connect->error);
        }

        $reasonQuery->bind_param("iss", $bookingId, $reason, $canceledBy);
        $reasonQuery->execute();

        // Decrease the reserve count in schedule_list table
        $updateCountQuery = $connect->prepare("UPDATE schedule_list SET reserve_count = reserve_count - 1 WHERE id = (SELECT event_id FROM car_emission WHERE id = ?)");
        
        if (!$updateCountQuery) {
            die("Error in updating reserve count query: " . $connect->error);
        }

        $updateCountQuery->bind_param("i", $bookingId);
        $updateCountQuery->execute();

        echo 'Booking canceled successfully.';
    } else {
        echo 'Error canceling booking.';
    }

    // Close the prepared statements
    $cancelQuery->close();
    $reasonQuery->close();
    $updateCountQuery->close();

    // Close the database connection
    $connect->close();
} else {
    echo 'Invalid parameters.';
}
?>
