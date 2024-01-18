<?php
// Include necessary files
include("../connect/connection.php");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the bookingId from the form data
    $bookingId = $_POST['bookingId'];

    // Fetch details from the test_result table based on the bookingId
    $detailsQuery = "SELECT Tested, Valid, Uploaded, Uploaded_Image, finalize FROM test_result WHERE booking_id = '$bookingId'";
    $detailsResult = $connect->query($detailsQuery);

    if ($detailsResult->num_rows === 1) {
        $details = $detailsResult->fetch_assoc();

        // Convert the values to integers
        $details['Tested'] = (int)$details['Tested'];
        $details['Valid'] = (int)$details['Valid'];
        $details['Uploaded'] = (int)$details['Uploaded'];
        $details['Uploaded_Image'] = (int)$details['Uploaded_Image'];
        $details['finalize'] = (int)$details['finalize']; // Include finalize in the response

        // Return the details as JSON
        echo json_encode($details);
    } else {
        // Return an empty response or an error message
        echo json_encode(['error' => 'Details not found']);
    }
} else {
    // Return an empty response or an error message
    echo json_encode(['error' => 'Invalid request']);
}
?>
