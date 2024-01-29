<?php
// return-payment1.php

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the POST request
    $bookingId = $_POST['bookingId'];
    $referenceNumber = $_POST['referenceNumber'];
    $returnReasons = isset($_POST['returnReasons']) ? $_POST['returnReasons'] : [];

    // Include the database connection file
    include("../connect/connection.php");

    // Update the payment lock status to 0
    $updatePaymentLockQuery = "UPDATE car_emission SET paymentlock2 = 0 WHERE id = ?";
    $stmtPaymentLock = $connect->prepare($updatePaymentLockQuery);
    $stmtPaymentLock->bind_param('i', $bookingId);
    $stmtPaymentLock->execute();
    $stmtPaymentLock->close();

    // Prepare the SQL query to update the car_emission table with return information
    $updateQuery = "UPDATE car_emission SET return_switch_2 = 3, return_reason2 = ? WHERE id = ?";
    $stmt = $connect->prepare($updateQuery);

    // Concatenate return reasons into a single string separated by commas
    $returnReasonString = implode(', ', $returnReasons);

    // Bind parameters
    $stmt->bind_param('si', $returnReasonString, $bookingId);

    // Execute the query
    $stmt->execute();

    // Check if any rows were affected
    if ($stmt->affected_rows > 0) {
        echo "Payment returned successfully.\n";
    } else {
        echo "Error returning payment. Please try again.\n";
    }

    // Close the statement
    $stmt->close();
} else {
    // If the request is not a POST request, return an error message
    echo "Invalid request method.";
}

?>
