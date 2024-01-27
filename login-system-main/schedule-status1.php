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
            <div class="tab-pane fade show active" id="ongoing">
            <div> 
                            <?php displayBookings('booked'); ?>
                             <!-- Check if there are no car emission records -->
        <?php if ($ongoingResult->num_rows === 0): ?>
            <div class="text-center mt-3">
                <br> </br>
                <p class="mb-3">No on going car emission records found.</p>
                <img src="assets/img/done.png" alt="Image Alt Text" style="max-width: 230px; max-height: 230px;">
            </div>
        <?php endif; ?>
                    </div>   
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

                <!-- Booking Information -->
                <div class="form-group mb-3">
                    <input type="text" class="form-control" id="bookingIdInput1" readonly>
                </div>
                <div class="form-group mb-3">
                    <label for="plateNumberDisplay">Plate Number:</label>
                    <span id="plateNumberDisplay"></span>
                </div>
                <div class="form-group mb-3">
                    <label for="mvTypeDisplay">MV Type:</label>
                    <span id="mvTypeDisplay"></span>
                </div>
                <div class="form-group mb-3">
                    <label for="dateDisplay">Date:</label>
                    <span id="dateDisplay"></span>
                </div>
                <div class="form-group mb-3">
                    <label for="dateStartEnd">Time:</label>
                    <span id="dateStartEnd"></span>
                </div>

                <!-- Payment Details -->
                <div class="form-group mb-3">
                    <label for="amountDisplay">Pay Full Amount:</label>
                    <span id="amountDisplay"></span>
                </div>
                <div class="form-group mb-3">
                    <label for="payHalfAmountDisplay">Pay Half Amount:</label>
                    <span id="payHalfAmountDisplay"></span>
                </div>

                <!-- Payment Method -->
                <div class="form-group mb-3">
                    <label for="paymentMethod">Payment Method:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="paymentMethod" id="payMayaRadio" value="payMaya">
                        <label class="form-check-label" for="payMayaRadio">PayMaya</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="paymentMethod" id="gcashRadio" value="gcash">
                        <label class="form-check-label" for="gcashRadio">GCash</label>
                    </div>
                </div>

                <!-- Dynamic Account Information -->
                <div class="alert alert-info" role="alert">
                    <p><strong>Account Name:</strong> <span id="accountName"></span></p>
                    <p><strong>Account Number:</strong>
                        <span id="accountNumber" style="user-select: all;"></span>
                        <i class="far fa-copy" style="cursor: pointer;" onclick="copyAccountNumber()"></i>
                    </p>
                </div>

                <!-- Account Information for Payment -->
                <div class="form-group mb-3">
                    <label for="paymentImage">Receipt Image:</label>
                    <input type="file" class="form-control" id="paymentImage" accept="image/*">
                    <!-- <img id="selectedImage" style="display: none; max-width: 100%;" alt="Selected Image"> -->
                </div>
                <div class="form-group mb-3">
                    <label for="referenceInput1">Reference Number:</label>
                    <input type="text" class="form-control" id="referenceInput1" placeholder="Enter reference number">
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submitPaymentBtn">Submit Payment</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal for "On Review" button -->
<div class="modal fade" id="onReviewModal" tabindex="-1" role="dialog" aria-labelledby="onReviewModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="onReviewModalLabel">Booking Review</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Add the content you want in the modal body here -->
        <p>Booking details and review information go here...</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <!-- You can add additional buttons or actions here -->
      </div>
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

<!-- GCASH OR PAYMAYA -->
    <script>
    // Get elements for account information
    var accountNameElement = document.getElementById('accountName');
    var accountNumberElement = document.getElementById('accountNumber');

    // Function to update account information based on payment method
    function updateAccountInfo(paymentMethod) {
        if (paymentMethod === 'payMaya') {
            accountNameElement.textContent = 'Gilene Mardo';
            accountNumberElement.textContent = '09951260721';
        } else if (paymentMethod === 'gcash') {
            accountNameElement.textContent = 'Richard Alaurin';
            accountNumberElement.textContent = '09927664189';
        } else {
            // Handle other payment methods here
            accountNameElement.textContent = '';
            accountNumberElement.textContent = '';
        }
    }

    // Function to copy account number to clipboard
    function copyAccountNumber() {
        var accountNumberText = accountNumberElement.textContent;
        navigator.clipboard.writeText(accountNumberText)
            .then(function () {
                alert('Account number copied to clipboard: ' + accountNumberText);
            })
            .catch(function (err) {
                console.error('Unable to copy account number to clipboard', err);
            });
    }

    // Event listener for radio buttons to update account information
    document.querySelectorAll('input[name="paymentMethod"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            updateAccountInfo(this.value);
        });
    });
</script>


<!-- ON REVIEW -->
<script>
  $(document).ready(function () {
    // Attach a click event listener to the "On Review" button
    $('.on-review-btn').on('click', function () {
      // Get the data attributes from the button
      var bookingId = $(this).data('booking-id');
      var plateNumber = $(this).data('plate-number');
      var startDatetime = $(this).data('start-datetime');
      var endDatetime = $(this).data('end-datetime');
      var ticketingId = $(this).data('ticketing-id');
      var amount = $(this).data('amount');
      var paymentMethod = $(this).data('payment-method');
      var reference1 = $(this).data('reference1');
      var mvType = $(this).data('mv-type');
      var payAmount1 = $(this).data('pay-amount1'); // Add this line

      // Update the modal body content with the booking details
      var modalBodyContent = '<p><strong>Booking ID:</strong> ' + bookingId + '</p>' +
        '<p><strong>Plate Number:</strong> ' + plateNumber + '</p>' +
        '<p><strong>MV Type:</strong> ' + mvType + '</p>' + 
        '<p><strong>Start Datetime:</strong> ' + startDatetime + '</p>' +
        '<p><strong>End Datetime:</strong> ' + endDatetime + '</p>' +
        '<p><strong>Ticketing ID:</strong> ' + ticketingId + '</p>' +
        '<p><strong>Total Amount:</strong> ₱' + amount + '</p>' +
        '<p><strong>Amount paid:</strong> ' + payAmount1 + '</p>' +
        '<p><strong>Payment Method:</strong> ' + paymentMethod + '</p>' +
        '<p><strong>Reference:</strong> ' + reference1 + '</p>'; // Add this line

      $('#onReviewModal .modal-body').html(modalBodyContent);
    });
  });
</script>


    
<script>
    $(document).ready(function () {
        // Handle image selection
        $('#paymentImage').change(function () {
            var input = this;
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#selectedImage').attr('src', e.target.result).show();

                // Update the preview button name with the image name
                var imageName = input.files[0].name;
                $('#previewImageButton').text('Preview Image: ' + imageName);

                // Store the image name for previewing
                $('#imageNamePreview').text('Image Name: ' + imageName);
            };

            reader.readAsDataURL(input.files[0]);
        });

        // Handle preview button click to show the modal
        $('#previewImageButton').on('click', function () {
            $('#imagePreviewModal').modal('show');
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
    $(document).ready(function () {
    // Initialize DataTable for the car emission table with sorting for "On Going" tab
    var dataTableOngoing = $('#ongoing').find('#carEmissionTable').DataTable({
        "searching": false,
        "lengthChange": false,  // Disable length change initially
        "order": [] 
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
            var plateNumber = $(this).data('plate-number');
            var startDatetime = $(this).data('start-datetime');
            var endDatetime = $(this).data('end-datetime');
            var amount = $(this).data('amount'); // Assuming the amount is passed as a data attribute
            var referenceNumber = $('#referenceInput1').val();
            var mvType = $(this).data('mv-type'); // Add this line

            // Calculate half of the amount
            var payHalfAmount = amount / 2;

            // Format start_datetime like the time
            var formattedDate = new Date(startDatetime).toLocaleDateString([], { year: 'numeric', month: 'short', day: 'numeric' });
            var formattedStartDatetime = new Date(startDatetime).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            var formattedEndDatetime = new Date(endDatetime).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

            // Open a modal for entering payment details
            $('#paymentModal').modal('show');

            // Set the booking ID, plate number, and date in the modal for reference
            $('#bookingIdInput1').val(bookingId);
            $('#plateNumberDisplay').text(plateNumber);
            $('#mvTypeDisplay').text(mvType);
            $('#dateDisplay').text(formattedDate);
            $('#dateStartEnd').text(formattedStartDatetime + ' - ' + formattedEndDatetime);

            // Set the amount and pay half amount in the modal
            $('#amountDisplay').text(amount);
            $('#payHalfAmountDisplay').text(payHalfAmount);
        });


            // Handle radio button change for payment method
            $('input[name="paymentMethod"]').on('change', function () {
            selectedPaymentMethod = $('input[name="paymentMethod"]:checked').val();
        });

          // Add event listeners to reset border color when user starts typing
          $('#referenceInput1, #paymentImage, input[name="paymentMethod"]').on('input', function () {
            $(this).css('border-color', '');
        });
     // Handle submitting payment details within the modal
     $('#submitPaymentBtn').on('click', function () {
            // Retrieve entered payment details
            var bookingId = $('#bookingIdInput1').val();
            var referenceNumber = $('#referenceInput1').val();
            var receiptImage = $('#paymentImage')[0].files[0]; // Get the selected image file
            var selectedPaymentMethod = $('input[name="paymentMethod"]:checked').val();

            // Validate inputs
            if (!referenceNumber || !receiptImage || !selectedPaymentMethod) {
                // Set input borders to red for the required fields
                if (!referenceNumber) {
                    $('#referenceInput1').css('border-color', 'red');
                }
                if (!receiptImage) {
                    $('#paymentImage').css('border-color', 'red');
                }
                if (!selectedPaymentMethod) {
                    $('input[name="paymentMethod"]').css('border-color', 'red');
                }

                // Show an alert or message to inform the user
                alert('Please fill in all required fields.');

                return; // Stop further execution if validation fails
            }

            // Reset input borders to default
            $('#referenceInput1, #paymentImage, input[name="paymentMethod"]').css('border-color', '');

            // Create FormData object to send image file along with other data
            var formData = new FormData();
            formData.append('bookingId', bookingId);
            formData.append('receiptImage', receiptImage);
            formData.append('paymentMethod', selectedPaymentMethod); // Include the selected payment method
            formData.append('referenceNumber', referenceNumber);

            // Perform AJAX request to handle payment
            $.ajax({
                type: 'POST',
                url: 'handle-payment.php', // Replace with the actual handling script
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    // Handle the success response, e.g., display a success message
                    alert('Payment successful for booking ID: ' + bookingId);

                    // Close the modal
                    $('#paymentModal').modal('hide');

                    // Change the button text to "On Review"
                    $('.pay-half-btn[data-booking-id="' + bookingId + '"]').text('On Review').addClass('btn-info').removeClass('btn-warning').attr('disabled', true);
                 location.reload();
                },
                error: function () {
                    // Handle the error response, e.g., display an error message
                    alert('Error processing payment for booking ID: ' + bookingId);
                }
            });
        });


    });
</script>


<!-- Payment Close -->
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

        // // You can also close the modal when the Submit Payment button is clicked
        // $('#submitPaymentBtn').on('click', function () {
        //     // Perform any actions you need before closing the modal
        //     // ...

        //     // Then close the modal
        //     closePaymentModal();
        // });
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
        echo '<table id="carEmissionTable" class="table table-bordered custom-table no-vertical-border">';
        echo '<thead><tr><th>Date</th><th>Time</th><th>Plate Number</th><th>Ticketing ID</th><th>Amount</th><th>Payment Method</th><th>Payment Status</th><th>By</th><th>Action</th></tr></thead>';
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
                    if ($booking['paymentMethod'] !== 'pending') {
                         // If reference1 has a value, change the button to "On Review"
  
                         echo '<button type="button" class="btn btn-info on-review-btn"
                        data-toggle="modal"
                        data-target="#onReviewModal"
                        data-booking-id="' . $booking['id'] . '"
                        data-plate-number="' . $booking['plate_number'] . '"
                        data-start-datetime="' . $booking['start_datetime'] . '"
                        data-end-datetime="' . $booking['end_datetime'] . '"
                        data-ticketing-id="' . $booking['ticketing_id'] . '"
                        data-amount="' . $booking['amount'] . '"
                        data-payment-method="' . $booking['paymentMethod'] . '" 
                        data-reference1="' . $booking['reference1'] . '"
                        data-mv-type="' . $booking['mv_type'] . '"
                        data-pay-amount1="' . $booking['payAmount1'] . '">On Review</button>';

                     
                     
    
    // Include the "View" link
    echo ' <a href="view-booking.php?id=' . $booking['id'] . '" class="btn btn-primary">View</a>';
                    } else {
                        echo '<button type="button" class="btn btn-danger cancel-booking-btn" data-toggle="modal" data-target="#cancelBookingModal" data-booking-id="' . $booking['id'] . '" data-plate-number="' . $booking['plate_number'] . '" data-start-datetime="' . $booking['start_datetime'] . '" data-end-datetime="' . $booking['end_datetime'] . '">Cancel</button> &nbsp;';
                        echo '<button type="button" class="btn btn-warning pay-half-btn"
                            data-booking-id="' . $booking['id'] . '"
                            data-plate-number="' . $booking['plate_number'] . '"
                            data-mv-type="' . $booking['mv_type'] . '"
                            data-start-datetime="' . $booking['start_datetime'] . '"
                            data-end-datetime="' . $booking['end_datetime'] . '"
                            data-ticketing-id="' . $booking['ticketing_id'] . '"
                            data-amount="' . $booking['amount'] . '">Pay Half/Full Now</button> &nbsp;';
                            echo '<a href="view-booking.php?id=' . $booking['id'] . '" class="btn btn-primary">View</a>';
                        }
                } else {
                    echo '<button type="button" class="btn btn-danger cancel-booking-btn" data-toggle="modal" data-target="#cancelBookingModal" data-booking-id="' . $booking['id'] . '" data-plate-number="' . $booking['plate_number'] . '" data-start-datetime="' . $booking['start_datetime'] . '" data-end-datetime="' . $booking['end_datetime'] . '">Cancel</button> &nbsp;';
                    echo '<a href="view-booking.php?id=' . $booking['id'] . '" class="btn btn-primary">View</a>';
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

