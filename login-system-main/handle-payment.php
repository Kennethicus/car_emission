<?php
// handle-payment.php

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the POST request
    $bookingId = $_POST['bookingId'];
    $receiptImage = $_FILES['receiptImage'];
    $paymentMethod = $_POST['paymentMethod'];
    $referenceNumber = $_POST['referenceNumber'];

    // Validate and process the payment data
    // Add your own validation and processing logic here

    // Handle the receipt image - move it to a designated folder with a unique filename
    $targetFolder = 'uploads/receipt-image/';
    $uniqueFilename = uniqid('receipt_') . '_' . basename($receiptImage['name']);
    $targetFile = $targetFolder . $uniqueFilename;

    if (move_uploaded_file($receiptImage['tmp_name'], $targetFile)) {
        // Prepare and execute the SQL query to update the car_emission table
        include("connect/connection.php");

        $updateQuery = $connect->prepare("UPDATE car_emission SET  receipt1 = ?, paymentMethod1 = ?, reference1 = ? WHERE id = ?");
        if (!$updateQuery) {
            die("Error in update query: " . $connect->error);
        }

        $updateQuery->bind_param("sssi", $uniqueFilename, $paymentMethod, $referenceNumber, $bookingId);
        $updateQuery->execute();

        if ($updateQuery->affected_rows > 0) {
            echo "Payment information updated successfully.\n";
        } else {
            echo "Error updating payment information.\n";
        }

        // Close the update query statement
        $updateQuery->close();
    } else {
        echo "Error uploading receipt image.\n";
    }
} else {
    // If the request is not a POST request, return an error message
    echo "Invalid request method.";
}
?>
