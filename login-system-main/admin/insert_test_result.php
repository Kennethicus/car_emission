<?php 
// Include necessary files and start the session
session_start();
include("../connect/connection.php");

// Check if the admin is logged in, if not, redirect to the login page
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data from the AJAX request
    $reserveId = $_POST['reserveId'];
    $ticketId = $_POST['ticketId'];

    // Check if the booking ID already exists in the test_result table
    $checkQuery = "SELECT * FROM test_result WHERE booking_id = '$reserveId'";
    $checkResult = $connect->query($checkQuery);

    if ($checkResult->num_rows > 0) {
        // Booking ID already has test results, handle accordingly (maybe send a response to the client)
        header("Location: test.php?id=$reserveId&ticketId=$ticketId");
        exit();
    } else {
        // Booking ID does not have test results, proceed to insert
        // Add your logic to insert test results into the `test_result` table
        // Example: Inserting test results with default values
        $insertQuery = "INSERT INTO test_result (booking_id, HC, CO, CO2, O2, N, RPM, K_AVE) VALUES ('$reserveId', 0, 0, 0, 0, 0, 0, 0)";
        $insertResult = $connect->query($insertQuery);

        if ($insertResult) {
            echo "Test results inserted successfully!";
        } else {
            echo "Error inserting test results!";
        }
    }
} else {
    echo "Invalid request method!";
}
?>
