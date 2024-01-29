<!-- admin reservation.php -->
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

// Fetch car emission details
$carEmissionQuery = "SELECT * FROM car_emission";
$carEmissionResult = $connect->query($carEmissionQuery);
$ongoingQuery = "SELECT * FROM car_emission WHERE status = 'booked'";
$canceledQuery = "SELECT * FROM car_emission WHERE status = 'canceled'";
$doneQuery = "SELECT * FROM car_emission WHERE status = 'doned'";

$ongoingResult = $connect->query($ongoingQuery);
$canceledResult = $connect->query($canceledQuery);
$doneResult = $connect->query($doneQuery);
?>

<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Green Frawg | Dashboard</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/Bootstrap-4-Calendar-No-Custom-Code.css">
    <link rel="stylesheet" href="assets/css/Bootstrap-Calendar.css">
    <link rel="stylesheet" href="assets/css/Continue-Button.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="assets/design/reservation.css">



    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.7/css/buttons.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.7/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.7/js/buttons.print.min.js"></script>

    <style>
  #reservationIdDisplay {
    display: none;
  }
</style>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include 'partials/nav.php'?>
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <?php include 'partials/nav-2.php' ?>

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

                <!-- Car Emission Tab with Card Navigation -->
                <div class="container mt-3">
                    <ul class="nav nav-tabs" id="carEmissionTabs">
                        <li class="nav-item">
                            <a class="nav-link active" id="ongoing-tab" data-bs-toggle="tab" href="#ongoing">Book</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="canceled-tab" data-bs-toggle="tab" href="#canceled">Canceled</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="done-tab" data-bs-toggle="tab" href="#done">Done</a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content container ">
                    <!-- On Going Tab Content -->
                    <div class="tab-pane fade show active" id="ongoing">
                        <div> 
                            <?php displayCarEmission($ongoingResult); ?>
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

                    <!-- Canceled Tab Content -->
                    <div class="tab-pane fade" id="canceled">
    <div class=""> <!-- Add top margin to create space -->
    <?php displayCarEmission($canceledResult); ?>
        <!-- Check if there are no car emission records -->
        <?php if ($canceledResult->num_rows === 0): ?>
            <div class="text-center mt-3">
                <br> </br>
                <p class="mb-3">No canceled car emission records found.</p>
                <img src="assets/img/done.png" alt="Image Alt Text" style="max-width: 230px; max-height: 230px;">
            </div>
        <?php endif; ?>
    </div>
</div>
<!-- Done Tab Content -->
<div class="tab-pane fade" id="done">
    <div class=""> <!-- Add top margin to create space -->
        <?php displayCarEmission($doneResult); ?>

        <!-- Check if there are no car emission records -->
        <?php if ($doneResult->num_rows === 0): ?>
            <div class="text-center mt-3">
                <br> </br>
                <p class="mb-3">No done car emission records found.</p>
                <img src="assets/img/done.png" alt="Image Alt Text" style="max-width: 230px; max-height: 230px;">
            </div>
        <?php endif; ?>
    </div>
</div>

                </div>


<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Cancel Reservation</h5>
                <button type="button" class="close border-0 bg-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-dark font-weight-bold" style="font-size: 2em;">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this reservation for:</p>
                <p id="reservationIdDisplay" class="font-weight-bold"></p>
                <p id="customerName"></p>
                <p id="plateNumbers"></p>
                <p id="EventDate"></p>
                <p id="EventStartDate"></p>
                <p id="EventEndDate"></p>
                
                <!-- Add an input field for cancellation reason -->
                <label for="cancellationReason">Cancellation Reason:</label>
                <textarea class="form-control" id="cancellationReason" rows="3" required></textarea>

                <input type="hidden" id="adminId" value="<?php echo $admin['id']; ?>" readonly>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="confirmCancelBtn">Cancel Reservation</button>
            </div>
        </div>
    </div>
</div>





                
            </div>
            <?php include 'partials/footer.php'?>
        </div>
        <a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>

    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/chart.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/theme.js"></script>
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
// Handle Cancel button click
$('.cancel-btn').on('click', function () {
    // Get the reservation ID from the data attribute
    var cancelReservationId = $(this).data('reservation-id');

    // Display the reservation ID in the modal
    $('#reservationIdDisplay').text(cancelReservationId);

    // Make an AJAX request to get customer details and event details
    $.ajax({
        type: 'POST',
        url: 'get_customer_details.php',
        data: { reservationId: cancelReservationId },
        dataType: 'json',
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

<script>
    // Update customer name in the cancel modal
    $(document).on('click', '.cancel-link', function () {
        // Get the reservation ID from the data attribute
        cancelReservationId = $(this).data('reservation-id');

        // Fetch customer details based on reservation ID
        $.ajax({
            type: 'POST',
            url: 'get_customer_details.php', // Replace with the actual path to your PHP script
            data: { reservationId: cancelReservationId },
            success: function (response) {
              // Split the data into lines
        var lines = data.split('<br>');

        // Display the customer name in the modal
        $('#customerName').text(lines[1]);

      
            }
        });

        // Show the cancel modal
        $('#cancelModal').modal('show');
    });





    // Inside your script tag
$('#confirmCancelBtn').on('click', function () {
    // Get cancellation reason and admin ID
    var cancellationReason = $('#cancellationReason').val();
    var adminId = $('#adminId').val();


       // Check if cancellation reason is provided
       if (!cancellationReason.trim()) {
        alert('Please provide a cancellation reason.');
        return;
    }

    // Make an AJAX request to update car_emission and insert into cancellation_reasons
    $.ajax({
        type: 'POST',
        url: 'cancel_reservation.php', // Create a new PHP file for handling cancellations
        data: {
            reservationId: cancelReservationId,
            reason: cancellationReason,
            adminId: adminId
        },
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                // Optionally, you can display a success message or update the UI
                alert('Reservation canceled successfully');
            } else {
                alert('Error canceling reservation');
            }
            // Close the modal
            $('#cancelModal').modal('hide');
        },
        error: function () {
            alert('Error canceling reservation');
        }
    });
});

</script>

</body>
</html>

<?php
error_log("Reservation ID: " . $reservationId);

function displayCarEmission($carEmissionResult)
{
    global $connect;

    // Reset the internal pointer of the result set
    $carEmissionResult->data_seek(0);

    if ($carEmissionResult->num_rows > 0) {
        echo '<div class="table-responsive">';
        // Inside the displayCarEmission function
     
        echo '<table id="carEmissionTable" class="table table-bordered custom-table no-vertical-border">';
        echo '<thead><tr><th class="date">Date</th><th>Time</th><th>Ticket ID</th><th>Customer Name</th><th>Plate Number</th><th>Booked by</th><th>Payment Status</th><th class="action">Action</th></tr></thead>';
        // Rest of your table rendering code...
        
        echo '<tbody>';
        while ($row = $carEmissionResult->fetch_assoc()) {
            $reserve_id = $row['id'];
            $ticketingId = $row['ticketing_id'];
            $plateNumber = $row['plate_number'];
            $ticketId = $row['ticketing_id'];
            $firstName = $row['customer_first_name'];
            $lastName = $row['customer_last_name'];
            $status = $row['status'];
            $eventId = $row['event_id'];
            $adminId = $row['smurf_admin_id'];
            $paymentStatus =  $row['paymentStatus'];

            // Retrieve start and end datetime from schedule_list
            $scheduleQuery = "SELECT start_datetime, end_datetime FROM schedule_list WHERE id = $eventId";
            $scheduleResult = $connect->query($scheduleQuery);
            $scheduleRow = $scheduleResult->fetch_assoc();
            $startDatetime = $scheduleRow['start_datetime'];
            $endDatetime = $scheduleRow['end_datetime'];

            // Determine "Booked by" status
            $bookedBy = ($adminId !== null) ? 'Admin' : 'Customer';

            echo '<tr>';
            echo '<td class="align-middle">' . date('M. j, Y', strtotime($startDatetime)) . '</td>';
            echo '<td class="align-middle">' . date('g:ia', strtotime($startDatetime)) . ' - ' . date('g:ia', strtotime($endDatetime)) . '</td>';
            echo '<td class="align-middle">' . $ticketingId . '</td>';
            echo '<td class="align-middle">' . $firstName . ' ' . $lastName . '</td>';
            echo '<td class="align-middle">' . $plateNumber . '</td>';
            echo '<td class="align-middle">' . $bookedBy . '</td>';
            echo '<td class="align-middle">' . $paymentStatus . '</td>';
            echo '<td>';
            
            // Display "Cancel" link only for entries with status 'booked'
            if ($status === 'booked') {
                echo '<button type="button" class="btn btn-danger cancel-btn" data-reservation-id="' . $reserve_id . '" data-toggle="modal" data-target="#cancelModal">Cancel</button>';
                echo ' '; // Add a space character
                echo '<button class="btn btn-primary" onclick="location.href=\'details.php?id=' . $reserve_id . '&ticketId=' . $ticketId . '\'">Details</button>';
                echo ' '; // Add a space character
                
              // Check the value of return_switch_1 and paymentStatus
if ($row['return_switch_1'] == 1 ) {
    echo '<button class="btn btn-info" onclick="location.href=\'review.php?id=' . $reserve_id . '&ticketId=' . $ticketId . '\'">Review 1</button>';
} elseif ($row['return_switch_1'] == 2 && $row['paymentStatus'] !== 'half paid') {
    // echo '<button class="btn btn-success" onclick="location.href=\'review.php?id=' . $reserve_id . '&ticketId=' . $ticketId . '\'">Success</button>';
} elseif ($row['return_switch_1'] == 3) {
    echo '<button class="btn btn-warning review-btn" onclick="location.href=\'review.php?id=' . $reserve_id . '&ticketId=' . $ticketId . '\'">Return</button>';
}


if ($row['return_switch_2'] == 1 ) {
    echo '<button class="btn btn-info" onclick="location.href=\'review-full-payment.php?id=' . $reserve_id . '&ticketId=' . $ticketId . '\'">Review 2</button>';
} elseif ($row['return_switch_2'] == 2 && $row['paymentStatus'] !== 'fully paid') {
    echo '<button class="btn btn-success" onclick="location.href=\'review-full-payment.php?id=' . $reserve_id . '&ticketId=' . $ticketId . '\'">Success 2</button>';
} elseif ($row['return_switch_2'] == 3) {
    echo '<button class="btn btn-warning review-btn" onclick="location.href=\'review-full-payment.php?id=' . $reserve_id . '&ticketId=' . $ticketId . '\'">Return 2</button>';
}


            } elseif ($status === 'canceled' || $status === 'done') {
                // Display only the Details button for canceled and done statuses
                echo '<button class="btn btn-info" onclick="location.href=\'details.php?id=' . $reserve_id . '&ticketId=' . $ticketId . '\'">Details</button>';
            } else {
                // Display Cancel button and Details button
                echo '<button type="button" class="btn btn-danger cancel-btn" data-reservation-id="' . $reserve_id . '" data-toggle="modal" data-target="#cancelModal">Cancel</button>';
                echo ' ';
                echo '<button class="btn btn-info" onclick="location.href=\'details.php?id=' . $reserve_id . '&ticketId=' . $ticketId . '\'">Details</button>';
            }
            
            
            
    echo '</td>';
    echo '</tr>';
     
        }
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    } else {
        echo ' ';
    }
}

?>

