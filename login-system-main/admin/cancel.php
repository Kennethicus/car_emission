<?php
// Include necessary files and start the session
session_start();
include("../connect/connection.php");

// Check if the admin is logged in, if not, redirect to the login page
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}

// Check if the reservation ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid request. Reservation ID is missing.";
    exit();
}

$reservationId = $_GET['id'];

// Fetch reservation details based on the reservation ID
$query = "SELECT * FROM car_emission WHERE id = $reservationId";
$result = $connect->query($query);

if ($result->num_rows != 1) {
    echo "Invalid reservation ID.";
    exit();
}

$reservation = $result->fetch_assoc();

// Check if the reservation status is 'booked'
if ($reservation['status'] !== 'booked') {
    echo "This reservation cannot be canceled.";
    exit();
}

// Handle form submission for cancellation reason
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reason = $_POST['reason'];
    $adminId = $_SESSION['admin']['id'];

    // Insert cancellation reason into the database
    $insertQuery = "INSERT INTO cancellation_reasons (booking_id, reason, cancel_by_smurf_admin) 
                    VALUES ($reservationId, '$reason', $adminId)";

    if ($connect->query($insertQuery) === TRUE) {
        // Update reservation status to 'canceled'
        $updateQuery = "UPDATE car_emission SET status = 'canceled' WHERE id = $reservationId";
        $connect->query($updateQuery);

        echo "Reservation canceled successfully.";
    } else {
        echo "Error: " . $insertQuery . "<br>" . $connect->error;
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancel Reservation</title>
    <!-- Add your CSS stylesheets and other meta tags as needed -->
</head>

<body>
    <h1>Cancel Reservation</h1>
    <p>Are you sure you want to cancel this reservation?</p>

    <form method="post" action="">
        <label for="reason">Reason for Cancellation:</label>
        <textarea id="reason" name="reason" required></textarea><br>

        <input type="submit" value="Cancel Reservation">
    </form>

    <p><a href="dashboard.php">Back to Dashboard</a></p>
</body>

</html>
