<!-- schedule-status.php -->
<?php
session_start();
include("partials/navbar.php");
include("connect/connection.php");

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // If not logged in, redirect to the login page
    header('Location: index.php');
    exit();
}

// Fetch customer details from the database based on the email in the session
$customerEmail = $_SESSION['email'];

// Fetch customer details without using 'id' column
$customerQuery = $connect->prepare("SELECT first_name, middle_name, last_name FROM login WHERE email = ?");
if (!$customerQuery) {
    die("Error in customer query: " . $connect->error);
}

$customerQuery->bind_param("s", $customerEmail);
$customerQuery->execute();

$customerResult = $customerQuery->get_result();

if (!$customerResult) {
    die("Error fetching customer details: " . $customerQuery->error);
}

$customerDetails = $customerResult->fetch_assoc();

// Close the customer query statement
$customerQuery->close();

// Check if the customer has any bookings
$bookingQuery = $connect->prepare("SELECT ce.*, sl.start_datetime, sl.end_datetime FROM car_emission ce 
    JOIN schedule_list sl ON ce.event_id = sl.id 
    WHERE ce.customer_email = ?");
if (!$bookingQuery) {
    die("Error in booking query: " . $connect->error);
}

$bookingQuery->bind_param("s", $customerEmail);
$bookingQuery->execute();

$bookingResult = $bookingQuery->get_result();

if (!$bookingResult) {
    die("Error fetching booking details: " . $bookingQuery->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Schedule Status</title>
</head>

<body class="bg-light">
    <div class="container py-5">
        <h2>Schedule Status for <?php echo $customerDetails['first_name'] . ' ' . $customerDetails['last_name']; ?></h2>

        <?php if ($bookingResult->num_rows > 0) { ?>
            <table class="table">
                <thead>
                    <tr>
                    <th>Date</th>
                        <th>Time</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Payment Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($booking = $bookingResult->fetch_assoc()) { ?>
                        <tr>
                        <td><?php echo date('M. j, Y', strtotime($booking['start_datetime'])); ?></td>
                            <td>
                                <?php 
                                    $startTime = date('g:ia', strtotime($booking['start_datetime']));
                                    $endTime = date('g:ia', strtotime($booking['end_datetime']));
                                    echo $startTime . ' - ' . $endTime;
                                ?>
                            </td>
                            <td><?php echo "₱". $booking['amount']; ?></td>
                            <td><?php echo $booking['status']; ?></td>
                            <td><?php echo $booking['paymentStatus']; ?></td>
                            <td><a href="cancel-booking.php?id=<?php echo $booking['id']; ?>">Cancel</a>
                            &nbsp;
                           <a href="view-booking.php?id=<?php echo $booking['id']; ?>">View</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No bookings found.</p>
        <?php } ?>
    </div>

    <!-- Include your scripts and other body elements here -->
</body>

</html>
