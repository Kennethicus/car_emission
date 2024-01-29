<?php
// Include necessary files and start the session
session_start();
include("../connect/connection.php");

// Check if the admin is logged in, if not, redirect to the login page
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the POST request
    $reserveId = $_POST['reserveId'];
    $ticketId = $_POST['ticketId'];
    $paidAmount = $_POST['paidAmount'];

    // Set return_switch_1 value
    $returnSwitch1 = '2'; // You may adjust this value as needed

    // Perform validation on the input data if needed

    // Fetch total amount from the car_emission table
    $totalAmountQuery = "SELECT amount, payAmount1 FROM car_emission WHERE id = '$reserveId'";
    $totalAmountResult = $connect->query($totalAmountQuery);

    if ($totalAmountResult->num_rows == 1) {
        $row = $totalAmountResult->fetch_assoc();
        $totalAmount = $row['amount'];
        $paidAmount1 = $row['payAmount1'];
        
        // Update the paid amount and return_switch_1 in the car_emission table
        $updateQuery = "UPDATE car_emission SET payAmount2 = '$paidAmount', return_switch_2 = '$returnSwitch1' WHERE id = '$reserveId'";
        $updateResult = $connect->query($updateQuery);
    
        if ($updateResult) {
            // Update successful
    
            // Calculate the total paid amount
            $totalPaidAmount = $paidAmount + $paidAmount1;

            // Update payment status based on the comparison of paid and total amount
            $paymentStatus = ($totalPaidAmount >= $totalAmount) ? 'fully paid' : 'half paid';
            $updatePaymentStatusQuery = "UPDATE car_emission SET paymentStatus = '$paymentStatus' WHERE id = '$reserveId'";
            $updatePaymentStatusResult = $connect->query($updatePaymentStatusQuery);
    
            if ($updatePaymentStatusResult) {
                echo "Paid amount, payment status, and return switch updated successfully!";
            } else {
                echo "Failed to update payment status!";
            }
        } else {
            // Update failed
            echo "Failed to update paid amount and return switch!";
        }
    } else {
        // Total amount not found
        echo "Failed to retrieve total amount!";
    }
    
} else {
    // If the request method is not POST, handle the error accordingly
    echo "Invalid request method!";
}
?>
