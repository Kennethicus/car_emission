<?php
session_start();

include("connect/connection.php");

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // If not logged in, redirect to the login page
    header('Location: index.php');return
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

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $bookingId = $_GET['id'];

    // Fetch details of the selected booking from the 'car_emission' table
    $bookingQuery = $connect->prepare("SELECT * FROM car_emission WHERE id = ?");
    if (!$bookingQuery) {
        die("Error in booking query: " . $connect->error);
    }

    $bookingQuery->bind_param("i", $bookingId);
    $bookingQuery->execute();

    $bookingResult = $bookingQuery->get_result();

    if (!$bookingResult) {
        die("Error fetching booking details: " . $bookingQuery->error);
    }

    $bookingDetails = $bookingResult->fetch_assoc();

    // Close the booking query statement
    $bookingQuery->close();

    // Check if a valid booking was found
    if (!$bookingDetails) {
        // If no booking found, redirect to the schedule status page or show an appropriate message
        header('Location: schedule-status.php');
        exit();
    }

    // Now, fetch details from the 'schedule list' based on the 'event_id' from the 'car_emission' table
    $event_id = $bookingDetails['event_id'];

    $scheduleQuery = $connect->prepare("SELECT * FROM schedule_list WHERE id = ?");
    if (!$scheduleQuery) {
        die("Error in schedule query: " . $connect->error);
    }

    $scheduleQuery->bind_param("i",     $event_id);
    $scheduleQuery->execute();

    $scheduleResult = $scheduleQuery->get_result();

    if (!$scheduleResult) {
        die("Error fetching schedule details: " . $scheduleQuery->error);
    }

    $scheduleDetails = $scheduleResult->fetch_assoc();

    // Close the schedule query statement
    $scheduleQuery->close();
    $paymentLock1 = $bookingDetails['paymentlock1'];
    $paymentLock1 = $bookingDetails['paymentlock1'];
    $paymentMethod1 = $bookingDetails['paymentMethod'];
    $carPicturePath = $bookingDetails['car_picture'];
    $returnSwitch1 = $bookingDetails['return_switch_1'];
    $returnReason1 = $bookingDetails['return_reason1'];

$userName = $bookingDetails['customer_first_name'];

    // Now you have $scheduleDetails with information from the 'schedule list' related to the 'event_id'
} else {
    // If 'id' parameter is not set, redirect to the schedule status page
    header('Location: schedule-status.php');
    exit();
}
?>


<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Log in - Brand</title>
    <link rel="stylesheet" href="admin/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&amp;display=swap">
    <!-- <link rel="stylesheet" href="admin/assets/css/aos.min.css">
    <link rel="stylesheet" href="admin/assets/css/NavBar-with-pictures.css"> -->
<!-- Bootstrap CSS -->

<!-- Font Awesome for the clipboard icon -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<style>
    
</style>
</head>

<body style=" background-color: #f8f9fa;">
  <?php include 'partials/nav.php'?>
    <div class="container mt-3">  
                    <form    method="POST"  name="bookingForm">
                    <div class="row d-flex justify-content-center">
           
                    <div>      
    <?php if ($returnSwitch1 == 0) : ?>
        <!-- Display warning for Pay Half/Full Now -->
        <div class="card bg-warning p-3 mx-auto"  style="max-width: 1275px; margin-bottom: 10px; height: 60px;"> 
        <p style="color: white; text-align: center; font-size: 20px;">Pay Half or Full Now </p>
        </div>
    <?php elseif ($returnSwitch1 == 1) : ?>
        <!-- Display On review info color -->
        <div class="card bg-info p-3 mx-auto"  style="max-width: 1275px; margin-bottom: 10px; height: 60px;">
        <p style="color: white; text-align: center; font-size: 20px;">On Review</p>
        </div>
    <?php elseif ($returnSwitch1 == 2) : ?>
        <!-- Display Payment Success -->
        <div class="card bg-success p-3 mx-auto"  style="max-width: 1275px; margin-bottom: 10px; height: 60px;">
        <p style="color: white; text-align: center; font-size: 20px;">Payment Success</p>
        </div>
    <?php elseif ($returnSwitch1 == 3) : ?>
        <!-- Display return reason -->
        <div class="card bg-danger p-3 mx-auto" style="max-width: 1275px; margin-bottom: 10px; height: 60px;">
            <p style="color: white; text-align: center; font-size: 20px;"><?php echo $returnReason1; ?></p>
        </div>
    <?php endif; ?>
</div>



                   
                        <div class="col-lg-9">
                            
                        <input id="bookingIdInput1"  type="hidden" value="<?php echo $bookingDetails['id'];  ?>" readonly> </input>
                        <!-- <div class="col-lg-5 col-xl-4 col-xxl-4"> -->
                        <div class="">
                       <div class="card shadow mb-4">
    <div class="card-header d-flex justify-content-between align-items-center" style="background: var(--bs-success-text-emphasis);">
        <h6 class="text-primary fw-bold m-0" style="background: Transparent;"><span style="color: rgb(244, 248, 244);">DETAIL</span></h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <tbody> <!-- Wrap the table content in tbody for better structure -->
                <tr>

                <th><span style="font-weight: normal !important;">Plate Number</span></th>
                        <th><span style="font-weight: normal !important;">Amount</span></th>
                    </tr>   
                    <tr>
                    <td id ="plateNumberValue" class="text-center" style="color: ;font-size: 23px;" ><?php echo $bookingDetails['plate_number']; ?></td>
                    <td id ="amountValue" class="text-center" style="color: ;font-size: 23px;" ><?php echo $bookingDetails['amount']; ?></td>
                    </tr> 
                <tr>
                <th><span style="font-weight: normal !important;">Ticketing ID</span></th>
                        <th><span style="font-weight: normal !important;">Mode of payment</span></th>
                    </tr>   
                    <tr>
                    <td id ="ticketingValue" class="text-center" style="color: ;font-size: 23px;" ><?php echo $bookingDetails['ticketing_id']; ?></td>
                    <td id ="ticketingValue" class="text-center" style="color: ;font-size: 23px;" ><?php echo $bookingDetails['paymentMethod']; ?></td>
                    </tr>  
                <tr>  <th><span style="font-weight: normal !important;">MV Type</span></th>
                        <th><span style="font-weight: normal !important;">Payment Status</span></th>
                    </tr>
                    <tr> <td id ="mvTypeValue" class="text-center" style="color: ;font-size: 23px;" ><?php echo strtoupper($bookingDetails['mv_type']); ?></td>
                        <td id ="paymentStatusValue" class="text-center" style="color: ;font-size: 23px;" ><?php echo strtoupper($bookingDetails['paymentStatus']); ?></td>
                    </tr>
                    
                    <tr>
                    <th><span style="font-weight: normal !important;">Date</span></th>
    <th><span style="font-weight: normal !important;">Book Status</span></th>
</tr>
<tr>
<td class="text-center" style="font-size: 23px;"><?php echo date('M. j, Y', strtotime( $scheduleDetails['start_datetime'])); ?></td>
<td class="text-center" style="font-size: 23px; color:orange;"><?php echo strtoupper($bookingDetails['status']); ?></td>
</tr>
<tr>
    <th><span style="font-weight: normal !important;">Time</span></th>
    <th><span style="font-weight: normal !important;">Reference No.</span></th>
</tr>
<tr>
<td class="text-center" style="font-size: 23px;"><?php echo date('g:ia', strtotime( $scheduleDetails['start_datetime'])) . ' to ' . date('g:ia', strtotime( $scheduleDetails['end_datetime'])); ?></td>
<td id="referenceValue1" class="text-center" style="font-size: 23px;"><?php echo strtoupper($bookingDetails['reference1']); ?></td>
</tr>
<tr>
<th><span style="font-weight: normal !important;">Amount Paid</span></th>
<th><span style="font-weight: normal !important;">Payment Receipt</span></th>
</tr>

<tr>
</tr>
                  

<tr>
<td id="payAmount1" class="text-center" style="font-size: 23px;"><?php echo strtoupper($bookingDetails['payAmount1']); ?></td>
    <td class="text-center">
        <!-- Make "View Payment Receipt" a button with btn-primary class -->
        <button id="paymentReceiptButton" type="button" class="btn btn-primary" onclick="viewPaymentReceipt()">View Payment Receipt</button>

    </td>
</tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div>
</div>




<!-- Modal HTML structure -->
<div class="modal fade" id="confirmGoBackModal" tabindex="-1" role="dialog" aria-labelledby="confirmGoBackModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmGoBackModalLabel">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to go back to home.php? Any unsaved changes will be lost.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="navigateBack()">Go Back</button>
            </div>
        </div>
    </div>
</div>


</div>







                      </div>
                      <div class="col-lg-3">

                      <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center" style="background: var(--bs-success-text-emphasis);">
            <h6 class="text-primary fw-bold m-0" style="background: Transparent;"><span style="color: rgb(244, 248, 244);">PAYMENT DETAIL</span></h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                    <tr>
                            <th><span style="font-weight: normal !important;">Submit</span></th>
                        </tr>   
                        <tr>
                        <td class="" style="font-size: normal;">
    <label for="payhalf2">Half Payment: <?php echo $bookingDetails['amount']/2 ?></label> <span id="payhalf2"></span><br>
    <span>or</span><br>
    <label for="payfull1">Full Payment:  <?php echo $bookingDetails['amount'] ?></label> <span id="payfull1"></span> 
</td>

                        </tr> 
                        <tr>
                            <th><span style="font-weight: normal !important;">Payment Method</span></th>
                        </tr>
                        <tr>
                            <td class="" style="font-size: normal;">
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
                            <input type="file" class="form-control" id="paymentImage1" accept="image/*">
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary" onclick="previewReceipt()">Preview Payment Picture</button>
                        </div>
                    </td>
                </tr>
                        <tr>
                            <th><span style="font-weight: normal !important;">Reference No.</span></th>
                        </tr>
                        <td class="text-center">
        <input type="text" id="referenceNumberInput" class="form-control" style="font-size: normal;" value="<?php echo $bookingDetails['reference1']; ?>">
    </td>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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
<div class="card" style="margin-bottom: 20px;">
    <div class="card-header" style="background: var(--bs-success-text-emphasis);">
        <h6 class="mb-0" style="text-align: center; color: var(--bs-body-bg); font-weight: bold; font-size: 16px;">ACTIONS</h6>
    </div>
    <div class="card-body" style="text-align: center; padding-top: 10px;">
    <button id="payNow1" type="button" class="btn btn-success m-2" onclick="showConfirmationModal()">Pay Now</button>

 <?php
        // Check if paymentMethod is equal to 'pending' and status is not equal to 'canceled'
        if (
            strtolower($bookingDetails['paymentMethod']) === 'pending' &&
            strtolower($bookingDetails['status']) !== 'canceled'
        ) {
        ?>
            <!-- Modify your existing "Submit Booking" button -->
            <!-- <button type="button" class="btn btn-warning" onclick="openEditModal()">Edit Booking</button> -->
            <button type="button" class="btn btn-danger m-2" onclick="cancelFunction()">Cancel</button>
        <?php
        }
        ?>

    </div>
</div>
<div class="text-end mt-5">
    <button type="button" class="btn btn-secondary btn-lg" data-bs-toggle="modal" data-bs-target="#confirmGoBackModal">Back</button>
</div>
<br>
                      </div>
                </div>
            </div>  
        </form> 
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




<!-- Modal for confirmation -->
<div class="modal fade" id="confirmationModal" tabindex="-1"  aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirm Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                   
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to proceed with the payment?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="payNow()">Continue</button>
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

  <?php include 'partials/footer.php' ?>

    <script src="admin/assets2/bootstrap/js/bootstrap.min.js"></script>
    <!-- <script src="admin/assets2/js/aos.min.js"></script> -->
    <script src="admin/assets2/js/bs-init.js"></script>
    <!-- <script src="admin/assets2/js/bold-and-bright.js"></script> -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
function previewReceipt() {
    var input = document.getElementById('paymentImage1'); // Corrected ID here
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
   function viewPaymentReceipt() {
    // Check if the payment receipt image path is empty or null
    var paymentReceiptImagePath = '<?php echo $bookingDetails['receipt1']; ?>';
    if (paymentReceiptImagePath === null || paymentReceiptImagePath === '') {
        // If no payment receipt available, disable the button and return
        $('#paymentReceiptButton').prop('disabled', true);
        return;
    }

    // Set the src attribute of the img tag to the payment receipt image path
    $('#paymentReceiptImage').attr('src', 'uploads/receipt-image/' + paymentReceiptImagePath);

    // Show the modal
    $('#paymentReceiptModal').modal('show');
}

</script>
<script>
  // Get the element by ID
var amountValueElement = document.getElementById('payAmount1');

// Check if the element is empty before setting the placeholder text
if (!amountValueElement.innerText.trim()) {
    // Set the placeholder text
    amountValueElement.innerText = '0';
}

// Repeat the same process for the referenceValue1 element
var referenceValueElement = document.getElementById('referenceValue1');

if (!referenceValueElement.innerText.trim()) {
    referenceValueElement.innerText = '0';
}

</script>


<script>
    function openEditModal() {
        // You can customize this function to include any logic you need
        $('#editBookingModal').modal('show');
    }

    function proceedWithEdit() {
        // Use the correct variable name: $bookingId instead of $bookingID
        var bookingId = '<?php echo $bookingId; ?>';
        window.location.href = 'edit-booking.php?id=' + bookingId;
    }
</script>

<script>
    // Get the payment lock status from PHP
    var paymentLockStatus = <?php echo $paymentLock1; ?>;

    // Function to disable elements if paymentLock1 equals 1
    function disableElementsIfLocked() {
        if (paymentLockStatus === 1) {
            // Disable the "Pay Now" button
            document.getElementById('payNow1').disabled = true;

            // Disable the payment image input
            document.getElementById('paymentImage1').disabled = true;

            // Disable the payment method radio buttons
            var paymentMethodRadios = document.querySelectorAll('input[name="payment_method"]');
            paymentMethodRadios.forEach(function(radio) {
                radio.disabled = true;
            });

            // Disable the reference number input
            document.getElementById('referenceNumberInput').disabled = true;
        }
    }

    // Call the function when the page loads
    window.onload = disableElementsIfLocked;
</script>

<script>
    // Function to check the payment method and update account details
    function updatePaymentMethodAndDetails() {
        // Get the payment method from PHP
        var paymentMethod = "<?php echo $bookingDetails['paymentMethod']; ?>";

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
    function navigateBack() {
        // Redirect to home.php
        window.location.href = 'schedule-status.php';
    }
</script>



<script>
    // Function to show the confirmation modal
    function showConfirmationModal() {
        // Validate fields before showing the modal
        var referenceNumberInput = document.getElementById('referenceNumberInput');
        var paymentMethodInputs = document.querySelectorAll('input[name="payment_method"]');
        var paymentImageInput = document.getElementById('paymentImage1');

        var isValid = true;

        // Check if reference number is empty
        if (referenceNumberInput.value.trim() === '') {
            referenceNumberInput.style.borderColor = 'red'; // Change border color to red
            isValid = false;
        } else {
            referenceNumberInput.style.borderColor = ''; // Revert border color
        }

        // Check if at least one payment method is selected
        var paymentMethodSelected = false;
        paymentMethodInputs.forEach(function(input) {
            if (input.checked) {
                paymentMethodSelected = true;
            }
        });
        if (!paymentMethodSelected) {
            paymentMethodInputs.forEach(function(input) {
                input.style.outline = '1px solid red'; // Change border color to red
            });
            isValid = false;
        } else {
            paymentMethodInputs.forEach(function(input) {
                input.style.outline = ''; // Revert border color
            });
        }

        // Check if payment image is not uploaded
        if (!paymentImageInput.files[0]) {
            paymentImageInput.style.borderColor = 'red'; // Change border color to red
            isValid = false;
        } else {
            paymentImageInput.style.borderColor = ''; // Revert border color
        }

        // If all fields are valid, show the confirmation modal
        if (isValid) {
            $('#confirmationModal').modal('show');
        }
    }

    // Remove red border color once there are values
    $('#referenceNumberInput, input[name="payment_method"], #paymentImage1').on('input', function() {
        if (this.value.trim() !== '') {
            this.style.borderColor = ''; // Revert border color
        }
    });

    // Remove red border color from radio buttons when clicked
    $('input[name="payment_method"]').on('click', function() {
        $('input[name="payment_method"]').css('outline', ''); // Revert border color from all radio buttons
    });
</script>


    
<script>

function payNow() {
    var bookingId = $('#bookingIdInput1').val();
    var referenceNumber = document.getElementById('referenceNumberInput').value;
    var paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
    var paymentImage = document.getElementById('paymentImage1').files[0];
 

    var formData = new FormData();
    formData.append('bookingId', bookingId);  // Add this line to include bookingId in the FormData
    formData.append('referenceNumber', referenceNumber);
    formData.append('paymentMethod', paymentMethod);
    formData.append('paymentImage', paymentImage);

    // Send AJAX request
    $.ajax({
        url: 'handle-payment1.php',
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
