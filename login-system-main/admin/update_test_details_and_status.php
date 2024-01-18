<?php
// Include necessary files and start the session
session_start();
include("../connect/connection.php");

// Check if the admin is logged in, if not, redirect to the login page
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}

// Fetch admin details based on the session information (you may customize this part)
$username = $_SESSION['admin'];
$query = "SELECT * FROM smurf_admin WHERE username = '$username'";
$result = $connect->query($query);

if ($result->num_rows == 1) {
    $admin = $result->fetch_assoc();
} else {
    // Handle the case where admin details are not found
    echo "Admin details not found!";
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming you have a form with a submit button to trigger the update

    // Get the car emission ID from the form (adjust the name attribute accordingly)
    $reserve_id = $_POST['bookingId'];

    // Generate a 5-digit random number for petc_or
    $petc_or = rand(10000, 99999);

    // Get the current date and time
    $payment_date = date('Y-m-d H:i:s');

    $doned_customer = "doned";
    $payment_status = "paid";

    // Check if the last 5 digits of cec_number already exist
    do {
        // Generate a 7-digit random number for auth_code
        $auth_code = rand(1000000, 9999999);
     $finalize = 1;
        // Generate the cec_number based on the year today + 000000 + petc_or
        $yearToday = date('Y');
        $cec_number = $yearToday . '000000' . $petc_or;

        // Check if the cec_number already exists in the database
        $cecNumberExistsQuery = "SELECT id FROM car_emission WHERE cec_number = '$cec_number' LIMIT 1";
        $cecNumberExistsResult = $connect->query($cecNumberExistsQuery);

    } while ($cecNumberExistsResult->num_rows > 0);

    // Update the car_emission table with the generated values
    $updateCarEmissionQuery = "UPDATE car_emission SET petc_or = '$petc_or', payment_date = '$payment_date', status = '$doned_customer', paymentStatus = '$payment_status', cec_number = '$cec_number' WHERE id = '$reserve_id'";

    if ($connect->query($updateCarEmissionQuery) === TRUE) {
        // Update the test_result table with the generated auth_code
        $updateTestResultQuery = "UPDATE test_result SET auth_code = '$auth_code', finalize = '$finalize' WHERE booking_id = '$reserve_id'";

        if ($connect->query($updateTestResultQuery) === TRUE) {
            // Assuming you have fetched the updated values from the database
            $updatedValues = array(
                'dateTested' => $payment_date,  // Modify this based on your actual data
                'petcValue' => $petc_or,        // Modify this based on your actual data
                'authCode' => $auth_code,       // Include the authCode in the response
                'cecNumber' => $cec_number,  
                'finalize' => $finalize,   // Include the cecNumber in the response
                // Add other values as needed
            );

            // Return the updated values as a JSON response
            echo json_encode($updatedValues);
        } else {
            echo "Error updating auth_code in test_result record: " . $connect->error;
        }
    } else {
        echo "Error updating car_emission record: " . $connect->error;
    }
}
?>
