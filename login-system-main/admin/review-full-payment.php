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

        // Add validation here
        if (
            $details['paymentStatus'] == 'pending' ||  // Condition 1
            $details['paymentStatus'] == 'unpaid' ||   // Condition 2
            ($details['paymentStatus'] == 'fully paid' && $details['return_switch_1'] == '2' &&  $details['return_switch_2'] == '') // Condition 3
        ) {
            echo "Access Denied! Reservation cannot be accessed.";
            exit();
        } else {
            echo "Access Granted! Reservation can be accessed.";
        }
        

        // Continue fetching details if validation passed
        $event_id = $details['event_id'];
        $returnSwitch2 = $details['return_switch_2'];
        $returnReason2 = $details['return_reason2'];

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
<style>
    .btn-wide {
    width: 90px; /* Adjust the width as needed */
}

</style>
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
                    <div>      
    <?php if ($returnSwitch2 == 0) : ?>
        <!-- Display warning for Pay Half/Full Now -->
        <div class="card bg-warning p-3 mx-auto"  style="max-width: 1275px; margin-bottom: 10px; height: 60px;"> 
        <p style="color: white; text-align: center; font-size: 20px;">Waiting for Payment </p>
        </div>
    <?php elseif ($returnSwitch2 == 1) : ?>
        <!-- Display On review info color -->
        <div class="card bg-info p-3 mx-auto"  style="max-width: 1275px; margin-bottom: 10px; height: 60px;">
        <p style="color: white; text-align: center; font-size: 20px;">Review Now</p>
        </div>
    <?php elseif ($returnSwitch2 == 2) : ?>
        <!-- Display Payment Success -->
        <div class="card bg-success p-3 mx-auto"  style="max-width: 1275px; margin-bottom: 10px; height: 60px;">
        <p style="color: white; text-align: center; font-size: 20px;">Successful Payment</p>
        </div>
    <?php elseif ($returnSwitch2 == 3) : ?>
        <!-- Display return reason -->
        <div class="card bg-danger p-3 mx-auto" style="max-width: 1275px; margin-bottom: 10px; height: 60px;">
            <p style="color: white; text-align: center; font-size: 20px;"><?php echo $returnReason2; ?></p>
        </div>
    <?php endif; ?>
</div>

                        <div class="col-lg-9 ">
                        <input id="bookingIdInput2"  type="hidden" value="<?php echo $details['id'];  ?>" readonly> </input>
                        <!-- <div class="col-lg-5 col-xl-4 col-xxl-4"> -->
                        <div class="">


                        <div class="card shadow mb-4">
    <div class="card-header d-flex justify-content-between align-items-center" style="background: var(--bs-success-text-emphasis);">
        <h6 class="text-primary fw-bold m-0" style="background: Transparent;"><span style="color: rgb(244, 248, 244);">PAYMENT REVIEW II</span></h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <tbody> <!-- Wrap the table content in tbody for better structure -->
                <tr>
                        <th><span style="font-weight: normal !important;">Plate Number</span></th>
                        <th><span style="font-weight: normal !important;">Vehicle Owner</span></th>
                    </tr>   
                    <tr>
                    <td class="text-center" style="font-size: 23px;"><?php echo $details['plate_number']; ?></td>
                    <td class="text-center" style="font-size: 23px;">
    <span style="font-size: 14px; font-style: italic;">
        <?php echo strtoupper($details['customer_first_name'] . ' ' . $details['customer_middle_name'] . ' ' . $details['customer_last_name']); ?>
    </span>
</td>


                    </tr> 
                <tr>
                <th><span style="font-weight: normal !important;">Ticketing ID</span></th>
                <th><span style="font-weight: normal !important;">Amount(PHP)</span></th>
                    </tr>   
                    <tr>
                    <td class="text-center" style="font-size: 23px;"><?php echo strtoupper($details['ticketing_id']); ?></td>
                    <td class="text-center" style="font-size: 23px;"><?php echo $details['amount']; ?></td>
                    </tr>  
                <tr>
                <th><span style="font-weight: normal !important;">MV Type</span></th>
                <th><span style="font-weight: normal !important;">Mode of Payment</span></th>  
                    </tr>
                    <tr>
                    <td class="text-center" style="font-size: 23px;"><?php echo strtoupper($details['mv_type']); ?></td>
                    <td class="text-center" style="font-size: 23px;"><?php echo strtoupper($details['paymentMethod2']); ?></td>
                    </tr>
                    <tr>
                    <th><span style="font-weight: normal !important;">Date</span></th>
                    <th><span style="font-weight: normal !important;">Payment Status</span></th>
                    </tr>
                    <tr>
                    <td class="text-center" style="font-size: 23px;"><?php echo date('M. j, Y', strtotime( $scheduleDetails['start_datetime'])); ?></td>
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
                        <th><span style="font-weight: normal !important;">Time</span></th>
                        <th><span style="font-weight: normal !important;">Book Status</span></th>
                    </tr>   
                    <tr>
                    <td class="text-center" style="font-size: 23px;"><?php echo date('g:ia', strtotime( $scheduleDetails['start_datetime'])) . ' to ' . date('g:ia', strtotime( $scheduleDetails['end_datetime'])); ?></td>
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
                       <tr>
    <th><span style="font-weight: normal !important;">Amount Paid I</span></th>
    <th><span style="font-weight: normal !important;">Reference No. II</span></th>
</tr>
<tr>
<td class="text-center" style="font-size: 23px;"><?php echo !empty($details['payAmount1']) ? $details['payAmount1'] : '0'; ?></td>
<td class="text-center" style="font-size: 23px;"><?php echo !empty($details['reference2']) ? $details['reference2'] : '0'; ?></td>
</tr>

<tr>
    <th><span style="font-weight: normal !important;">Amount Paid II</span></th>
    <th><span style="font-weight: normal !important;">Payment Receipt II</span></th>
</tr>
<tr>
<td class="text-center" style="font-size: 23px;"><?php echo !empty($details['payAmount2']) ? $details['payAmount2'] : '0'; ?></td>
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
<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Payment Receipt Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                  
                </button>
            </div>
            <div class="modal-body">
                <img id="previewImage" src="#" alt="Payment Receipt" class="img-fluid">
            </div>
        </div>
    </div>
</div>



<script>
function previewReceipt() {
    var input = document.getElementById('paymentImage2'); // Corrected ID here
    var file = input.files[0];
    var reader = new FileReader();
    reader.onload = function (e) {
        $('#previewImage').attr('src', e.target.result);
        $('#previewModal').modal('show');
    }
    reader.readAsDataURL(file);
}

</script>

       
<script>
    // JavaScript function to navigate back to reservation.php
    function goBack() {
        window.location.href = 'reservation.php';
    }
</script>
</div>
</div>
<div class="col-lg-3">

<div class="card shadow mb-4">
<div class="card-header d-flex justify-content-between align-items-center" style="background: var(--bs-success-text-emphasis);">
<h6 class="text-primary fw-bold m-0" style="background: Transparent;"><span style="color: rgb(244, 248, 244);">PAYMENT DETAIL II</span></h6>
</div>
<div class="card-body">
<div class="table-responsive">
<table class="table">
<tbody>
<tr>
      <th><span style="font-weight: normal !important;">Pay Now</span></th>
  </tr>   
  <tr>
  <td class="" style="font-size: normal;">
<label for="payhalf2">Half Payment: <?php echo $details['amount']/2 ?></label> <span id="payhalf2"></span><br>
<span>or</span><br>
<label for="payfull1">Full Payment:  <?php echo $details['amount'] ?></label> <span id="payfull1"></span> 
</td>

  </tr> 
  <tr>
      <th><span style="font-weight: normal !important;">Payment Method</span></th>
  </tr>
  <tr>
      <td class="" style="font-size: normal;">
      <input type="radio" id="cash" name="payment_method" value="cash" onclick="updateAccountDetails('cash')">
          <label for="cash">Cash</label><br>
          <input type="radio" id="gcash" name="payment_method" value="gcash" onclick="updateAccountDetails('gcash')">
          <label for="gcash">Gcash</label><br>
          <input type="radio" id="paymaya" name="payment_method" value="paymaya" onclick="updateAccountDetails('paymaya')">
          <label for="paymaya">PayMaya</label><br>
      </td>
  </tr> 
  <tr>
      <th><span style="font-weight: normal !important;">Account Details</span></th>
  </tr>   
  <tr>
      <td class="" style="font-size: normal;">
          <label for="account_name">Name:</label> <span id="account_name"></span><br>
          <label for="account_number">Number:</label> <span id="account_number"></span> <i id="copy_icon" class="far fa-clipboard" style="display: none;" onclick="copyToClipboard('account_number')"></i><br>
      </td>
  </tr> 
  <tr>
<th><span style="font-weight: normal !important;">Payment Receipt Input</span></th>
</tr>
<tr>
<td style="text-align: center;">
  <div style="margin-bottom: 10px;">
      <input type="file" class="form-control" id="paymentImage2" accept="image/*">
  </div>
  <div>
      <button type="button" class="btn btn-primary" onclick="previewReceipt()">Preview Payment Picture</button>
  </div>
</td>
</tr>
  <tr>
      <th><span style="font-weight: normal !important;">Reference No. II</span></th>
  </tr>
  <td class="text-center">
<input type="text" id="referenceNumberInput2" class="form-control" style="font-size: normal;" value="<?php echo $details['reference2']; ?>">
</td>

</tbody>
</table>
</div>
</div>
</div>

<script>
    // Function to check the payment method and update account details
    function updatePaymentMethodAndDetails() {
        // Get the payment method from PHP
        var paymentMethod = "<?php echo $details['paymentMethod2']; ?>";

        // Check the payment method and update accordingly
        if (paymentMethod === 'paymaya') {
            // Click the PayMaya radio button
            document.getElementById('paymaya').checked = true;

            // Update account details for PayMaya
            updateAccountDetails('paymaya');
        } else if (paymentMethod === 'gcash') {
            // Click the Gcash radio button
            document.getElementById('gcash').checked = true;

            // Update account details for Gcash
            updateAccountDetails('gcash');
        }
    }

    // Call the function when the page loads
    window.onload = function() {
        updatePaymentMethodAndDetails();
        disableElementsIfLocked();
    };
</script>
<script>
    // Get the payment lock status from PHP
    var paymentLockStatus = <?php echo $returnSwitch2; ?>;

    // Function to disable elements if paymentLock1 equals 1
    function disableElementsIfLocked() {
        if (paymentLockStatus === 2) {
            // Disable the "Pay Now" button
            document.getElementById('paid1').disabled = true;

            // Disable the payment image input
            document.getElementById('save1').disabled = true;

            // Disable the reference number input
            document.getElementById('return1').disabled = true;
        }
    }

    
</script>

<script>
function updateAccountDetails(method) {
    var accountName = document.getElementById("account_name");
    var accountNumber = document.getElementById("account_number");
    var copyIcon = document.getElementById("copy_icon");

    if (method === 'gcash') {
        accountName.textContent = "Richard Alaurin";
        accountNumber.textContent = "0823123213";
    } else if (method === 'paymaya') {
        accountName.textContent = "Gilene Mardo";
        accountNumber.textContent = "09756253";
    } else if (method === 'cash') {
        // Set the admin username as the account name for cash payment
        // You can replace 'adminUsername' with the actual admin username variable
        var adminUsername = "<?php echo $username; ?>";
        accountName.textContent = adminUsername;
        accountNumber.textContent = ""; // Clear account number for cash payment
    }

    if (accountNumber.textContent !== "") {
        copyIcon.style.display = "inline";
    } else {
        copyIcon.style.display = "none";
    }
}


function viewPaymentReceipt() {
// Functionality to view payment receipt
}

function copyToClipboard(elementId) {
var element = document.getElementById(elementId);
var text = element.textContent;

var textarea = document.createElement('textarea');
textarea.value = text;
document.body.appendChild(textarea);

textarea.select();
document.execCommand('copy');

document.body.removeChild(textarea);
alert('Copied to clipboard: ' + text);
}
</script>
<div class="card" style="height: 160px; margin-bottom: 20px;">
    <div class="card-header" style="background: var(--bs-success-text-emphasis);">
        <h6 class="mb-0" style="text-align: center; color: var(--bs-body-bg); font-weight: bold; font-size: 16px;">ACTIONS</h6>
    </div>
    <div class="card-body" style="text-align: center; padding-top: 10px;">
        <?php if ($details['status'] !== 'canceled' && $details['status'] !== 'doned') { ?>
            
            <button id="paid1" class="btn btn-primary m-2 btn-wide" onclick="PaidFunction()">Paid</button>
            <button id="save1" class="btn btn-info m-2 btn-wide" onclick="savePayment1()">Save</button>
            <a href="details.php?id=<?php echo $reserve_id; ?>&ticketId=<?php echo $ticketId; ?>" class="btn btn-success m-2 btn-wide">Details</a>
            <button id="return1" class="btn btn-danger m-2 btn-wide" onclick="returnPayment1()">Return</button>
           
        <?php } elseif ($details['status'] === 'doned') { ?>
            <a href="test.php?id=<?php echo $reserve_id; ?>&ticketId=<?php echo $ticketId; ?>" class="btn btn-success m-2 btn-wide" onclick="testFunction()">Test</a>
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
<br>
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




<!-- Modal for confirming return payment -->
<div class="modal fade" id="returnConfirmationModal" tabindex="-1" aria-labelledby="returnConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="returnConfirmationModalLabel">Confirm Return Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Choose reason(s) for return:</p>
                
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="Unmatch payment receipt" id="returnWrongPaymentReceipt">
                    <label class="form-check-label" for="returnWrongPaymentReceipt">Unmatch payment receipt</label>
                </div>
             
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="Invalid payment receipt" id="returnInvalidPaymentReceipt">
                    <label class="form-check-label" for="returnInvalidPaymentReceipt">Invalid payment receipt</label>
                </div>
                
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="Duplicate payment receipt" id="returnDuplicatePayment">
                    <label class="form-check-label" for="returnDuplicatePayment">Duplicate payment receipt</label>
                </div>
                
              
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmReturnPayment()">Confirm</button>
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
        var paymentReceiptImagePath = '../uploads/receipt-image/<?php echo $details['receipt2']; ?>';
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
    // Function to display the modal for confirming the return payment
function showReturnConfirmationModal() {
    $('#returnConfirmationModal').modal('show'); // Show the modal
}

// Function to handle the return payment action
function returnPayment1() {
    // Show the confirmation modal
    showReturnConfirmationModal();
}

function confirmReturnPayment() {
    // Get the selected return reasons
    var returnReasons = [];
    $('input[type="checkbox"]:checked').each(function() {
        returnReasons.push($(this).val());
    });

    // Perform actions to return the payment here
    var bookingId = $('#bookingIdInput2').val(); // Get the booking ID
    var referenceNumber = $('#referenceNumberInput2').val(); // Get the reference number
    
    // Send an AJAX request to update the payment status
    $.ajax({
        url: 'return_payment2.php', // Specify the PHP file to handle the return payment process
        type: 'POST',
        data: { 
            bookingId: bookingId, 
            referenceNumber: referenceNumber, 
            returnReasons: returnReasons // Pass the selected return reasons as an array
        },
        success: function(response) {
            // Handle the success response, if needed
            console.log(response);
            // For example, you can display a success message or reload the page
            alert('Payment returned successfully.');
            window.location.reload(); // Reload the page to reflect changes
        },
        error: function(xhr, status, error) {
            // Handle the error response, if needed
            console.error(xhr.responseText);
            // For example, you can display an error message
            alert('Error returning payment. Please try again.');
        }
    });
}



    </script>

<script>
    // JavaScript function to handle the "Paid" button click
    function PaidFunction() {
        // Get the receipt image path
        var receiptImagePath = '../uploads/receipt-image/<?php echo $details['receipt2']; ?>';

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
            url: 'update_paid_amount2.php', // Replace with the actual path to your PHP script
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

<script>
function savePayment1() {
    var bookingId = $('#bookingIdInput2').val();
    var referenceNumber = document.getElementById('referenceNumberInput2').value;
    var paymentMethodRadio = document.querySelector('input[name="payment_method"]:checked');

    // Check if a payment method radio button is selected
    var paymentMethod = paymentMethodRadio ? paymentMethodRadio.value : '';

    var paymentImage = document.getElementById('paymentImage2').files[0];
    // Check if paymentImage is null
    paymentImage = paymentImage ? paymentImage : '';

    var formData = new FormData();
    formData.append('bookingId', bookingId);
    formData.append('referenceNumber', referenceNumber);
    formData.append('paymentMethod', paymentMethod);
    formData.append('paymentImage', paymentImage);

    // Send AJAX request
    $.ajax({
        url: 'update-save-payment-2.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            // Handle success response
            console.log(response);
        },
        error: function(xhr, status, error) {
            // Handle error
            console.error(xhr.responseText);
        }
    });
}


</script>


</body>

</html>