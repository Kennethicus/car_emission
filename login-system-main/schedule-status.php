<!-- customer side -->
<!-- schedule-status.php -->
<?php
session_start();

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
    WHERE ce.user_id = ?");
if (!$bookingQuery) {
    die("Error in booking query: " . $connect->error);
}

$userName = $customerDetails['first_name'];
$bookingQuery->bind_param("i", $customerDetails['user_id']);
$bookingQuery->execute();

$bookingResult = $bookingQuery->get_result();

if (!$bookingResult) {
    die("Error fetching booking details: " . $bookingQuery->error);
}

// Function to get car emission records by status
function getCarEmissionByStatus($status) {
    global $connect;

    $query = "SELECT * FROM car_emission WHERE status = ?";
    $stmt = $connect->prepare($query);

    if (!$stmt) {
        die("Error in car emission query: " . $connect->error);
    }

    $stmt->bind_param("s", $status);
    $stmt->execute();

    $result = $stmt->get_result();

    if (!$result) {
        die("Error fetching car emission details: " . $stmt->error);
    }

    // Close the statement
    $stmt->close();

    return $result;
}

// Usage example:
// Fetch car emission details for a specific user
$carEmissionQueryForUser = "SELECT * FROM car_emission WHERE user_id = " . $customerDetails['user_id'];
$carEmissionResultForUser = $connect->query($carEmissionQueryForUser);

$ongoingQueryForUser = "SELECT * FROM car_emission WHERE status = 'booked' AND user_id = " . $customerDetails['user_id'];
$canceledQueryForUser = "SELECT * FROM car_emission WHERE status = 'canceled' AND user_id = " . $customerDetails['user_id'];
$doneQueryForUser = "SELECT * FROM car_emission WHERE status = 'doned' AND user_id = " . $customerDetails['user_id'];

$ongoingResult = $connect->query($ongoingQueryForUser);
$canceledResult = $connect->query($canceledQueryForUser);
$doneResult = $connect->query($doneQueryForUser);

?>


<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Greenfrog - Schedule Status</title>
    <link rel="stylesheet" href="admin/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&amp;display=swap">
    <link rel="stylesheet" href="admin/assets/css/aos.min.css">
    <link rel="stylesheet" href="admin/assets/css/animate.min.css">
    <link rel="stylesheet" href="admin/assets/css/NavBar-with-pictures.css">


    <link rel="stylesheet" href="admin/assets/bootstrap/css/bootstrap.min.css">

    <link rel="stylesheet" href="admin/assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="admin/assets/css/animate.min.css">
    <link rel="stylesheet" href="admin/assets/css/Bootstrap-4-Calendar-No-Custom-Code.css">
    <link rel="stylesheet" href="admin/assets/css/Bootstrap-Calendar.css">
    <link rel="stylesheet" href="admin/assets/css/Continue-Button.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="admin/assets/design/reservation.css">


    <style>
  #reservationIdDisplay {
    display: none;
  }
</style>
</head>

<body class="bg-light">
     <?php include 'partials/nav.php' ?>
                  <!-- Search and Length Change Section -->
<div class="container mt-3">
    <div class="row justify-content-begin">
        <div class="col-md-5">
            <label for="search">Search:</label>
            <div class="input-group">
                <input type="text" id="search" class="form-control">
                <button class="btn btn-outline-secondary" type="button" id="clearSearchBtn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <div class="col-md-2">
            <label for="lengthChange">Show entries:</label>
            <select id="lengthChange" class="form-select">
            <option value="1">1</option>  
            <option value="2">2</option>  
            <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>

        <div class="col-md-1 align-self-end">
            <button id="searchBtn" class="btn btn-primary">Search</button>
        </div>
    </div>
</div>

<div class="container py-5">
        <h2>Schedule Status for <?php echo $customerDetails['first_name'] . ' ' . $customerDetails['last_name']; ?></h2>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" id="carEmissionTabs myTabs">
            <li class="nav-item">
                <a class="nav-link active" id="ongoing-tab" data-toggle="tab" href="#ongoing">Book</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="canceled-tab" data-toggle="tab" href="#canceled">Canceled</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="done-tab" data-toggle="tab" href="#done">Done</a>
            </li>
        </ul>

        <!-- Tab content -->
        <div class="tab-content">
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

    <script src="admin/assets2/bootstrap/js/bootstrap.min.js"></script>
    <!-- <script src="admin/assets2/js/aos.min.js"></script> -->
    <script src="admin/assets2/js/bs-init.js"></script>
    <!-- <script src="admin/assets2/js/bold-and-bright.js"></script> -->
<script src="admin/assets/js/chart.min.js"></>
    <script src="admin/assets/js/bs-init.js"></script>
    <!-- Include Bootstrap JS and jQuery -->

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="admin/assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="admin/assets/js/chart.min.js"></script>
    <script src="admin/assets/js/bs-init.js"></script>
    <script src="admin/assets/js/theme.js"></script>
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

                <!-- Display Plate Number -->
                <div class="form-group">
                    <label for="plateNumber">Plate Number:</label>
                    <input type="text" class="form-control" id="plateNumber" name="plateNumber" value="" readonly>
                </div>
                <div class="form-group">
                    <label for="startDatetime">Date</label>
                    <input type="text" class="form-control" id="Date" name="Date" value="" readonly>
                </div>
                <!-- Display Start Datetime -->
                <div class="form-group">
                    <label for="startDatetime">Start Datetime:</label>
                    <input type="text" class="form-control" id="startDatetime" name="startDatetime" value="" readonly>
                </div>

                <!-- Display End Datetime -->
                <div class="form-group">
                    <label for="endDatetime">End Datetime:</label>
                    <input type="text" class="form-control" id="endDatetime" name="endDatetime" value="" readonly>
                </div>

                <form id="cancelBookingForm">
                    <input type="hidden" name="bookingId" id="bookingIdInput">
                    <div class="form-group">
                        <label for="cancelReason">Reason for Cancellation:</label>
                        <textarea class="form-control" name="cancelReason" id="cancelReason" rows="3" required></textarea>
                    </div>
                    <!-- Inside the modal form -->
                    <div class="form-group">
                        <input type="hidden" name="canceledBy" id="canceledBy" value="<?php echo $customerDetails['user_id']; ?>">
                    </div>

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Cancel Booking</button>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- Updated Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Enter Payment Details</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Add your payment form fields here -->
                <div class="form-group">
                    <label for="bookingIdInput">Booking ID:</label>
                    <input type="text" class="form-control" id="bookingIdInput" readonly>
                </div>
                <div class="form-group">
                    <label for="paymentAmountInput">Payment Amount:</label>
                    <input type="text" class="form-control" id="paymentAmountInput" placeholder="Enter payment amount">
                </div>
                <!-- Add more fields as needed -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submitPaymentBtn">Submit Payment</button>
            </div>
        </div>
    </div>
</div>


<script src="admin/assets2/bootstrap/js/bootstrap.min.js"></script>
    <!-- <script src="admin/assets2/js/aos.min.js"></script> -->
    <script src="admin/assets2/js/bs-init.js"></script>
    <!-- <script src="admin/assets2/js/bold-and-bright.js"></script> -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


  <!-- DataTable initialization script -->
  <script>
        $(document).ready(function () {
            // Initialize DataTable for the 'Booked' tab
            $('#ongoingTable').DataTable({
                // Configure DataTable options here
                "paging": true,
                "ordering": true,
                "searching": true,
                "info": true
                // Add other options as needed
            });
        });
    </script>
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

<script>
    $(document).ready(function () {
     $('.cancel-booking-btn').click(function () {
        var bookingId = $(this).data('booking-id');
        var plateNumber = $(this).data('plate-number');
        var startDatetime = $(this).data('start-datetime');
        var endDatetime = $(this).data('end-datetime');

        // Format start_datetime and end_datetime like the time
        var formattedDate = new Date(startDatetime).toLocaleDateString([], { year: 'numeric', month: 'short', day: 'numeric' });
        var formattedStartDatetime = new Date(startDatetime).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        var formattedEndDatetime = new Date(endDatetime).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

        $('#Date').val(formattedDate);
        
        $('#bookingIdInput').val(bookingId);
        $('#plateNumber').val(plateNumber);
        $('#startDatetime').val(formattedStartDatetime);
        $('#endDatetime').val(formattedEndDatetime);
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




<script>
    $(document).ready(function () {
    // Initialize DataTable for the car emission table with sorting for "On Going" tab
    var dataTableOngoing = $('#ongoing').find('#carEmissionTable').DataTable({
        "searching": false,
        "lengthChange": false,  // Disable length change initially
        "order": [] // Disable initial sorting if needed
    });

        // Initialize DataTable for the car emission table with sorting for "Canceled" tab
        var dataTableCanceled =  $('#canceled').find('#carEmissionTable').DataTable({
            "searching": false,
            "lengthChange": false,
            "order": [] // Disable initial sorting if needed
        });

        // Initialize DataTable for the car emission table with sorting for "Done" tab
        var dataTableDone =   $('#done').find('#carEmissionTable').DataTable({
            "searching": false,
            "lengthChange": false,
            "order": [] // Disable initial sorting if needed
        });


  // Enable length change when the user selects a different option
  $('#lengthChange').on('change', function () {
        var selectedLength = $(this).val();
        dataTableOngoing.page.len(selectedLength).draw();
        // Repeat the above lines for canceled and done tabs if needed
    });


    $('#lengthChange').on('change', function () {
        var selectedLength = $(this).val();
        dataTableCanceled.page.len(selectedLength).draw();
        // Repeat the above lines for canceled and done tabs if needed
    });

    $('#lengthChange').on('change', function () {
        var selectedLength = $(this).val();
        dataTableDone.page.len(selectedLength).draw();
        // Repeat the above lines for canceled and done tabs if needed
    });


        // Search bar functionality
        $('#searchBtn').on('click', function () {
            var searchTerm = $('#search').val().toLowerCase();

            // Display results based on the active tab
            var defaultTab = $('.nav-link.active').attr('id');
            switch (defaultTab) {
                case 'ongoing-tab':
                    filterTable('ongoing', searchTerm);
                    break;
                case 'canceled-tab':
                    filterTable('canceled', searchTerm);
                    break;
                case 'done-tab':
                    filterTable('done', searchTerm);
                    break;
            }
        });

        function filterTable(tabId, searchTerm) {
    var tableId = '#' + tabId;
    var rows = $(tableId + ' tbody tr');

    rows.hide();
    rows.filter(function () {
        // Make the search case-insensitive and match partial plate numbers
        return $(this).text().toLowerCase().includes(searchTerm);
    }).show();
}


        // Clear search on tab change and filter rows based on the new active tab
        $('a[data-bs-toggle="tab"]').on('shownL.bs.tab', function (e) {
            $('#search').val(''); // Clear the search input
            var defaultTab = e.target.id;
            filterTable(defaultTab.replace('-tab', ''), ''); // Show all rows
        });

        $('#clearSearchBtn').on('click', function () {
            $('#search').val(''); // Clear the search input
            var defaultTab = $('.nav-link.active').attr('id');
            filterTable(defaultTab.replace('-tab', ''), ''); // Show all rows
        });


       // Store the reservation ID when the Cancel link is clicked
var cancelReservationId;

// Handle Cancel link click
$('.cancel-link').on('click', function () {
    // Get the reservation ID from the data attribute
    cancelReservationId = $(this).data('reservation-id');

    // Display the reservation ID in the modal
    $('#reservationIdDisplay').text(cancelReservationId);

 // Make an AJAX request to get customer details and event details
$.ajax({
    type: 'POST',
    url: 'get_customer_details.php',
    data: { reservationId: cancelReservationId },
    dataType: 'json', // Expect JSON response
    success: function (data) {
        // Display the details in the modal
        $('#customerName').text('Customer Name: ' + data.customerName);
        $('#plateNumbers').text('Plate Number: ' + data.plateNumber);
        $('#EventDate').text('Event Date: ' + data.eventDate);
        $('#EventStartDate').text('Start Time: ' + data.startTime);
        $('#EventEndDate').text('End Time: ' + data.endTime);
    },
    error: function () {
        alert('Error fetching customer details');
    }
});


    // Show the cancel modal
    $('#cancelModal').modal('show');
});

// Handle cancel confirmation
$('#confirmCancelBtn').on('click', function () {
    // Close the modal
    $('#cancelModal').modal('hide');

    // Perform the cancel action (you can implement the cancellation logic here)
    // For demonstration purposes, you can just alert the reservation ID
    // alert('Cancel Reservation ID: ' + cancelReservationId);
});

$(document).on('click', '[data-dismiss="modal"]', function () {
    $('#cancelModal').modal('hide');
});

// Function to clear the cancellation reason input field
function clearCancellationReason() {
    $('#cancellationReason').val('');
}

// Clear the reason when the modal is hidden
$('#cancelModal').on('hidden.bs.modal', clearCancellationReason);
    });
</script>
<!-- Add this script at the end of your HTML body -->
<script>
    $(document).ready(function () {
        // Handle Pay Half Now button click
        $('.pay-half-btn').on('click', function () {
            var bookingId = $(this).data('booking-id');

            // Open a modal for entering payment details
            $('#paymentModal').modal('show');

            // Set the booking ID in the modal for reference
            $('#bookingIdInput').val(bookingId);
        });

        // Handle submitting payment details within the modal
        $('#submitPaymentBtn').on('click', function () {
            // Retrieve entered payment details
            var bookingId = $('#bookingIdInput').val();
            var paymentAmount = $('#paymentAmountInput').val();
            // Add more fields as needed

            // Perform AJAX request to handle payment
            $.ajax({
                type: 'POST',
                url: 'handle-payment.php', // Replace with the actual handling script
                data: {
                    bookingId: bookingId,
                    paymentAmount: paymentAmount
                    // Add more fields as needed
                },
                success: function (response) {
                    // Handle the success response, e.g., display a success message
                    alert('Payment successful for booking ID: ' + bookingId);
                    // Close the modal
                    $('#paymentModal').modal('hide');
                },
                error: function () {
                    // Handle the error response, e.g., display an error message
                    alert('Error processing payment for booking ID: ' + bookingId);
                }
            });
        });
    });
</script>

<!-- Bootstrap Script for Modal -->
<script>
    $(document).ready(function () {
        // Function to close the payment modal
        function closePaymentModal() {
            $('#paymentModal').modal('hide');
        }

        // Close the payment modal when the close button is clicked
        $('#paymentModal .btn-close').on('click', closePaymentModal);

        // Close the payment modal when the Close button is clicked
        $('#paymentModal .btn-secondary').on('click', closePaymentModal);

        // You can also close the modal when the Submit Payment button is clicked
        $('#submitPaymentBtn').on('click', function () {
            // Perform any actions you need before closing the modal
            // ...

            // Then close the modal
            closePaymentModal();
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
        echo '<thead><tr><th>Date</th><th>Time</th><th>Plate Number</th><th>Ticketing ID</th><th>Amount</th><th>Payment Method</th><th>Payment Status</th><th>Booked By</th><th>Action</th></tr></thead>';
        echo '<tbody>';
        foreach ($filteredBookings as $booking) {
            echo '<tr>';
            echo '<td  class="align-middle">' . date('M. j, Y', strtotime($booking['start_datetime'])) . '</td>';
            echo '<td  class="align-middle">' . date('g:ia', strtotime($booking['start_datetime'])) . ' - ' . date('g:ia', strtotime($booking['end_datetime'])) . '</td>';
            echo '<td  class="align-middle">' . $booking['plate_number'] . '</td>';
            echo '<td  class="align-middle">' . $booking['ticketing_id'] . '</td>';
            echo '<td  class="align-middle">' . '₱' . $booking['amount'] . '</td>';
            echo '<td  class="align-middle">' . $booking['paymentMethod'] . '</td>';
            echo '<td  class="align-middle">' . $booking['paymentStatus'] . '</td>';
            echo '<td  class="align-middle">';
            if ($booking['smurf_admin_id'] !== null) {
                echo 'Admin';
            } else {
                echo 'Me';
            }
            echo '</td>';
            echo '<td class="align-middle">';
            if (strtolower($status) === 'booked') {
                if ($booking['paymentStatus'] === 'unpaid') {                                                                                                                                                                                                                       
                    // Display "Pay Half Now" button for unpaid bookings
                    echo '<button type="button" class="btn btn-danger cancel-booking-btn" data-toggle="modal" data-target="#cancelBookingModal" data-booking-id="' . $booking['id'] . '" data-plate-number="' . $booking['plate_number'] . '" data-start-datetime="' . $booking['start_datetime'] . '" data-end-datetime="' . $booking['end_datetime'] . '">Cancel</button> &nbsp; <a href="view-booking.php?id=' . $booking['id'] . '" class="btn btn-primary">View</a> &nbsp; <button type="button" class="btn btn-warning pay-half-btn" data-booking-id="' . $booking['id'] . '">Pay Half Now</button>';

                } else {
                    echo '<a href="#" class="cancel-booking-btn" data-toggle="modal" data-target="#cancelBookingModal" data-booking-id="' . $booking['id'] . '" data-plate-number="' . $booking['plate_number'] . '" data-start-datetime="' . $booking['start_datetime'] . '" data-end-datetime="' . $booking['end_datetime'] . '">Cancel</a> &nbsp; <a href="view-booking.php?id=' . $booking['id'] . '" class="btn btn-primary">View</a>';
                }
            } elseif (strtolower($status) === 'canceled') {
                echo '<a href="view-booking.php?id=' . $booking['id'] . '">View</a>';
            } elseif (strtolower($status) === 'doned') {
                echo '<a href="view-booking-doned.php?id=' . $booking['id'] . '">View</a> &nbsp; <a href="test-link.php">Test</a>';
            }
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<p>No ' . strtolower($status) . ' bookings found.</p>';
    }
}

?>

