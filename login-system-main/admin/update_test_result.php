<?php
// Include necessary files and start the session
session_start();
include("../connect/connection.php");

// Check if the admin is logged in, if not, redirect to the login page
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}

// Get the bookingId, MVect operator, testResults, testingStatus, tested, and dateTested from the AJAX request
$bookingId = $_POST['bookingId'];
$mvectOperator = $_POST['mvectOperator'];
$testResults = $_POST['testResults'];
$testingStatus = $_POST['testingStatus'];
$tested = $_POST['tested'];
$dateTested = $_POST['dateTested'];

// Update the test_result table with the received test results and testing status
$updateTestResultQuery = "UPDATE test_result SET
    HC = '{$testResults['hc']}',
    CO = '{$testResults['co']}',
    CO2 = '{$testResults['co2']}',
    O2 = '{$testResults['o2']}',
    N = '{$testResults['n']}',
    RPM = '{$testResults['rpm']}',
    K_AVE = '{$testResults['kAve']}',
    testing_status = '$testingStatus',
    Tested = '$tested'
    WHERE booking_id = '$bookingId'";

$updateTestResultResult = $connect->query($updateTestResultQuery);

// Update the car_emission table with the selected MVect operator and date_tested
$updateCarEmissionQuery = "UPDATE car_emission SET
    mvect_operator = '$mvectOperator',
    date_tested = '$dateTested'
    WHERE id = '$bookingId'";

$updateCarEmissionResult = $connect->query($updateCarEmissionQuery);

// Check if the updates were successful
if ($updateTestResultResult && $updateCarEmissionResult) {
    echo "Test results, MVect operator, testing status, and date tested updated successfully.";
} else {
    echo "Error updating test results, MVect operator, testing status, and date tested: " . $connect->error;
}
?>
