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

    $scheduleQuery->bind_param("i", $event_id);
    $scheduleQuery->execute();

    $scheduleResult = $scheduleQuery->get_result();

    if (!$scheduleResult) {
        die("Error fetching schedule details: " . $scheduleQuery->error);
    }

    $scheduleDetails = $scheduleResult->fetch_assoc();

    // Close the schedule query statement
    $scheduleQuery->close();
    
    $carPicturePath = $bookingDetails['car_picture'];
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
</head>

<body style=" background-color: #f8f9fa;">
  <?php include 'partials/nav.php'?>
    <div class="container mt-3">  
                    <form    method="POST"  name="bookingForm">
                    <div class="row">
                
                    <div class="col-12 col-md-8 col-lg-9 mx-auto">          
                
    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center" style="background: var(--bs-success-text-emphasis);">
            <h6 class="text-primary fw-bold m-0"><span style="color: rgb(244, 248, 244);">MOTOR VEHICLE INFORMATION</span></h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">    
                                            <tbody>

                                            <!-- <tr>
                                                <td style="text-align: right;"><input id="userId" class="" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="hidden" value="<?php echo $customerDetails['user_id']; ?>" readonly disabled></td>
                                                <td class="text-end"><input id="sched_id" class="" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="hidden" value="<?php echo $event['id']; ?>" readonly disabled></td>   
                                                </tr> -->
                                                <tr>
   
   <td class="text-end" style="vertical-align: middle;">Ticketing ID<input id="ticketingId" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['ticketing_id']; ?>" type="text" readonly disabled></td>
   <td class="text-end" style="vertical-align: middle;">Application Date<input id="appDate" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['app_date']; ?>" type="text" readonly disabled></td>
   
   </tr>
                                            <tr>
                                            <!-- <td  class="text-end">App Date<input type="text" style="margin-left: 5px;" ></td> -->
   

                                            <td class="text-end" type="text" style="width: 200px;">Plate Number<input id="plateNumber" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;"  value="<?php echo $bookingDetails['plate_number']; ?>"  readonly disabled></td>
                                                    <td class="text-end" style="width: 200px;">Organization<input id="organizationInput" type="text" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['organization']; ?>"  readonly disabled></td>
                                                </tr>
                                               <tr>
                                                    <!-- <td class="text-end" style="width: 200px;">Ticketing ID<input type="text" style="margin-left: 5px;" ></td> -->
                                                    <td class="text-end">
                 <!-- Input for uploading a picture -->
                <label for="carPictureInput" class="btn btn-primary">
                Car Picture
                <input type="file" id="carPictureInput" class="d-none" accept="image/*" onchange="displaySelectedPicture(this)" disabled>
                </label>
                <!-- Button to display the selected picture -->
                <button type="button" class="btn btn-success" onclick="displaySelectedPicture()" id="selectedPictureFilename">Display</button>
                </td>
                                                    <td class="text-end">First Name<input id="firstnameInput" type="text" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['customer_first_name']; ?>"  readonly disabled></td>
                                                </tr> 
                                                <tr>
                                                <td class="text-end">
                 <!-- Input for uploading a picture -->
                 <label for="OrInputPicture" class="btn btn-primary">
    OR Picture
    <input type="file" id="OrInputPicture" class="d-none" accept="image/*" onchange="displaySelectedOrPicture(this)" disabled>
</label>
                <!-- Button to display the selected picture -->
                <button type="button" class="btn btn-success" onclick="displaySelectedOrPicture(this)" id="selectedOrFilename">Display</button>
                </td>
                <td class="text-end" style="width: 200px;">Middle Name<input id="middleNameInput" type="text" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['customer_middle_name']; ?>"  readonly disabled></td>
                                            
                                                <tr>
                                                <td class="text-end">
                 <!-- Input for uploading a picture -->
                 <label for="CrInputPicture" class="btn btn-primary">
                CR Picture
                <input type="file" id="CrInputPicture" class="d-none" accept="image/*" onchange="displaySelectedCrPicture(this)" disabled>
                </label>
                <!-- Button to display the selected picture -->
                <button type="button" class="btn btn-success" onclick="displaySelectedCrPicture()" id="selectedCrFilename">Display</button>  
                </td>
                                                
                                                    <td class="text-end" style="width: 200px;">Last Name<input id="lastNameInput" type="text" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['customer_last_name']; ?>" readonly disabled></td>
                                                </tr>
                                                <tr>
   
                                                <td class="text-end" style="vertical-align: middle;">Vehicle CR No.<input id="vehicleCrInput" type="text" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['vehicle_cr_no']; ?>"  readonly disabled></td>
                                                <td class="text-end" style="width: 200px;">Address<input id="addressInput" type="text" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['address']; ?>"  readonly disabled></td>
                                                </tr>
                                                <tr>
                                                <td style="text-align: right;">Vehicle OR No.<input id="vehicleOrInput" type="text"  class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['vehicle_or_no']; ?>" readonly disabled></td>
                                                <td class="text-end">Engine<input id="engineInput" class="form-control" type="text" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['engine']; ?>"  readonly disabled></td>
                                           
                                                </tr>
                                            
                                                <tr>
                                                <td class="text-end">First Registration Date
                                                <input id="firstRegInput" type="date" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['first_reg_date']; ?>" readonly disabled>
    </td>
    <td style="text-align: right;">Chassis<input type="text" id="chassisInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['chassis']; ?>"  readonly disabled></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">Year Model
                                                    <input id="yearModelInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['year_model']; ?>"  readonly disabled> <!-- Adjust the width as needed -->
                                                    
                                                    </td>
                                                    <td class="text-end">Make
                                                    <input name="makeInput" id="makeInput"  class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;"  value="<?php echo $bookingDetails['make']; ?>" readonly disabled>
                        <!-- Populate options dynamically based on your requirements -->
                                                  
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">Fuel type
                                                    <input name="fuelTypeInput" id="fuelTypeInput" type="text" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['fuel_type']; ?>" readonly disabled>
                        <!-- Populate options dynamically based on your requirements -->
                
                                                    </td>
                                                    <td class="text-end">Series
                                                    <input name="seriesInput" id="seriesInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['series']; ?>" readonly disabled>
                                                <!-- Populate options dynamically based on your requirements -->
                                                   
                                                </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">Purpose
                                                    <input name="purposeInput" id="purposeInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['purpose']; ?>" readonly disabled>
                                                    <!-- Populate options dynamically based on your requirements -->
                                                  
                                                    </td>
                                                    <td class="text-end">Color
                                                    <input name="colorInput" id="colorInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['color']; ?>" readonly disabled>
                                                    <!-- Populate options dynamically based on your requirements -->
       
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">MV Type
                                                    <input name="mvTypeInput" id="mvTypeInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['mv_type']; ?>" readonly disabled>
                                                    <!-- Populate options dynamically based on your requirements -->            
                                                    </td>
                                                    <td class="text-end">
    Gross Weight
    <input id="grossWeightInput" type="text" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['gross_weight']; ?>" readonly disabled>
</td>
                                                   
                                                </tr>
                                                
                                                <tr>
                                                   <td class="text-end">MV File No.<input id="mvFileInput" type="text"class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['mv_file_no']; ?>" readonly disabled></td>  
                                           
                                                    <td class="text-end">Net Capacity<input id="netCapacityInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['net_capacity']; ?>" readonly disabled></td>
                                                </tr> 
                                                <tr>
                                                      <!-- <td class="text-end" style="width: 200px;">Ticketing ID<input type="text" style="margin-left: 5px;" ></td> -->
                                                      <td class="text-end">Classification
                                                    <input name="classification" id="classificationInput"  class="form-control" style="max-width: 230px; display: inline-block; margin-left: 8px;"  value="<?php echo $bookingDetails['classification']; ?>" readonly disabled>
                        <!-- Populate options dynamically based on your requirements -->
                                                   
                                                    </td>
                                                    <td class="text-end">Region
                                                    <input name="regionInput" id="regionInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['region']; ?>" readonly disabled>
                                                    <!-- Populate options dynamically based on your requirements -->
                                                   
                                                    </td>
                                                </tr>
                                           
                                                
                                                
                                              
                                              
                                                <tr>

                                              
                                               
                                               
                                                <!-- Add more rows based on your structure -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">

                        <!-- <div class="col-lg-5 col-xl-4 col-xxl-4"> -->
                        <div class="">
                       <div class="card shadow mb-4">
    <div class="card-header d-flex justify-content-between align-items-center" style="background: var(--bs-success-text-emphasis);">
        <h6 class="text-primary fw-bold m-0" style="background: Transparent;"><span style="color: rgb(244, 248, 244);">TEST DETAIL</span></h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <tbody> <!-- Wrap the table content in tbody for better structure -->
                <tr>
                        <th><span style="font-weight: normal !important;">Date</span></th>
                    </tr>   
                    <tr>
                       <td class="text-center" style="font-size: 23px;"><?php echo date('M. j, Y', strtotime( $scheduleDetails['start_datetime'])); ?></td>
                    </tr> 
                <tr>
                        <th><span style="font-weight: normal !important;">Date</span></th>
                    </tr>   
                    <tr>
                       <td class="text-center" style="font-size: 23px;"><?php echo date('g:ia', strtotime( $scheduleDetails['start_datetime'])) . ' to ' . date('g:ia', strtotime( $scheduleDetails['end_datetime'])); ?></td>
                    </tr>  
                <tr>
                        <th><span style="font-weight: normal !important;">Amount (PHP)</span></th>
                    </tr>
                    <tr>
                        <td id ="amountValue" class="text-center" style="color: ;font-size: 23px;" ><?php echo $bookingDetails['amount']; ?></td>
                    </tr>
                    <tr>
    <th><span style="font-weight: normal !important;">Mode of Payment</span></th>
</tr>
<tr>
<td class="text-center" style="font-size: 23px;"><?php echo strtoupper($bookingDetails['paymentMethod']); ?></td>
</tr>
<tr>
    <th><span style="font-weight: normal !important;">Status</span></th>
</tr>
<tr>
<td class="text-center" style="font-size: 23px; color:orange;"><?php echo strtoupper($bookingDetails['status']); ?></td>
</tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div>
</div>

                            <div class="card" style=" margin-bottom: 20px;">
    <div class="card-header" style="background: var(--bs-success-text-emphasis);">
        <h6 class="mb-0" style="text-align: center; color: var(--bs-body-bg); font-weight: bold; font-size: 16px;">ACTIONS</h6>
    </div>
    <div class="card-body" style="text-align: center; padding-top: 10px;">


<!-- Modify your existing "Submit Booking" button -->
<button type="button" class="btn btn-warning" onclick="openEditModal()">Edit Booking</button>
<button type="button" class="btn btn-danger m-2" onclick="cancelFunction()">Cancel</button>

</div>
</div>

<!-- Add this code where your other HTML content is -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirm Booking Submission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to submit the booking with the entered information?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitBooking()">Submit</button>
            </div>
        </div>
    </div>
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

<!-- Edit Booking Modal -->
<div class="modal fade" id="editBookingModal" tabindex="-1" role="dialog" aria-labelledby="editBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBookingModalLabel">Edit Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Do you want to edit this booking?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button id="editButton" type="button" class="btn btn-warning" onclick="proceedWithEdit()">Edit</button>
            </div>
        </div>
        </div>
    </div>
</div>



<div class="text-end mt-5">
    <button type="button" class="btn btn-secondary btn-lg" data-bs-toggle="modal" data-bs-target="#confirmGoBackModal">Back</button>
</div>



                      </div>
                </div>
            </div>  
        </form> 
        </div>



    <!-- Modal for displaying the picture -->
    <div class="modal fade" id="carPictureModal" tabindex="-1" aria-labelledby="carPictureModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="carPictureModalLabel">Car Picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Display the picture here -->
                    <img src="<?php echo $carPicturePath; ?>" alt="Car Picture" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

<!-- Modal for displaying the OR picture -->
<div class="modal fade" id="orPictureModal" tabindex="-1" aria-labelledby="orPictureModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orPictureModalLabel">OR Picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Display the OR picture here -->
                <img id="orPictureDisplay" class="img-fluid" alt="OR Picture">
            </div>
        </div>
    </div>
</div>


<!-- Modal for displaying the CR picture -->
<div class="modal fade" id="crPictureModal" tabindex="-1" aria-labelledby="crPictureModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="crPictureModalLabel">CR Picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Display the CR picture here -->
                <img id="crPictureDisplay" class="img-fluid" alt="CR Picture">
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
function displaySelectedPicture() {
    // Show the modal when the button is clicked
    $('#carPictureModal').modal('show');
}
</script>



<script>
    function navigateBack() {
        // Redirect to home.php
        window.location.href = 'schedule-status.php';
    }
</script>

</body>

</html>
