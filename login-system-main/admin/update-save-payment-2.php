<?php
// update-save-payment-1.php

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the POST request
    $bookingId = $_POST['bookingId'];

    // Debugging statement
    if (isset($_POST['paymentMethod'])) {
        echo "Payment method received: " . $_POST['paymentMethod'] . "\n";
    } else {
        echo "No payment method received.\n";
    }

    // Initialize variables for the fields
    $updateQuery = "UPDATE car_emission SET ";
    $params = array();

    // Check if payment method, reference number, or payment image is provided
    $paymentMethodProvided = isset($_POST['paymentMethod']);
    $referenceNumberProvided = isset($_POST['referenceNumber']);
    $paymentImageProvided = isset($_FILES['paymentImage']);

    // Construct the update query based on the changed values
 // Check if the payment method is provided and not empty
if ($paymentMethodProvided && !empty($_POST['paymentMethod'])) {
    $updateQuery .= "paymentMethod2 = ?, ";
    $params[] = $_POST['paymentMethod'];
}


    if ($referenceNumberProvided) {
        $updateQuery .= "reference2 = ?, ";
        $params[] = $_POST['referenceNumber'];
    }

    // Check if payment image is provided and valid
    if ($paymentImageProvided && is_uploaded_file($_FILES['paymentImage']['tmp_name'])) {
        // Handle the receipt image - move it to a designated folder with a unique filename
        $targetFolder = '../uploads/receipt-image/';
        $uniqueFilename = uniqid('receipt_') . '_' . basename($_FILES['paymentImage']['name']);
        $targetFile = $targetFolder . $uniqueFilename;

        if (move_uploaded_file($_FILES['paymentImage']['tmp_name'], $targetFile)) {
            $updateQuery .= "receipt2 = ?, ";
            $params[] = $uniqueFilename;
        } else {
            echo "Error uploading receipt image.\n";
            exit; // Stop execution if there's an error uploading the image
        }
    }

    // Remove trailing comma and space
    $updateQuery = rtrim($updateQuery, ', ');

    // Add the bookingId as the last parameter
    $updateQuery .= " WHERE id = ?";
    $params[] = $bookingId;

    // Prepare and execute the SQL query to update the car_emission table
    include("../connect/connection.php");

    // Prepare and bind parameters to the update query
    $stmt = $connect->prepare($updateQuery);
    if (!$stmt) {
        die("Error in update query: " . $connect->error);
    }

    // Bind parameters dynamically
    $types = str_repeat('s', count($params)); // Assuming all parameters are strings
    $stmt->bind_param($types, ...$params);

    // Execute the update query
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Payment information updated successfully.\n";
    } else {
        echo "Error updating payment information.\n";
    }

    // Close the statement
    $stmt->close();
} else {
    // If the request is not a POST request, return an error message
    echo "Invalid request method.";
}
?>
