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




$userName = $customerDetails['first_name'];
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
                                          
                                            <tr>
                                                <td style="text-align: right;"><input id="userId" class="" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="hidden" value="<?php echo $customerDetails['user_id']; ?>" readonly></td>
                                                <td class="text-end"><input id="sched_id" class="" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="hidden" value="<?php echo $event['id']; ?>" readonly></td>
                                           
                                                </tr>
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
                <button type="button" class="btn btn-success" onclick="displaySelectedOrPicture(this)" id="selectedOrFilename">Display</button>
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
                                                   <td class="text-end">MV File No.<input id="mvFileInput" type="text"class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" ></td>  
                                           
                                                    <td class="text-end">Net Capacity<input id="netCapacityInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text"></td>
                                                </tr> 
                                                <tr>
                                                      <!-- <td class="text-end" style="width: 200px;">Ticketing ID<input type="text" style="margin-left: 5px;" ></td> -->
                                                      <td class="text-end">Classification
                                                    <select name="classification" id="classificationInput"  class="form-control" style="max-width: 230px; display: inline-block; margin-left: 8px;" type="text">
                        <!-- Populate options dynamically based on your requirements -->
                                                    </select>
                                                    </td>
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
                        <td id ="amountValue" class="text-center" style="color: ;font-size: 23px;"></td>
                    </tr>
                    <tr>
    <th><span style="font-weight: normal !important;">Mode of Payment</span></th>
</tr>
<tr>
    <td>
        <select class="form-select" id="paymentMode" name="paymentMode">
            <option value="cash">Cash</option>
            <option value="paymaya">PayMaya</option>
            <!-- Add more payment options if needed -->
        </select>
    </td>
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

  <?php include 'partials/footer.php' ?>

    <script src="admin/assets2/bootstrap/js/bootstrap.min.js"></script>
    <!-- <script src="admin/assets2/js/aos.min.js"></script> -->
    <script src="admin/assets2/js/bs-init.js"></script>
    <!-- <script src="admin/assets2/js/bold-and-bright.js"></script> -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    function navigateBack() {
        // Redirect to home.php
        window.location.href = 'home.php';
    }
</script>
<script>
function cancelBookFunction() {
    // Display a confirmation dialog
    var confirmed = window.confirm("Are you sure you want to cancel? Any unsaved changes will be lost.");

    // Check if the user confirmed
    if (confirmed) {
        // Clear the input fields
        clearInputFields();
    }
    // If the user cancels, do nothing
}

// Function to clear input fields
function clearInputFields() {
    // Assuming that these are the IDs of your input fields, modify accordingly
    $('#firstnameInput').val('');
    $('#middleNameInput').val('');
    $('#lastNameInput').val('');
    $('#addressInput').val('');
    // Add more fields as needed
    // ...

    // Optionally, you can also clear the selected file name and preview
    $('#selectedPictureFilename').text('No file selected');
    $('#carPictureDisplay').attr('src', '');
    $('#selectedOrFilename').text('No file selected');
    $('#orPictureDisplay').attr('src', '');
    $('#selectedCrFilename').text('No file selected');
    $('#crPictureDisplay').attr('src', '');
}

</script>

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
document.addEventListener('DOMContentLoaded', function () {
         
    var mvTypes = ['Car', 'Mopeds (0-49 cc)', 'Motorcycle w/ side car', 'Motorcycle w/o side car', 'Non-conventional MC (Car)', 'Shuttle Bus', 'Sports Utility Vehicle', 'Tourist Bus', 'Tricycle', 'Truck Bus', 'Trucks', 'Utility Vehicle', 'School bus', 'Rebuilt', 'Mobil Clinic', 'Trailer'];     
        var mvTypeSelect = document.getElementById('mvTypeInput');
        mvTypes.forEach(function (mvType) {
            var option = document.createElement('option');
            option.value = mvType; //.toLowerCase() You can set the value based on your requirements
            option.text = mvType;
            mvTypeSelect.add(option);
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




        var classifications = ['Diplomatic-Consular Corps', 'Diplomatic-Chief of Mission', 'Diplomatic-Diplomatic Corps', 'Exempt-Government', 'Diplomatic Exempt-Economics Z', 'Government', 'For hire', 'Diplomatic-OEV', 'Private', 'Exempt-For-Hire', 'Exempt-Private'];
            var classificationSelect = document.getElementById('classificationInput');
            classifications.forEach(function (classification) {
            var option = document.createElement('option');
            option.value = classification; //.toLowerCase()
            option.text = classification;
            classificationSelect.add(option);
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


        var makes = ['Toyota', 'Honda', 'Mitsubishi', 'Ford', 'Chevrolet', 'Kia', 'Hyundai', 'MG', 'Geely', 'Chery'];
        var makeSelect = document.getElementById('makeInput');
         makes.forEach(function (make) {
            var option = document.createElement('option');
            option.value = make; //.toLowerCase()
            option.text = make;
            makeSelect.add(option);
        });



        var colors = ['Red', 'Blue', 'Green', 'Black'];
        var colorSelect = document.getElementById('colorInput');
        colors.forEach(function (color) {
            var option = document.createElement('option');
            option.value = color; //.toLowerCase()
            option.text = color;
            colorSelect.add(option);
        });


        function updateAmount() {
    var mvTypeSelect = document.getElementById('mvTypeInput');
    var amountElement = document.getElementById('amountValue');
    var price1 = '<?php echo $event['price_1']; ?>';
    var price2 = '<?php echo $event['price_2']; ?>';
    var price3 = '<?php echo $event['price_3']; ?>';

    // Get the selected mvType
    var selectedMvType = mvTypeSelect.value;

    // Update the amount based on mvType
    if (selectedMvType === 'Mopeds (0-49 cc)' || selectedMvType === 'Motorcycle w/ side car' || selectedMvType === 'Motorcycle w/o side car' || selectedMvType === 'Non-conventional MC (Car)' || selectedMvType === 'Tricycle') {
        amountElement.textContent = price1;
    } else if (selectedMvType === 'Car' || selectedMvType === 'Sports utility Vehicle' || selectedMvType === 'Utility Vehicle' ||  selectedMvType === 'Rebuilt' || selectedMvType === 'Mobil Clinic') {
        amountElement.textContent = price2;
    } else if (selectedMvType === 'School bus' || selectedMvType === 'Shuttle Bus' || selectedMvType === 'Tourist Bus' || selectedMvType === 'Truck Bus' || selectedMvType === 'Trucks' || selectedMvType === 'Trailer') {
        amountElement.textContent = price3;
    } else {
        // Handle other cases or set a default value
        amountElement.textContent = '0';
    }
}


// Add an event listener to the mvType select element
var mvTypeSelect = document.getElementById('mvTypeInput'); // Correct id here
mvTypeSelect.addEventListener('change', updateAmount);

// Call the function initially to set the default amount
updateAmount();


    });
</script>


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
        return grossWeightInput !== '' && netCapacityInput !== '' && chassisInput !== '' && engineInput !== '' && addressInput !== '' && firstnameInput !== ''  && organizationInput !== '' && userNameInput !== '' && vehicleCrInput !== '' && vehicleOrInput !== '' && firstRegInput !== '' && mvFileInput !== ''  && middleNameInput !== '' && lastNameInput !== '' && plateNumberLetter !== '' && plateNumberNumbers !== '';
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
       
      

        // Check if any of the required fields is empty
        return validateVehicleInformation() ;
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
        formData.append("amount", $("#amountValue").text());
        formData.append("organization", $("#organizationInput").val());
        formData.append("engine", $("#engineInput").val());
        formData.append("chassis", $("#chassisInput").val());
        formData.append("make", $("#makeInput").val());
        formData.append("series", $("#seriesInput").val());
        formData.append("color", $("#colorInput").val());
        formData.append("grossWeight", $("#grossWeightInput").val());
        formData.append("netCapacity", $("#netCapacityInput").val());
        formData.append("carPicture", $("#carPictureInput")[0].files[0]);
        formData.append("paymentMethod", $("#paymentMode").val());
        // formData.append("paymentStatus", $("#paymentStatus").val());
        formData.append("plateNumberLetters", $("#plateNumberLetters").val());
        formData.append("plateNumberNumbers", $("#plateNumberNumbers").val());
        // Add more fields as needed

        $.ajax({
    type: "POST",
    url: "submit_booked_by_user.php", // Replace with your server-side script URL
    data: formData,
    contentType: false,
    processData: false,
    dataType: "json", // Change this based on your server response type
    success: function (response) {
        // Handle the server response here
        console.log(response);

        if (response.status === 'error') {
            // Show alert if there's an error
            alert(response.message);
        } else {
            // Show success message
            alert("Data sent successfully!");
        }
    },
    error: function (xhr, status, error) {
        // Handle errors
        console.error("XHR status:", status);
        console.error("XHR error:", error);
        console.error("XHR response:", xhr.responseText);
        alert("Error sending data to the server! Check the console for details.");
    }
});

    } else {
        // Highlight the empty required fields
        highlightEmptyFields();
    }
}


</script>


</body>

</html>