<!-- booking_form.php -->
<?php
session_start();

date_default_timezone_set('Asia/Manila');


include("connect/connection.php");

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // If not logged in, redirect to the login page
    header('Location: index.php');
    exit();
} 

// Check if the event ID is set in the query parameters
if (!isset($_GET['eventId']) || !is_numeric($_GET['eventId'])) {
    // If the event ID is not provided or not numeric, redirect to the homepage
    header('Location: home.php');
    exit();
}

// Get the event ID from the query parameters and sanitize it
$eventId = intval($_GET['eventId']);

// Include database connection code (modify as needed)
include("connect/connection.php");

// Check connection
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

// Prepare and execute a query to check if the event exists in the database
$stmt = $connect->prepare("SELECT * FROM schedule_list WHERE id = ?");
$stmt->bind_param("i", $eventId);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

// Check if the event exists
if (!$event) {
    // If the event does not exist, redirect to the homepage
    header('Location: home.php');
    exit();
}

// Fetch customer details from the database based on the email in the session
$customerEmail = $_SESSION['email'];
$customerQuery = $connect->prepare("SELECT user_id, first_name, middle_name, last_name, address FROM login WHERE email = ?");
$customerQuery->bind_param("s", $customerEmail);
$customerQuery->execute();
$customerResult = $customerQuery->get_result();
$customerDetails = $customerResult->fetch_assoc();





// Close the customer query statement
$customerQuery->close();

// Close the database connection
$stmt->close();
$connect->close();


?>
<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Log in - Brand</title>
    <link rel="stylesheet" href="admin/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&amp;display=swap">
    <link rel="stylesheet" href="admin/assets/css/aos.min.css">
    <link rel="stylesheet" href="admin/assets/css/NavBar-with-pictures.css">
</head>

<body style=" background-color: #f8f9fa;">
  <?php include 'partials/nav.php'?>
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
                                            <!-- <td  class="text-end">App Date<input type="text" style="margin-left: 5px;" ></td> -->
   

                                            <td class="text-end" style="width: 200px;">
                                                Plate No. 
                                                <input id="plateNumberLetters" class="form-control" style="max-width: 100px; display: inline-block;" type="text" placeholder="ex. ABCD">
                                                <input id="plateNumberNumbers" class="form-control" style="max-width: 100px; display: inline-block; margin-left: 8px;" type="text" placeholder="ex. 1234">
                                                 </td>
                                                    <td class="text-end" style="width: 200px;">Organization<input id="organizationInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text"></td>
                                                </tr>
                                               <tr>
                                                    <!-- <td class="text-end" style="width: 200px;">Ticketing ID<input type="text" style="margin-left: 5px;" ></td> -->
                                                    <td class="text-end">
                 <!-- Input for uploading a picture -->
                <label for="carPictureInput" class="btn btn-primary">
                Car Picture
                <input type="file" id="carPictureInput" class="d-none" accept="image/*" onchange="displaySelectedPicture(this)">
                </label>
                <!-- Button to display the selected picture -->
                <button type="button" class="btn btn-success" onclick="displaySelectedPicture()" id="selectedPictureFilename">Display</button>  
                </td>
                                                    <td class="text-end">First Name<input id="firstnameInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text"></td>
                                                </tr> 
                                                <tr>
                                                <td class="text-end">
                 <!-- Input for uploading a picture -->
                <label for="OrInputPicture" class="btn btn-primary">
                OR Picture
                <input type="file" id="OrInputPicture" class="d-none" accept="image/*" onchange="displaySelectedOrPicture(this)">
                </label>
                <!-- Button to display the selected picture -->
                <button type="button" class="btn btn-success" onclick="displaySelectedOrPicture()" id="selectedOrFilename">Display</button>  
                </td>
                <td class="text-end" style="width: 200px;">Middle Name<input id="middleNameInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text"></td>
                                            
                                                <tr>
                                                <td class="text-end">
                 <!-- Input for uploading a picture -->
                <label for="CrInputPicture" class="btn btn-primary">
                CR Picture
                <input type="file" id="CrInputPicture" class="d-none" accept="image/*" onchange="displaySelectedCrPicture(this)">
                </label>
                <!-- Button to display the selected picture -->
                <button type="button" class="btn btn-success" onclick="displaySelectedCrPicture()" id="selectedCrFilename">Display</button>  
                </td>
                                                
                                                    <td class="text-end" style="width: 200px;">Last Name<input id="lastNameInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text"></td>
                                                </tr>
                                                <tr>
   
                                                <td class="text-end" style="vertical-align: middle;">Vehicle CR No.<input id="vehicleCrInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text"></td>
                                                <td class="text-end" style="width: 200px;">Address<input id="addressInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text"></td>
                                                </tr>
                                                <tr>
                                                <td style="text-align: right;">Vehicle OR No.<input id="vehicleOrInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text" ></td>
                                                <td class="text-end">Engine<input id="engineInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text"></td>
                                           
                                                </tr>
                                            
                                                <tr>
                                                <td class="text-end">First Registration Date
                                                <input id="firstRegInput" type="date" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;">
    </td>
    <td style="text-align: right;">Chassis<input type="text" id="chassisInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text"></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">Year Model
                                                    <select id="yearModelInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;"> <!-- Adjust the width as needed -->
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
                                                    <td class="text-end">Make
                                                    <select name="makeInput" id="makeInput"  class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" >
                        <!-- Populate options dynamically based on your requirements -->
                                                    </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">Fuel type
                                                    <select name="fuelTypeInput" id="fuelTypeInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;">
                        <!-- Populate options dynamically based on your requirements -->
                    </select>
                                                    </td>
                                                    <td class="text-end">Series
                                                    <select name="seriesInput" id="seriesInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;">
                                                <!-- Populate options dynamically based on your requirements -->
                                                </select>    
                                                </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">Purpose
                                                    <select name="purposeInput" id="purposeInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;">
                                                    <!-- Populate options dynamically based on your requirements -->
                                                    </select>
                                                    </td>
                                                    <td class="text-end">Color
                                                    <select name="colorInput" id="colorInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;">
                                                    <!-- Populate options dynamically based on your requirements -->
                                                    </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">MV Type
                                                    <select name="mvTypeInput" id="mvTypeInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;">
                                                    <!-- Populate options dynamically based on your requirements -->
                                                    </select>
                                                    </td>
                                                    <td class="text-end">
    Gross Weight
    <input id="grossWeightInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text">
</td>
                                                   
                                                </tr>
                                                
                                                <tr>
                                                    <!-- <td class="text-end" style="width: 200px;">Ticketing ID<input type="text" style="margin-left: 5px;" ></td> -->
                                                    <td class="text-end">Classification
                                                    <select name="classification" id="classificationInput"  class="form-control" style="max-width: 230px; display: inline-block; margin-left: 8px;" type="text">
                        <!-- Populate options dynamically based on your requirements -->
                                                    </select>
                                                    </td>
                                                    <td class="text-end">Net Capacity<input id="netCapacityInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text"></td>
                                                </tr> 
                                                <tr>
                                                    <td class="text-end">Region
                                                    <select name="regionInput" id="regionInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;">
                                                    <!-- Populate options dynamically based on your requirements -->
                                                    </select>
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
                       <td class="text-center" style="font-size: 23px;"><?php echo date('M. j, Y', strtotime($event['start_datetime'])); ?></td>
                    </tr> 
                <tr>
                        <th><span style="font-weight: normal !important;">Date</span></th>
                    </tr>   
                    <tr>
                       <td class="text-center" style="font-size: 23px;"><?php echo date('g:ia', strtotime($event['start_datetime'])) . ' to ' . date('g:ia', strtotime($event['end_datetime'])); ?></td>
                    </tr>  
                <tr>
                        <th><span style="font-weight: normal !important;">Amount (PHP)</span></th>
                    </tr>
                    <tr>
                        <td class="text-center" style="color: ;font-size: 23px;"></td>
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


                      </div>
                </div>
            </div>  
        </form> 
        </div>
  <?php include 'partials/footer.php' ?>
    <script src="admin/assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="admin/assets/js/aos.min.js"></script>
    <script src="admin/assets/js/bs-init.js"></script>
    <script src="admin/assets/js/bold-and-bright.js"></script>
</body>

</html>