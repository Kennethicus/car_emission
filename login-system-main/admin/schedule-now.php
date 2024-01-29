<!-- details.php  -->
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






$detailsQuery = "SELECT `user_id`, `first_name`, `middle_name`, `last_name` FROM login";
$detailsResult = $connect->query($detailsQuery);

$loginDetails = array();

if ($detailsResult->num_rows > 0) {
    // Fetch the details and convert them to an array
    while ($row = $detailsResult->fetch_assoc()) {
        $loginDetails[] = $row;
    }
}


// Close the database connection
$connect->close();
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
    
   <!-- Include Select2 CSS -->
   <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.0/css/select2.min.css" rel="stylesheet" />

<!-- Include Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.0/js/select2.min.js"></script>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include 'partials/nav.php'?>
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <?php include 'partials/nav-2.php' ?>
                <!-- Display the details content here -->
                <div class="container mt-3">  
                    <form action="submit_book_by_admin.php" method="POST"  name="bookingForm">
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
                                          
                                            <tr>
                                            <td  class="text-end" style="width: 200px;">
  
    <input id="userId" type="hidden" style="margin-left: 5px;" readonly>
</td>
<td class="text-end" style="width: 200px;">
 
    <input id="adminId" type="hidden" class="form-control form-group" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text" value="<?php echo $admin['id']; ?>" readonly>
</td>
                                        </tr>
                                            <tr>
                                            <!-- <td  class="text-end">App Date<input type="text" style="margin-left: 5px;" ></td> -->
   

                                            <td class="text-end">
    <label for="userNameInput">Select a user:</label>
    <input type="text" id="userNameInput" name="userNameInput" list="userSuggestions"class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;">
    <datalist id="userSuggestions">
        <option value="None">None</option>
        <?php foreach ($loginDetails as $loginEntry): ?>
            <option value="<?php echo $loginEntry['first_name'] . ' ' . $loginEntry['middle_name'] . ' ' . $loginEntry['last_name']; ?>" data-userid="<?php echo $loginEntry['user_id']; ?>">
        <?php endforeach; ?>
    </datalist>
</td>
                                                    <td class="text-end" style="width: 200px;">Organization<input id="organizationInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text"></td>
                                                </tr>
                                               <tr>

                                               <td class="text-end" style="width: 200px;">
                                                Plate No. 
                                                <input id="plateNumberLetters" class="form-control" style="max-width: 100px; display: inline-block;" type="text" placeholder="ex. ABCD">
                                                <input id="plateNumberNumbers" class="form-control" style="max-width: 100px; display: inline-block; margin-left: 8px;" type="text" placeholder="ex. 1234">
                                                 </td>

           
                                               
                                            
                                                    <td class="text-end">First Name<input id="firstnameInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text"></td>
                                                </tr> 
                                                <tr>

                                                <td class="text-end">
    <!-- Input for uploading a picture -->
    <label for="carPictureInput" class="btn btn-primary">
        Vehicle Picture
        <input type="file" id="carPictureInput" class="d-none" accept="image/*" onchange="displaySelectedPicture(this)">
    </label>

    <!-- Button to display the selected picture -->
    <button type="button" class="btn btn-success" onclick="displaySelectedPicture()" id="selectedPictureFilename">Display</button>  
</td>
                                                        <td class="text-end" style="width: 200px;">Middle Name<input id="middleNameInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text"></td>
                                                </tr>
                                            
                                                <tr>
                                            
                                                <td class="text-end">
    <!-- Input for uploading a picture -->
    <label for="CrInputPicture" class="btn btn-primary">
       OR Picture
        <input type="file" id="CrInputPicture" class="d-none" accept="image/*" onchange="displaySelectedCrPicture(this)">
    </label>

    <!-- Button to display the selected picture -->
    <button type="button" class="btn btn-success" onclick="displaySelectedCrPicture()" id="selectedCrFilename">Display</button>  
</td>


                                                
                                                    <td class="text-end" style="width: 200px;">Last Name<input id="lastNameInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text"></td>
                                                </tr>
                                                <tr>
                                                <td class="text-end">
    <!-- Input for uploading a picture -->
    <label for="OrInputPicture" class="btn btn-primary">
       CR Picture
        <input type="file" id="OrInputPicture" class="d-none" accept="image/*" onchange="displaySelectedOrPicture(this)">
    </label>

    <!-- Button to display the selected picture -->
    <button type="button" class="btn btn-success" onclick="displaySelectedOrPicture(this)" id="selectedOrFilename">Display</button>  
</td>
<td class="text-end" style="width: 200px;">Address<input id="addressInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text"></td>
                                                                        
                                         

                                                </tr>   
                                                <tr>

                                                  
                                                </tr>  
                                   
                                                <tr>
                                                <td class="text-end" style="vertical-align: middle;">Vehicle CR No.<input id="vehicleCrInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text"></td>


                                                    <td class="text-end">Engine<input id="engineInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text"></td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: right;">Vehicle OR No.<input id="vehicleOrInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text" ></td>
                                                    <td style="text-align: right;">Chassis<input type="text" id="chassisInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text"></td>
                                                </tr>
                                                <tr>
                                                <td class="text-end">First Registration Date
                                                <input id="firstRegInput" type="date" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;">
    </td>
                                                    <td class="text-end">Make
                                                    <select name="makeInput" id="makeInput"  class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" >
                                                    <option value="" selected>Select Make</option>
                                                    </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">Year Model
                                                    <select id="yearModelInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;"> <!-- Adjust the width as needed -->
                                                    <option value="" selected>Select Year</option> 
                                                    
                                                    <?php
                                                    // Assuming $startYear and $endYear are your desired range
                                                    $startYear = date("Y") - 20; // Display 20 recent years
                                                    $endYear = date("Y"); // Current year

                                                    // Loop to generate options
                                                    for ($year = $endYear; $year >= $startYear; $year--) {
                                                        echo '<option value="' . $year . '">' . $year . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                                    </td>
                                                    <td class="text-end">Series
                                                    <select name="seriesInput" id="seriesInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;">
                                                    <option value="" selected>Select Series</option>
                                                </select>    
                                                </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">Fuel type
                                                    <select name="fuelTypeInput" id="fuelTypeInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;">
                                                    <option value="" selected>Select Fuel</option>
                    </select>
                                                    </td>
                                                    <td class="text-end">Color
                                                    <select name="colorInput" id="colorInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;">
                                                    <option value="" selected>Select Color</option>
                                                    </select>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">Purpose
                                                    <select name="purposeInput" id="purposeInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;">
                                                    <option value="" selected>Select Purpose</option>
                                                    </select>
                                                    </td>
                                                  <td class="text-end">
    Gross Weight
    <input id="grossWeightInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text">
</td>

                                                </tr>
                                                <tr>
                                                    <td class="text-end">MV Type
                                                    <select name="mvTypeInput" id="mvTypeInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;">
                                                    <option value="" selected>Select MV Type</option>
                                                    </select>
                                                    </td>
                                                    <td class="text-end">Net Capacity<input id="netCapacityInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text"></td>
                                                </tr>
                                                <tr>
     
     <td class="text-end">MV File No.<input id="mvFileInput" type="text"class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" ></td>  
     <td class="text-end">Region
                                                    <select name="regionInput" id="regionInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;">
                                                    <option value="" selected>Select Region</option>
                                                    </select>
                                                    </td>
         </tr>
                                                <tr>
                                                 
         
                                                </tr>
                                                
                                                
                                              
                                              
                                                <tr>

                                                 <!-- <td class="text-end" style="width: 200px;">Ticketing ID<input type="text" style="margin-left: 5px;" ></td> -->
                                                 <td class="text-end">Classification
                                                    <select name="classification" id="classificationInput"  class="form-control" style="max-width: 230px; display: inline-block; margin-left: 8px;" type="text">
                                                    <option value="" selected>Select Classification</option>
                                                    </select>
                                                    </td>
                                                </tr>
                                               
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
                                                    
                <!-- <tr>
    <th><span style="font-weight: normal !important;">Mode of Payment</span></th>
</tr>
<tr>
    <td>
        <select class="form-select" id="paymentMode" name="paymentMode">
            <option value="cash">Cash</option>
            <option value="paymaya">PayMaya</option>
        
        </select>
    </td>
</tr> -->
                    <!-- <tr>
    <th><span style="font-weight: normal !important;">Payment Status</span></th>
</tr>
<tr>
    <td>
        <select class="form-select" id="paymentStatus" name="paymentStatus">
            <option value="unpaid">Unpaid</option>
            <option value="paid">Paid</option>
        </select>
    </td>
</tr> -->

                    <tr>
                        <th><span style="font-weight: normal !important;">Amount (PHP)</span></th>
                        
                    </tr>
                    <tr>
                        <td class="text-center" >
                        <input id="amountValue" type="text" class="form-control" value="" readonly></td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="card shadow mb-4">
    <div class="card-header d-flex justify-content-between align-items-center" style="background: var(--bs-success-text-emphasis);">
        <h6 class="text-primary fw-bold m-0" style="background: Transparent;"><span style="color: rgb(244, 248, 244);">CHOOSE DATE</span></h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <tbody>
                    <!-- Wrap the table content in tbody for better structure -->
                    <tr>
                        <td >
                            <input type="date" class="form-control" id="datePickerAdmin" placeholder="date" />
                        </td>
                    </tr>
                    <tr>
                        <td id="bookdetails" >
                            <input type="hidden" class="form-control" id="sched_id" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td id="start_date" >
                            <input type="text" class="form-control" id="start_date_input" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td id="end_date" >
                            <input type="text" class="form-control" id="end_date_input" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td >
                            <input type="hidden" class="form-control" id="price1" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td >
                            <input type="hidden" class="form-control" id="price2" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td >
                            <input type="hidden" class="form-control" id="price3" readonly>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

                            <div class="card" style=" margin-bottom: 20px;">
    <div class="card-header" style="background: var(--bs-success-text-emphasis);">
        <h6 class="mb-0" style="text-align: center; color: var(--bs-body-bg); font-weight: bold; font-size: 16px;">ACTIONS</h6>
    </div>
    <div class="card-body" style="text-align: center; padding-top: 10px;">


<!-- Modify your existing "Submit Booking" button -->
<button type="button" class="btn btn-primary" onclick="openConfirmationModal()">Submit Booking</button>    
<button class="btn btn-danger m-2" onclick="cancelBookFunction()">Cancel</button>

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


<!-- Back button outside the card, positioned lower and larger with increased margin-top -->
<div class="text-end mt-5">
    <button class="btn btn-secondary btn-lg" onclick="goBack()">Back</button>
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
                    <img id="carPictureDisplay" class="img-fluid" alt="Car Picture">
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

                      </div>
                </div>
            </div>  
        </form> 
        </div>
        <?php include 'partials/footer.php'?>
        <a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>




       <!-- Modal -->
       <div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="scheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scheduleModalLabel">Schedule Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="events-container">
                        <!-- Schedule details will be displayed here -->
                    </div>
                </div>
                <div class="modal-footer">
                 
                   <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>

    
<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1"  aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Success</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
       
        </button>
      </div>
      <div class="modal-body">
        Data sent successfully!
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Error</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
       
      </div>
      <div class="modal-body">
        Error sending data to the server! Check the console for details.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


    <script>
function cancelBookFunction() {
    // Display a confirmation dialog
    var confirmed = window.confirm("Are you sure you want to cancel? Any unsaved changes will be lost.");

    // Check if the user confirmed
    if (confirmed) {
        // Reload the page
        location.reload();
    }
    // If the user cancels, do nothing
}
</script>
<!-- Add this JavaScript code to your file -->
<script>
    function openConfirmationModal() {
        // Prevent the default form submission behavior
        event.preventDefault();

        // Check if the required fields are not empty
        if (validateForm()) {
            $('#confirmationModal').modal('show');
        } else {
            // Highlight the empty required fields (excluding the date field)
            highlightEmptyFields();
        }
    }


    function validateVehicleInformation() {
        // Validate the Gross Weight and Net Capacity fields
        var grossWeightInput = $('#grossWeightInput').val();
        var netCapacityInput = $('#netCapacityInput').val();
        var chassisInput = $('#chassisInput').val();
        var engineInput = $('#engineInput').val();
        var addressInput = $('#addressInput').val();
        var firstnameInput = $('#firstnameInput').val();
        var middleNameInput = $('#middleNameInput').val();
        var organizationInput = $('#organizationInput').val();
        var userNameInput = $('#userNameInput').val();
        var plateNumberLetter = $('#plateNumberLetters').val();
        var plateNumberNumbers = $('#plateNumberNumbers').val();
        var vehicleCrInput = $('#vehicleCrInput').val();
        var vehicleOrInput = $('#vehicleOrInput').val();
        var firstRegInput =  $('#firstRegInput').val();
        var mvFileInput = $('#mvFileInput').val();
        var lastNameInput = $('#lastNameInput').val();
        // Check if both Gross Weight and Net Capacity are not empty
        var carPictureInput = $('#carPictureInput').val();
        var CrInputPicture = $('#CrInputPicture').val();
        var OrInputPicture = $('#OrInputPicture').val();
        var yearModelInput = $('#yearModelInput').val();
        var makeInput =  $('#makeInput').val();
        var mvType =  $('#mvTypeInput').val();
        var fuelType =  $('#fuelTypeInput').val();
        var purposeInput =  $('#purposeInput').val();
        var seriesInput =  $('#seriesInput').val();
        var colorInput =  $('#colorInput').val();
        var regionInput =  $('#regionInput').val();
        var classificationInput =  $('#classificationInput').val();

           return grossWeightInput !== '' && netCapacityInput !== '' && chassisInput !== '' && engineInput !== '' && addressInput !== '' && firstnameInput !== ''  && organizationInput !== '' && userNameInput !== '' && vehicleCrInput !== '' && vehicleOrInput !== '' && firstRegInput !== '' && mvFileInput !== ''  && middleNameInput !== '' && lastNameInput !== '' && plateNumberLetter !== '' && plateNumberNumbers !== '' && carPictureInput !== '' && CrInputPicture !== '' && OrInputPicture !== '' && yearModelInput !== '' && makeInput !== '' && fuelType !== '' && purposeInput !== ''  && seriesInput !== '' && colorInput !== '' && regionInput !== '' && classificationInput !== '' && mvType !== '';
    }


    function highlightmvTypeInput() {
        // Remove existing red border from Gross Weight field
        $('#mvTypeInput').removeClass('border-danger');
    }


    function highlightClassificationInput() {
        // Remove existing red border from Gross Weight field
        $('#classificationInput').removeClass('border-danger');
    }

    function highlightRegionInput() {
        // Remove existing red border from Gross Weight field
        $('#regionInput').removeClass('border-danger');
    }
    
    function highlightColorInput() {
        // Remove existing red border from Gross Weight field
        $('#colorInput').removeClass('border-danger');
    }

    function highlightSeriesInput() {
        // Remove existing red border from Gross Weight field
        $('#seriesInput').removeClass('border-danger');
    }


    function highlightPurposeInput() {
        // Remove existing red border from Gross Weight field
        $('#purposeInput').removeClass('border-danger');
    }


    function highlightFuelType() {
        // Remove existing red border from Gross Weight field
        $('#fuelTypeInput').removeClass('border-danger');
    }

    function highlightMake() {
        // Remove existing red border from Gross Weight field
        $('#makeInput').removeClass('border-danger');
    }

    function yearModelInput() {
        // Remove existing red border from Gross Weight field
        $('#yearModelInput').removeClass('border-danger');
    }

    function carPictureInput() {
        // Remove existing red border from Gross Weight field
        $('#carPictureInput').removeClass('border-danger');
    }
    function CrInputPicture() {
        // Remove existing red border from Gross Weight field
        $('#CrInputPicture').removeClass('border-danger');
    }
    function OrInputPicture() {
        // Remove existing red border from Gross Weight field
        $('#OrInputPicture').removeClass('border-danger');
    }




    function highlightGrossWeight() {
        // Remove existing red border from Gross Weight field
        $('#grossWeightInput').removeClass('border-danger');
    }

    function highlightNetCapacity() {
        // Remove existing red border from Net Capacity field
        $('#netCapacityInput').removeClass('border-danger');
    }

    function highlightchassisInput() {
        // Remove existing red border from Net Capacity field
        $('#chassisInput').removeClass('border-danger');
    }

    function highlightengineInput() {
        // Remove existing red border from Net Capacity field
        $('#engineInput').removeClass('border-danger');
    }
    function highlightaddressInput() {
        // Remove existing red border from Net Capacity field
        $('#addressInput').removeClass('border-danger');
    }
    function highlightfirstnameInput() {
        // Remove existing red border from Net Capacity field
        $('#firstnameInput').removeClass('border-danger');
    }
    function highlightorganizationInput() {
        // Remove existing red border from Net Capacity field
        $('#organizationInput').removeClass('border-danger');
    }
    function highlightusernameInput() {
        // Remove existing red border from Net Capacity field
        $('#userNameInput').removeClass('border-danger');
    }

    function highlightvehiclecrInput() {
        // Remove existing red border from Net Capacity field
        $('#vehicleCrInput').removeClass('border-danger');
    }

    function highlightvehicleorInput() {
        // Remove existing red border from Net Capacity field
        $('#vehicleOrInput').removeClass('border-danger');
    }
    
    function highlightfirstregInput() {
        // Remove existing red border from Net Capacity field
        $('#firstRegInput').removeClass('border-danger');
    }

    function highlightmvfileInput() {
        // Remove existing red border from Net Capacity field
        $('#mvFileInput').removeClass('border-danger');
    }

    function highlightmiddlenameInput() {
        // Remove existing red border from Net Capacity field
        $('#middleNameInput').removeClass('border-danger');
    }

    function highlightlastnameInput() {
        // Remove existing red border from Net Capacity field
        $('#lastNameInput').removeClass('border-danger');
    }

    function highlightplatenumberLetters() {
        // Remove existing red border from Net Capacity field
        $('#plateNumberLetters').removeClass('border-danger');
    }

    function highlightplatenumbers() {
        // Remove existing red border from Net Capacity field
        $('#plateNumberNumbers').removeClass('border-danger');
    }



// Add event listener to the file input field for change event
$('#mvTypeInput').on('change', function () {
    // Call the function to handle the selected picture
    highlightmvTypeInput();
});

    // Add event listener to the file input field for change event
$('#carPictureInput').on('change', function () {
    // Call the function to handle the selected picture
    displaySelectedPicture(this);
});

// Add event listener to the file input field for change event
$('#CrInputPicture').on('change', function () {
    // Call the function to handle the selected picture
    displaySelectedCrPicture(this);
});

// Add event listener to the file input field for change event
$('#OrInputPicture').on('change', function () {
    // Call the function to handle the selected picture
    displaySelectedOrPicture(this);
});

 // Add event listeners to the input fields for the input event
 $('#yearModelInput').on('change', function () {
        // Remove the highlight from Gross Weight field when the user starts typing
        yearModelInput();
    });

     // Add event listeners to the input fields for the input event
 $('#makeInput').on('change', function () {
        // Remove the highlight from Gross Weight field when the user starts typing
        highlightMake();
    });

     // Add event listeners to the input fields for the input event
     $('#fuelTypeInput').on('change', function () {
        // Remove the highlight from Gross Weight field when the user starts typing
        highlightFuelType();
    });

     // Add event listeners to the input fields for the input event
     $('#purposeInput').on('change', function () {
        // Remove the highlight from Gross Weight field when the user starts typing
        highlightPurposeInput();
    });

   // Add event listeners to the input fields for the input event
     $('#seriesInput').on('change', function () {
        // Remove the highlight from Gross Weight field when the user starts typing
        highlightSeriesInput();
    });

     // Add event listeners to the input fields for the input event
     $('#colorInput').on('change', function () {
        // Remove the highlight from Gross Weight field when the user starts typing
        highlightColorInput();
    });

     // Add event listeners to the input fields for the input event
     $('#regionInput').on('change', function () {
        // Remove the highlight from Gross Weight field when the user starts typing
        highlightRegionInput();
    });

         // Add event listeners to the input fields for the input event
         $('#classificationInput').on('change', function () {
        // Remove the highlight from Gross Weight field when the user starts typing
        highlightClassificationInput();
    })



 // Add event listeners to the input fields for the input event
 $('#grossWeightInput').on('input', function () {
        // Remove the highlight from Gross Weight field when the user starts typing
        highlightGrossWeight();
    });

    $('#netCapacityInput').on('input', function () {
        // Remove the highlight from Net Capacity field when the user starts typing
        highlightNetCapacity();
    });

    
    $('#chassisInput').on('input', function () {
        // Remove the highlight from Net Capacity field when the user starts typing
        highlightchassisInput();
    });

    $('#engineInput').on('input', function () {
        // Remove the highlight from Net Capacity field when the user starts typing
        highlightengineInput();
    });

    $('#addressInput').on('input', function () {
        // Remove the highlight from Net Capacity field when the user starts typing
        highlightaddressInput();
    });
    $('#firstnameInput').on('input', function () {
        // Remove the highlight from Net Capacity field when the user starts typing
        highlightfirstnameInput();
    });
    $('#organizationInput').on('input', function () {
        // Remove the highlight from Net Capacity field when the user starts typing
        highlightorganizationInput();
    });
    $('#userNameInput').on('input', function () {
        // Remove the highlight from Net Capacity field when the user starts typing
        highlightusernameInput();
    });

    $('#vehicleCrInput').on('input', function () {
        // Remove the highlight from Net Capacity field when the user starts typing
        highlightvehiclecrInput();
    });

    $('#vehicleOrInput').on('input', function () {
        // Remove the highlight from Net Capacity field when the user starts typing
        highlightvehicleorInput();
    });

    $('#firstRegInput').on('input', function () {
        // Remove the highlight from Net Capacity field when the user starts typing
        highlightfirstregInput();
    });


    $('#mvFileInput').on('input', function () {
        // Remove the highlight from Net Capacity field when the user starts typing
        highlightmvfileInput();
    });

    $('#middleNameInput').on('input', function () {
        // Remove the highlight from Net Capacity field when the user starts typing
        highlightmiddlenameInput();
    });

    $('#lastNameInput').on('input', function () {
        // Remove the highlight from Net Capacity field when the user starts typing
        highlightlastnameInput();
    });

    $('#plateNumberLetters').on('input', function () {
        // Remove the highlight from Net Capacity field when the user starts typing
        highlightplatenumberLetters();
    });

    $('#plateNumberNumbers').on('input', function () {
        // Remove the highlight from Net Capacity field when the user starts typing
        highlightplatenumbers();
    });

    function validateForm() {
        // Validate each required input field (excluding the date field)
        var schedIdValue = $('#sched_id').val();
        var startDateValue = $('#start_date_input').val();
        var endDateValue = $('#end_date_input').val();

        // Check if any of the required fields is empty
        return validateVehicleInformation() && schedIdValue && startDateValue && endDateValue;
    }

    function highlightEmptyFields() {
        // Remove existing red borders
        $('.form-control').removeClass('border-danger');

        // Highlight the empty required fields (excluding the date field) with red border
        $('.form-control').each(function () {
            var isDateField = $(this).attr('id') === 'datePickerAdmin';
            var isEmpty = $(this).val() === '';

            if (!isDateField && isEmpty) {
                $(this).addClass('border-danger');
            }
        });
        
        function highlightVehicleInformation() {
        // Highlight the fields with red border if they are empty
        if (!validateVehicleInformation()) {
            $('#mvTypeInput').addClass('border-danger');
            $('#classificationInput').addClass('border-danger');
            $('#regionInput').addClass('border-danger');
            $('#colorInput').addClass('border-danger');
            $('#seriesInput').addClass('border-danger');
            $('#purposeInput').addClass('border-danger');
            $('#fuelTypeInput').addClass('border-danger');
            $('#yearModelInput').addClass('border-danger');
            $('#makeInput').addClass('border-danger');
            $('#carPictureInput').addClass('border-danger');
              $('#OrInputPicture').addClass('border-danger');
              $('#CrInputPicture').addClass('border-danger');
            $('#grossWeightInput').addClass('border-danger');
            $('#netCapacityInput').addClass('border-danger');
            $('#engineInput').addClass('border-danger');
            $('#addressInput').addClass('border-danger');
            $('#vehicleOwnerInput').addClass('border-danger');
            $('#organizationInput').addClass('border-danger');
            $('#userNameInput').addClass('border-danger');
          
            $('#vehicleCrInput').addClass('border-danger');  
            $('#firstnameInput').addClass('border-danger');  
            $('#firstRegInput').addClass('border-danger');  
            $('#mvFileInput').addClass('border-danger');  
            $('#middleNameInput').addClass('border-danger');  
            $('#lastNameInput').addClass('border-danger');  
            $('#plateNumberLetters').addClass('border-danger'); 
            $('#plateNumberNumbers').addClass('border-danger');  
        }
    }
    }

    function submitBooking() {
    // Prevent the default form submission behavior
    event.preventDefault();

    // Now submit the form programmatically if the form is valid
    if (validateForm()) {
        // Collect form data
        var formData = new FormData();
        formData.append("userId", $("#userId").val());
        formData.append("adminId", $("#adminId").val());
        formData.append("schedId", $("#sched_id").val());
        formData.append("firstName", $("#firstnameInput").val());
        formData.append("middleName", $("#middleNameInput").val());
        formData.append("lastName", $("#lastNameInput").val());
        formData.append("address", $("#addressInput").val());
        formData.append("vehicleCrNo", $("#vehicleCrInput").val());
        formData.append("vehicleOrNo", $("#vehicleOrInput").val());
        formData.append("firstReg", $("#firstRegInput").val());
        formData.append("yearModel", $("#yearModelInput").val());
        formData.append("fuelType", $("#fuelTypeInput").val());
        formData.append("purpose", $("#purposeInput").val());
        formData.append("mvType", $("#mvTypeInput").val());
        formData.append("region", $("#regionInput").val());
        formData.append("mvFile", $("#mvFileInput").val());
        formData.append("classification", $("#classificationInput").val());
        formData.append("amount", $("#amountValue").val());
        formData.append("organization", $("#organizationInput").val());
        formData.append("engine", $("#engineInput").val());
        formData.append("chassis", $("#chassisInput").val());
        formData.append("make", $("#makeInput").val());
        formData.append("series", $("#seriesInput").val());
        formData.append("color", $("#colorInput").val());
        formData.append("grossWeight", $("#grossWeightInput").val());
        formData.append("netCapacity", $("#netCapacityInput").val());
        formData.append("carPicture", $("#carPictureInput")[0].files[0]);
        formData.append("OrInputPicture", $("#OrInputPicture")[0].files[0]);
        formData.append("CrInputPicture", $("#CrInputPicture")[0].files[0]);
        formData.append("paymentMethod", $("#paymentMode").val());
        // formData.append("paymentMethod", $("#paymentMode").val());
        // formData.append("paymentStatus", $("#paymentStatus").val());
        formData.append("plateNumberLetters", $("#plateNumberLetters").val());
        formData.append("plateNumberNumbers", $("#plateNumberNumbers").val());
        // Add more fields as needed

        $.ajax({
    type: "POST",
    url: "submit_book_by_admin.php", // Replace with your server-side script URL
    data: formData,
    contentType: false,
    processData: false,
    dataType: "json", // Change this based on your server response type
    success: function (response) {
        // Handle the server response here
        console.log(response);

        if (response.status === 'error') {
            // Show error modal
            $('#errorModal .modal-body').text(response.message);
            $('#errorModal').modal('show');
        } else {
            // Show success modal
            $('#successModal').modal('show');
        }
    },
    error: function (xhr, status, error) {
        // Handle errors
        console.error("XHR status:", status);
        console.error("XHR error:", error);
        console.error("XHR response:", xhr.responseText);
        
        // Show error modal
        $('#errorModal .modal-body').text("Error sending data to the server! Check the console for details.");
        $('#errorModal').modal('show');
    }
});

    } else {
        // Highlight the empty required fields
        highlightEmptyFields();
    }
}


</script>





    <script>
    // JavaScript function to navigate back to reservation.php
    function goBack() {
        window.location.href = 'reservation.php';
    }
</script>
<!-- Add this script -->
<script>
    // Wait for the document to be fully loaded
    $(document).ready(function () {
        // When the close button in the schedule modal is clicked
        $('#scheduleModal .close, #scheduleModal .btn-secondary').on('click', function () {
            // Manually close the modal
            $('#scheduleModal').modal('hide');
        });
    });
</script>


<!-- Include Popper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<!-- Include Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<!-- Include Bootstrap Datepicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<!-- Include Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.0/js/select2.min.js"></script>

    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/chart.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/theme.js"></script>
<!-- Add this script at the end of your HTML, after including jQuery and Bootstrap JS -->




<script>
        // Function to display the selected picture filename
        function displaySelectedPicture() {
            const input = document.getElementById('carPictureInput');
            const selectedFilename = input.files[0] ? input.files[0].name : 'No file selected';
            document.getElementById('selectedPictureFilename').innerText = selectedFilename;

            // Optionally, you can also display the picture in the modal
            if (input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('carPictureDisplay').src = e.target.result;
                    $('#carPictureModal').modal('show'); // Show the modal
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
<script>
    // Function to display the selected OR picture filename
    function displaySelectedOrPicture() {
    // Check if input.files is defined and has length
   
        const input = document.getElementById('OrInputPicture');
        const selectedFilename =  input.files[0] ? input.files[0].name : 'No file selected';
        document.getElementById('selectedOrFilename').innerText = selectedFilename;

        // Display the OR picture in the modal
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('orPictureDisplay').src = e.target.result;
            $('#orPictureModal').modal('show'); // Show the OR picture modal using jQuery
        };
        reader.readAsDataURL(input.files[0]);
    
}

</script>

<script>
    // Function to display the selected CR picture filename
    function displaySelectedCrPicture() {
        const input = document.getElementById('CrInputPicture');
        const selectedFilename = input.files[0] ? input.files[0].name : 'No file selected';
        document.getElementById('selectedCrFilename').innerText = selectedFilename;

        // Optionally, you can also display the picture in the modal
       
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('crPictureDisplay').src = e.target.result;
                $('#crPictureModal').modal('show'); // Show the modal using jQuery
           
        };
        reader.readAsDataURL(input.files[0]);
    } 
</script>
 
<script>
  // Function to format date

  var mvTypeSelect = document.getElementById('mvTypeInput');

// Adding an event listener for the change event
mvTypeSelect.addEventListener('change', function() {
    updateAmountBasedOnMVType();
    highlightEmptyFields();
});

// Function to update amount based on the selected MV type
function updateAmountBasedOnMVType() {
    var price_1 = parseFloat(document.getElementById('price1').value) || 0;
    var price_2 = parseFloat(document.getElementById('price2').value) || 0;
    var price_3 = parseFloat(document.getElementById('price3').value) || 0;
    
    var mvType = document.getElementById('mvTypeInput').value;
    var amountInput = document.getElementById('amountValue');

// Set the amount based on MV type using if-else conditions
if (mvType === 'Tricycle' || mvType === 'Mopeds (0-49 cc)' || mvType === 'Motorcycle w/ side car' || mvType === 'Motorcycle w/o side car' || mvType === 'Non-conventional MC (Car)') {
    amountInput.value = price_1;
} else if (mvType === 'Car' || mvType === 'School bus' || mvType === 'Sports Utility Vehicle' || mvType === 'Utility Vehicle' || mvType === 'Rebuilt' || mvType === 'Mobil Clinic') {
        amountInput.value = price_2;
}else if (mvType === 'Trucks' || mvType === 'School bus' || mvType === 'Shuttle Bus' || mvType === 'Tourist Bus' || mvType === 'Truck Bus' || mvType === 'Trailer') {
        amountInput.value = price_3;
} else {
    amountInput.value = '';
}
}
// Call the function initially to set the initial amount
updateAmountBasedOnMVType();




  function formatDate(dateTimeString) {
        var options = { hour: 'numeric', minute: 'numeric', hour12: true };
        var formattedDate = new Date(dateTimeString).toLocaleTimeString('en-US', options);
        return formattedDate;
    }

    
// Function to update book details
function updateBookDetails(eventId, start, end, price1, price2, price3) {
    // Replace this line with your logic to fetch and display the details based on the eventId
    var eventDetails = eventId;
    var start_date = formatDate(start);
    var end_date = formatDate(end);
    var price_1 = price1;
    var price_2 = price2;
    var price_3 = price3;
    // Update the value of the input fields
    document.getElementById('start_date_input').value = start_date;
    document.getElementById('end_date_input').value = end_date;
    document.getElementById('sched_id').value = eventDetails;
    document.getElementById('price1').value = price_1;
    document.getElementById('price2').value = price_2;
    document.getElementById('price3').value = price_3;
    updateAmountBasedOnMVType();
    highlightEmptyFields();
}



        // Function to display the selected picture filename
        function displaySelectedPicture() {
            const input = document.getElementById('carPictureInput');
            const selectedFilename = input.files[0] ? input.files[0].name : 'No file selected';
            document.getElementById('selectedPictureFilename').innerText = selectedFilename;

            // Optionally, you can also display the picture in the modal
            if (input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('carPictureDisplay').src = e.target.result;
                    $('#carPictureModal').modal('show'); // Show the modal
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            var datePicker = document.getElementById('datePickerAdmin');

            // Set the min attribute to the current date
            datePicker.min = new Date().toISOString().split('T')[0];

            datePicker.addEventListener('change', function () {
                var selectedDate = datePicker.value;
                updateAmountBasedOnMVType();
                // Replace the following URL with your actual endpoint
                $.ajax({
                    url: '../get_customer_schedule.php',
                    type: 'GET',
                    data: { date: selectedDate },
                    dataType: 'json',
                    success: function (response) {
                        response.sort(function (a, b) {
                            return new Date(a.start) - new Date(b.start);
                        });

                        var eventsContainer = $('#events-container');
                        eventsContainer.empty();

                        if (response.length > 0) {
                            for (var i = 0; i < response.length; i++) {
                                var eventDetails = '<div class="row mb-3">';
                                eventDetails += '<div class="col-md-4">';
                                eventDetails += '<p><strong>TIME:</strong> ' + formatDate(response[i].start) + ' - ' + formatDate(response[i].end) + '</p>';
                                eventDetails += '</div>';
                                eventDetails += '<div class="col-md-4">';
                                eventDetails += '<p><strong>SLOT:</strong> '+ response[i].reserve_count +' of '+ response[i].qty_of_person + '</p>';
                                eventDetails += '<p><strong>MV III:</strong> ' + "₱" + response[i].price_3 + '</p>';
                                eventDetails += '<p><strong>MV II:</strong> ' + "₱" + response[i].price_2 + '</p>';
                                eventDetails += '<p><strong>MV I:</strong> ' + "₱" + response[i].price_1 + '</p>';
                                // eventDetails += '<p><strong>STATUS:</strong> ' + response[i].availability + '</p>';
                                eventDetails += '</div>';
                                eventDetails += '<div class="col-md-4 text-end">';

                                if (response[i].reserve_count === response[i].qty_of_person) {
                                    eventDetails += '<button class="btn btn-secondary" disabled>Not Available</button>';
                                } else {
                                  // Change this line in your loop where you create the button
                                  eventDetails += '<button class="btn btn-primary" onclick="updateBookDetails(' + response[i].id + ', \'' + response[i].start + '\', \'' + response[i].end + '\', ' + response[i].price_1 + ', ' + response[i].price_2 + ', ' + response[i].price_3 + ')">Book Now</button>';

                                }

                                eventDetails += '</div>';
                                eventDetails += '</div>';
                                eventDetails += '<hr>';

                                eventsContainer.append(eventDetails);
                            }
                        } else {
                            eventsContainer.html('<p>No events on the selected date</p>');
                                                
                            // Reset the input fields to zero or empty when no events are available
                            document.getElementById('sched_id').value = '0';
                            document.getElementById('start_date_input').value = '';
                            document.getElementById('end_date_input').value = '';
                            document.getElementById('price1').value = '';
                            document.getElementById('price2').value = '';
                            document.getElementById('price3').value = '';
                            document.getElementById('amountValue').value = '';
                            
                            // Display the modal with the schedule details
                            $('#scheduleModal').modal('show');
                        }

                        // Display the modal with the schedule details
                        $('#scheduleModal').modal('show');
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX Error: ' + status, error);
                        alert('Error fetching schedule data. Check the console for details.');
                    }
                });
            });

            function formatDate(dateTimeString) {
                var options = { hour: 'numeric', minute: 'numeric', hour12: true };
                var formattedDate = new Date(dateTimeString).toLocaleTimeString('en-US', options);
                return formattedDate;
            }
        });
    </script>

<script>
        document.addEventListener('DOMContentLoaded', function () {
        


            var classifications = ['Diplomatic-Consular Corps', 'Diplomatic-Chief of Mission', 'Diplomatic-Diplomatic Corps', 'Exempt-Government', 'Diplomatic Exempt-Economics Z', 'Government', 'For hire', 'Diplomatic-OEV', 'Private', 'Exempt-For-Hire', 'Exempt-Private'];
            var classificationSelect = document.getElementById('classificationInput');
            classifications.forEach(function (classification) {
            var option = document.createElement('option');
            option.value = classification; //.toLowerCase()
            option.text = classification;
            classificationSelect.add(option);
        });
      
      

        var makes = ['Toyota', 'Honda', 'Mitsubishi', 'Ford', 'Chevrolet', 'Kia', 'Hyundai', 'MG', 'Geely', 'Chery'];
        var makeSelect = document.getElementById('makeInput');
         makes.forEach(function (make) {
            var option = document.createElement('option');
            option.value = make; //.toLowerCase()
            option.text = make;
            makeSelect.add(option);
        });


        var fuelTypes = ['Gasoline', 'LPG', 'Diesel - None Turbo', 'Turbo Diesel'];
        var fuelTypeSelect = document.getElementById('fuelTypeInput');
          fuelTypes.forEach(function (fuelType) {
            var option = document.createElement('option');
            option.value = fuelType; //.toLowerCase() You can set the value based on your requirements
            option.text = fuelType;
            fuelTypeSelect.add(option);
        });
      
      
        var purposes = ['For Registration', 'For Compliance', 'Plate Redemption']; 
        var purposeSelect = document.getElementById('purposeInput');
        purposes.forEach(function (purpose) {
            var option = document.createElement('option');
            option.value = purpose; //.toLowerCase() You can set the value based on your requirements
            option.text = purpose;
            purposeSelect.add(option);
        });


        var colors = ['Red', 'Blue', 'Green', 'Black'];
        var colorSelect = document.getElementById('colorInput');
        colors.forEach(function (color) {
            var option = document.createElement('option');
            option.value = color; //.toLowerCase()
            option.text = color;
            colorSelect.add(option);
        });


        var mvTypes = ['Car', 'Mopeds (0-49 cc)', 'Motorcycle w/ side car', 'Motorcycle w/o side car', 'Non-conventional MC (Car)', 'Shuttle Bus', 'Sports Utility Vehicle', 'Tourist Bus', 'Tricycle', 'Truck Bus', 'Trucks', 'Utility Vehicle', 'School bus', 'Rebuilt', 'Mobil Clinic', 'Trailer'];     
        var mvTypeSelect = document.getElementById('mvTypeInput');
        mvTypes.forEach(function (mvType) {
            var option = document.createElement('option');
            option.value = mvType; //.toLowerCase() You can set the value based on your requirements
            option.text = mvType;
            mvTypeSelect.add(option);
        });


         var regions = ['Region I', 'Region II', 'Region III', 'Region IV‑A', 'MIMAROPA', 'Region V', 'Region VI', 'Region VII', 'Region VIII', 'Region IX', 'Region X', 'Region XI', 'Region XII', 'Region XIII', 'NCR', 'CAR', 'BARMM'];
        var regionSelect = document.getElementById('regionInput');
         regions.forEach(function (region) {
            var option = document.createElement('option');
            option.value = region; //.toLowerCase() You can set the value based on your requirements
            option.text = region;
            regionSelect.add(option);
        });


        var series = ['Sedan', 'SUV', 'Truck', 'Hatchback'];
        var seriesSelect = document.getElementById('seriesInput');
        series.forEach(function (serie) {
            var option = document.createElement('option');
            option.value = serie; //.toLowerCase()
            option.text = serie;
            seriesSelect.add(option);
        });


      });
</script>

<script>
    var userNameInput = document.getElementById('userNameInput');
    var userIdInput = document.getElementById('userId');
    var userSuggestions = document.getElementById('userSuggestions');

    userNameInput.addEventListener('input', function () {
        var selectedUser = this.value;
        var options = userSuggestions.getElementsByTagName('option');

        for (var i = 0; i < options.length; i++) {
            if (options[i].value === selectedUser) {
                userIdInput.value = options[i].getAttribute('data-userid');
                return;
            }
        }

        // Clear the input if the entered value doesn't match any suggestion
        userNameInput.value = '';
        userIdInput.value = '';
    });
</script>
<script>
    // Wait for the document to be fully loaded
    document.addEventListener("DOMContentLoaded", function () {
        // Get the form element
        var bookingForm = document.forms["bookingForm"];

        // Add an event listener to the form
        bookingForm.addEventListener("submit", function (event) {
            // Prevent the default form submission behavior
            event.preventDefault();

            // Validate the form and submit it if valid
            if (validateForm()) {
                document.forms["bookingForm"].submit();
            } else {
                // Highlight the empty required fields
                highlightEmptyFields();
            }
        });
    });
</script>

</body>

</html>