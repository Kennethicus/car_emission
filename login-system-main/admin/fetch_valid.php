<?php
session_start();
include("../connect/connection.php");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the isChecked value from the form data
    $isChecked = isset($_POST['isChecked']) ? $_POST['isChecked'] : 0;
    $bookingId = $_POST['bookingId']; // Assuming you have 'bookingId' as part of your data

    // Update the database based on the isChecked value
    $updateQuery = "UPDATE test_result SET valid = $isChecked, record_status = $isChecked WHERE booking_id = '$bookingId'";
    
    // Assuming $connect is your database connection variable
    // If you are using mysqli, you can replace $connect with $mysqli or your actual connection variable
    if ($connect->query($updateQuery) === TRUE) {
        // Fetch the updated values from the database
        $fetchQuery = "SELECT valid, record_status FROM test_result WHERE booking_id = '$bookingId'";
        $result = $connect->query($fetchQuery);
        $row = $result->fetch_assoc();
        $updatedValue = $row['valid'];
        $recordStatus = $row['record_status'];

        // Return success along with the updated values
        echo json_encode(['success' => true, 'isChecked' => $isChecked, 'updatedValue' => $updatedValue, 'recordStatus' => $recordStatus]);
    } else {
        // Return an error message if the update fails
        echo json_encode(['success' => false, 'message' => 'Error updating the database.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
