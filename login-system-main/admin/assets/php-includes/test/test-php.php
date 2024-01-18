<?php
// Include necessary files and start the session
session_start();
include("../admin/connect/connection.php");

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
if (isset($_GET['id']) && isset($_GET['ticketId'])) {
    $reserve_id = $_GET['id'];
    $ticketId = $_GET['ticketId'];

    // Check if the id exists and is associated with the provided ticketId
    $detailsQuery = "SELECT * FROM car_emission WHERE id = '$reserve_id' AND ticketing_id = '$ticketId'";
    $detailsResult = $connect->query($detailsQuery);

    if ($detailsResult->num_rows === 1) {
        $details = $detailsResult->fetch_assoc();
        // Now you have the details of the specific car emission entry and ticketId
    } else {
        // Handle the case where details are not found or parameters are invalid
        echo "Invalid parameters or details not found!";
        exit();
    }
} else {
    // Handle the case where id or ticketId parameters are not provided
    echo "Reservation details not found!";
    exit();
}

?>