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

// Fetch car emission details based on the provided id parameter
if (isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];
    $HC = $_POST['HC'];
    $CO = $_POST['CO'];
    $CO2 = $_POST['CO2'];
    $O2 = $_POST['O2'];
    $N = $_POST['N'];
    $RPM = $_POST['RPM'];
    $K_AVE = $_POST['K_AVE'];
    $mvectOperator = $_POST['mvectOperator'];

    // Set a default value for the "Tested" column
    $Tested = 1; // Change this value to whatever default you want

    // Update the car_emission table with the selected operator value
    $updateQuery = "UPDATE car_emission 
                    SET mvect_operator = '$mvectOperator' 
                    WHERE id = '$booking_id'";

    if ($connect->query($updateQuery)) {
        // Success
        echo "MVECT operator updated successfully!";

        // Insert the test results into the test_result table
        $insertQuery = "INSERT INTO test_result (booking_id, HC, CO, CO2, O2, N, RPM, K_AVE, Tested) 
                        VALUES ('$booking_id', '$HC', '$CO', '$CO2', '$O2', '$N', '$RPM', '$K_AVE', '$Tested')";

        if ($connect->query($insertQuery)) {
            // Success
            echo "Test results saved successfully!";
        } else {
            // Error
            echo "Error: " . $connect->error;
        }
    } else {
        // Error
        echo "Error updating MVECT operator: " . $connect->error;
    }
} else {
    // Handle the case where booking_id parameter is not provided
    echo "Booking ID not provided!";
}
?>
