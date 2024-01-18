<!-- customer side -->
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
$customerQuery = $connect->prepare("SELECT user_id, first_name, middle_name, last_name FROM login WHERE email = ?");
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
    <!-- Add Bootstrap CSS link -->
   
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container py-5">
        <h2>Schedule Status for <?php echo $customerDetails['first_name'] . ' ' . $customerDetails['last_name']; ?></h2>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" id="myTabs">
            <li class="nav-item">
                <a class="nav-link active" id="booked-tab" data-toggle="tab" href="#booked">On Going</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="canceled-tab" data-toggle="tab" href="#canceled">Cancellation</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="done-tab" data-toggle="tab" href="#done">Confirmation</a>
            </li>
        </ul>

        <!-- Tab content -->
        <div class="tab-content mt-3">
            <!-- Booked Tab -->
            <div class="tab-pane fade show active" id="booked">
                <?php displayBookings('Booked'); ?>
            </div>

            <!-- Canceled Tab -->
            <div class="tab-pane fade" id="canceled">
                <?php displayBookings('Canceled'); ?>
            </div>

            <!-- Done Tab -->
            <div class="tab-pane fade" id="done">
                <?php displayBookings('Doned'); ?>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script>
        // Activate the current tab based on the hash in the URL
        $(document).ready(function () {
            if (window.location.hash) {
                $('#myTabs a[href="' + window.location.hash + '"]').tab('show');
            }
        });

        // Remember the tab selection in the URL hash
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            window.location.hash = e.target.hash;
        });
    </script>



<!-- Modal -->
<div class="modal fade" id="cancelBookingModal" tabindex="-1" role="dialog" aria-labelledby="cancelBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelBookingModalLabel">Cancel Booking</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this booking?</p>
                <form id="cancelBookingForm">
                    <input type="hidden" name="bookingId" id="bookingIdInput">
                    <div class="form-group">
                        <label for="cancelReason">Reason for Cancellation:</label>
                        <textarea class="form-control" name="cancelReason" id="cancelReason" rows="3" required></textarea>
                    </div>
                    <!-- Inside the modal form -->
<div class="form-group">
    <input type="hidden" name="canceledBy" id="canceledBy" value="<?php echo $customerDetails['user_id'];?>">
</div>

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Cancel Booking</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // When the cancel booking button is clicked, set the booking ID in the modal form
        $('.cancel-booking-btn').click(function () {
            var bookingId = $(this).data('booking-id');
            $('#bookingIdInput').val(bookingId);
        });

        // When the cancel booking form is submitted
        $('#cancelBookingForm').submit(function (e) {
            e.preventDefault();

            // Perform AJAX request to cancel-booking.php
            $.ajax({
                type: 'POST',
                url: 'cancel-booking.php',
                data: $(this).serialize(),
                success: function (response) {
                    // Update the modal content with the response
                    $('.modal-body').html(response);
                },
                error: function () {
                    alert('Error cancelling booking.');
                }
            });
        });
    });
</script>
</body>

</html>

<?php
function displayBookings($status)
{
    global $bookingResult;

    // Reset the internal pointer of the result set
    $bookingResult->data_seek(0);

    $filteredBookings = [];

    // Iterate through all bookings and filter based on status
    while ($booking = $bookingResult->fetch_assoc()) {
        // Convert both status values to lowercase for case-insensitive comparison
        if (strtolower($booking['status']) === strtolower($status)) {
            $filteredBookings[] = $booking;
        }
    }

    if (count($filteredBookings) > 0) {
        echo '<table class="table">';
        echo '<thead><tr><th>Date</th><th>Time</th><th>Ticketing ID</th><th>Amount</th><th>Status</th><th>Payment Method</th><th>Payment Status</th><th>Action</th></tr></thead>';
        echo '<tbody>';
        foreach ($filteredBookings as $booking) {
            echo '<tr>';
            echo '<td>' . date('M. j, Y', strtotime($booking['start_datetime'])) . '</td>';
            echo '<td>' . date('g:ia', strtotime($booking['start_datetime'])) . ' - ' . date('g:ia', strtotime($booking['end_datetime'])) . '</td>';
            echo '<td>' . $booking['ticketing_id'] . '</td>';
            echo '<td>' . '₱' . $booking['amount'] . '</td>';
            echo '<td>' . $booking['status'] . '</td>';
            echo '<td>' . $booking['paymentMethod'] . '</td>';
            echo '<td>' . $booking['paymentStatus'] . '</td>';
            if (strtolower($status) === 'booked') {
                echo '<td><a href="#" class="cancel-booking-btn" data-toggle="modal" data-target="#cancelBookingModal" data-booking-id="' . $booking['id'] . '">Cancel</a> &nbsp; <a href="view-booking.php?id=' . $booking['id'] . '">View</a></td>';
            } elseif (strtolower($status) === 'canceled') {
                echo '<td><a href="view-booking.php?id=' . $booking['id'] . '">View</a></td>';
            } elseif (strtolower($status) === 'doned') {
                echo '<td><a href="view-booking.php?id=' . $booking['id'] . '">View</a> &nbsp; <a href="test-link.php">Test</a></td>';
            }
            

echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<p>No ' . strtolower($status) . ' bookings found.</p>';
    }
}



?>
