<!-- details.php -->
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
if (isset($_GET['id']) && isset($_GET['ticketId'])) {
    $reserve_id = $_GET['id'];
    $ticketId = $_GET['ticketId'];

    // Fetch car emission details
    $detailsQuery = "SELECT * FROM car_emission WHERE id = '$reserve_id'";
    $detailsResult = $connect->query($detailsQuery);

    if ($detailsResult->num_rows === 1) {
        $details = $detailsResult->fetch_assoc();
        // Now you have the details of the specific car emission entry and ticketId
$event_id = $details['event_id'];
        // Perform actions with $details (e.g., display, update, etc.)
        echo "Car emission details found!";

        // Fetch additional schedule details where id equals $reserve_id
        $scheduleQuery = "SELECT * FROM schedule_list WHERE id = '$event_id'";
        $scheduleResult = $connect->query($scheduleQuery);

        if ($scheduleResult->num_rows > 0) {
            // Now you have the schedule details
            $scheduleDetails = $scheduleResult->fetch_assoc();
            // Perform actions with $scheduleDetails (e.g., display, update, etc.)
        } else {
            // Handle the case where schedule details are not found
            echo "Schedule details not found!";
        }

    } else {
        // Handle the case where car emission details are not found
        echo "Car emission details not found!";
    }
} else {
    // Handle the case where id or ticketId parameters are not provided
    echo "Reservation details not found!";
}
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

</head>

<body id="page-top">
    <div id="wrapper">
        <?php include 'partials/nav.php'?>
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <?php include 'partials/nav-2.php' ?>
                <!-- Display the details content here -->
                <div class="container mt-3">
                    <div class="row d-flex justify-content-center">
                
                        <div class="col-lg-9 ">

                        <!-- <div class="col-lg-5 col-xl-4 col-xxl-4"> -->
                        <div class="">


                        <div class="card shadow mb-4">
    <div class="card-header d-flex justify-content-between align-items-center" style="background: var(--bs-success-text-emphasis);">
        <h6 class="text-primary fw-bold m-0" style="background: Transparent;"><span style="color: rgb(244, 248, 244);">PAYMENT REVIEW 1</span></h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <tbody> <!-- Wrap the table content in tbody for better structure -->
                <tr>
                        <th><span style="font-weight: normal !important;">Plate Number</span></th>
                        <th><span style="font-weight: normal !important;">Amount(PHP)</span></th>
                    </tr>   
                    <tr>
                    <td class="text-center" style="font-size: 23px;"><?php echo $details['plate_number']; ?></td>
                       <td class="text-center" style="font-size: 23px;"><?php echo $details['amount']; ?></td>
                    </tr> 
                <tr>
                <th><span style="font-weight: normal !important;">Ticketing ID</span></th>
                        <th><span style="font-weight: normal !important;">Mode of Payment</span></th>
                    </tr>   
                    <tr>
                    <td class="text-center" style="font-size: 23px;"><?php echo strtoupper($details['ticketing_id']); ?></td>
                       <td class="text-center" style="font-size: 23px;"><?php echo strtoupper($details['paymentMethod']); ?></td>
                    </tr>  
                <tr>
                <th><span style="font-weight: normal !important;">MV Type</span></th>
                        <th><span style="font-weight: normal !important;">Payment Status</span></th>
                    </tr>
                    <tr>
                    <td class="text-center" style="font-size: 23px;"><?php echo strtoupper($details['mv_type']); ?></td>
                        <td class="text-center" style="color: <?php
                            // Set color based on payment status
                            switch ($details['paymentStatus']) {
                                case 'unpaid':
                                    echo 'orange';
                                    break;
                                case 'paid':
                                    echo 'green';
                                    break;
                                default:
                                    echo 'black'; // Default color if none of the above matches
                            }
                        ?>;font-size: 23px;"><?php echo strtoupper($details['paymentStatus']); ?></td>
                    </tr>
                    <tr>
                    <th><span style="font-weight: normal !important;">Date</span></th>
                        <th><span style="font-weight: normal !important;">Book Status</span></th>
                    </tr>
                    <tr>
                    <td class="text-center" style="font-size: 23px;"><?php echo date('M. j, Y', strtotime( $scheduleDetails['start_datetime'])); ?></td>
                        <td class="text-center" style="color: <?php
                            // Set color based on payment status
                            switch ($details['status']) {
                                case 'booked':
                                    echo 'orange';
                                    break;
                                case 'canceled':
                                    echo 'red';
                                    break;
                                    case 'doned':
                                        echo 'green';
                                        break;
                                default:
                                    echo 'black'; // Default color if none of the above matches
                            }
                        ?>;font-size: 23px;"><?php echo strtoupper($details['status']); ?></td>
                    </tr>
                    <tr>
                        <th><span style="font-weight: normal !important;">Time</span></th>
                        <th><span style="font-weight: normal !important;">Reference No.</span></th>
                    </tr>   
                    <tr>
                    <td class="text-center" style="font-size: 23px;"><?php echo date('g:ia', strtotime( $scheduleDetails['start_datetime'])) . ' to ' . date('g:ia', strtotime( $scheduleDetails['end_datetime'])); ?></td>
                       <td class="text-center" style="font-size: 23px;"><?php echo $details['reference1']; ?></td>
                       <tr>
    <th><span style="font-weight: normal !important;">Amount Paid</span></th>
    <th><span style="font-weight: normal !important;">Payment Receipt</span></th>
</tr>
<tr>
<td class="text-center" style="font-size: 23px;"><?php echo $details['payAmount1']; ?></td>
    <td class="text-center">
        <!-- Make "View Payment Receipt" a button with btn-primary class -->
        <button type="button" class="btn btn-primary" onclick="viewPaymentReceipt()">View Payment Receipt</button>
    </td>
</tr>
                </tbody>
            </table>
        </div>
    </div>
</div>




                            <div class="card" style="height: 160px; margin-bottom: 20px;">
    <div class="card-header" style="background: var(--bs-success-text-emphasis);">
        <h6 class="mb-0" style="text-align: center; color: var(--bs-body-bg); font-weight: bold; font-size: 16px;">ACTIONS</h6>
    </div>
    <div class="card-body" style="text-align: center; padding-top: 10px;">
<?php if ($details['status'] !== 'canceled' && $details['status'] !== 'doned') { ?>
    <button class="btn btn-primary m-2" onclick="PaidFunction()">Paid</button>
    <a href="details.php?id=<?php echo $reserve_id; ?>&ticketId=<?php echo $ticketId; ?>" class="btn btn-success m-2">Details</a>
    <button class="btn btn-danger m-2" onclick="cancelBookFunction()">Return</button>
<?php } elseif ($details['status'] === 'doned') { ?>
    <a href="test.php?id=<?php echo $reserve_id; ?>&ticketId=<?php echo $ticketId; ?>" class="btn btn-success m-2" onclick="testFunction()">Test</a>
<?php } else { ?>
    <br>
    <p class="text-danger">Booking is canceled. No further actions allowed.</p>
<?php } ?>
</div>


</div>

<!-- Back button outside the card, positioned lower and larger with increased margin-top -->
<div class="text-end mt-5">
    <button class="btn btn-secondary btn-lg" onclick="goBack()">Back</button>
</div>
       
<script>
    // JavaScript function to navigate back to reservation.php
    function goBack() {
        window.location.href = 'reservation.php';
    }
</script>
</div>
</div>

</div>
</div>




<!-- Modal for displaying the payment receipt -->
<div class="modal fade" id="paymentReceiptModal" tabindex="-1" aria-labelledby="paymentReceiptModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentReceiptModalLabel">Payment Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Display the payment receipt content here -->
                <!-- Use an <img> tag to display the image -->
                <img id="paymentReceiptImage" class="img-fluid" alt="Payment Receipt">
            </div>
        </div>
    </div>
</div>




        <?php include 'partials/footer.php'?>
        <a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>


    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/chart.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/theme.js"></script>


    <script>
    function viewPaymentReceipt() {
        // Set the src attribute of the img tag to the payment receipt image path
        var paymentReceiptImagePath = '../uploads/receipt-image/<?php echo $details['receipt1']; ?>';
        $('#paymentReceiptImage').attr('src', paymentReceiptImagePath);

        // Show the modal
        $('#paymentReceiptModal').modal('show');
    }
</script>


<!-- Paid function modal -->
<div class="modal fade" id="paidModal" tabindex="-1" aria-labelledby="paidModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paidModalLabel">Enter Paid Amount</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Display the receipt image in the modal -->
                <img id="receiptImage" class="img-fluid" alt="Receipt Image">

                <!-- Form for entering the paid amount -->
                <form id="paidForm">
                    <div class="mb-3">
                        <label for="paidAmount" class="form-label">Amount:</label>
                        <input type="text" class="form-control" id="paidAmount" name="paidAmount" required>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="submitPaid()">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>



<script>
    // JavaScript function to handle the "Paid" button click
    function PaidFunction() {
        // Get the receipt image path
        var receiptImagePath = '../uploads/receipt-image/<?php echo $details['receipt1']; ?>';

        // Set the src attribute of the img tag to the receipt image path
        $('#receiptImage').attr('src', receiptImagePath);

        // Show the paid modal
        $('#paidModal').modal('show');
    }

    // JavaScript function to submit the paid amount
    function submitPaid() {
        // Get the paid amount from the input field
        var paidAmount = $('#paidAmount').val();

        // Validate the paid amount (add additional validation if needed)

        // Make an AJAX request to the PHP script for updating the paid amount
        $.ajax({
            url: 'update_paid_amount1.php', // Replace with the actual path to your PHP script
            method: 'POST',
            data: {
                reserveId: <?php echo $reserve_id; ?>,
                ticketId: <?php echo $ticketId; ?>,
                paidAmount: paidAmount
            },
            success: function(response) {
                // Handle the response if needed
                console.log(response);

                // Close the paid modal
                $('#paidModal').modal('hide');
            },
            error: function(error) {
                // Handle errors if needed
                console.error(error);

                // Close the paid modal
                $('#paidModal').modal('hide');
            }
        });
    }
</script>



</body>

</html>