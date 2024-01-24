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
$customerQuery = $connect->prepare("SELECT first_name, middle_name, last_name, address FROM login WHERE email = ?");
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

// Check if 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $bookingId = $_GET['id'];

    // Fetch details of the selected booking from the 'car_emission' table
    $bookingQuery = $connect->prepare("SELECT ce.*, sl.price_1, sl.price_2, sl.price_3 FROM car_emission ce
                                      INNER JOIN schedule_list sl ON ce.event_id = sl.id
                                      WHERE ce.id = ?");
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
} else {
    // If 'id' parameter is not set, redirect to the schedule status page
    header('Location: schedule-status.php');
    exit();
}


?>

<!-- Rest of the HTML/PHP code for your page -->



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
                                                <td style="text-align: right;"><input id="userId" class="" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="hidden" value="<?php echo $customerDetails['user_id']; ?>" readonly></td>
                                                <td class="text-end"><input id="sched_id" class="" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="hidden" value="<?php echo $event['id']; ?>" readonly></td>   
                                                </tr> -->
                                                <tr>
   
   <td class="text-end" style="vertical-align: middle;">Ticketing ID<input id="ticketingId" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['ticketing_id']; ?>" type="text" readonly disabled></td>
   <td class="text-end" style="vertical-align: middle;">Application Date<input id="appDate" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['app_date']; ?>" type="text" readonly disabled></td>
   
   </tr>
                                            <tr>
                                            <!-- <td  class="text-end">App Date<input type="text" style="margin-left: 5px;" ></td> -->
   

                                            <td class="text-end" type="text" style="width: 200px;">Plate Number<input id="plateNumber" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;"  value="<?php echo $bookingDetails['plate_number']; ?>"  ></td>
                                                    <td class="text-end" style="width: 200px;">Organization<input id="organizationInput" type="text" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['organization']; ?>" ></td>
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
                                                    <td class="text-end">First Name<input id="firstnameInput" type="text" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['customer_first_name']; ?>" ></td>
                                                </tr> 
                                                <tr>
                                                <td class="text-end">
                 <!-- Input for uploading a picture -->
                 <label for="OrInputPicture" class="btn btn-primary">
    OR Picture
    <input type="file" id="OrInputPicture" class="d-none" accept="image/*" onchange="displaySelectedOrPicture(this)" >
</label>
                <!-- Button to display the selected picture -->
                <button type="button" class="btn btn-success" onclick="displaySelectedOrPicture(this)" id="selectedOrFilename">Display</button>
                </td>
                <td class="text-end" style="width: 200px;">Middle Name<input id="middleNameInput" type="text" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['customer_middle_name']; ?>"  ></td>
                                            
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
                                                
                                                    <td class="text-end" style="width: 200px;">Last Name<input id="lastNameInput" type="text" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['customer_last_name']; ?>" ></td>
                                                </tr>
                                                <tr>
   
                                                <td class="text-end" style="vertical-align: middle;">Vehicle CR No.<input id="vehicleCrInput" type="text" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['vehicle_cr_no']; ?>"  ></td>
                                                <td class="text-end" style="width: 200px;">Address<input id="addressInput" type="text" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['address']; ?>"  ></td>
                                                </tr>
                                                <tr>
                                                <td style="text-align: right;">Vehicle OR No.<input id="vehicleOrInput" type="text"  class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['vehicle_or_no']; ?>" ></td>
                                                <td class="text-end">Engine<input id="engineInput" class="form-control" type="text" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['engine']; ?>"  ></td>
                                           
                                                </tr>
                                            
                                                <tr>
                                                <td class="text-end">First Registration Date
                                                <input id="firstRegInput" type="date" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['first_reg_date']; ?>" >
    </td>
    <td style="text-align: right;">Chassis<input type="text" id="chassisInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['chassis']; ?>"  ></td>
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
                        $selected = ($year == $bookingDetails['year_model']) ? 'selected' : '';
                        echo '<option value="' . $year . '" ' . $selected . '>' . $year . '</option>';
                    }
                    ?>
                                                </select>
                                                    </td>
                                                    <td class="text-end">Make
                                                    <select name="makeInput" id="makeInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;">
                                                    <?php
                                                    // Assuming $fuelTypes is an array containing your fuel types
                                                    $makeTypes = array("Toyota", "Honda", "Ford", "Chevrolet", "Mitsubishi");

                                                    // Loop to generate options
                                                    foreach ($makeTypes as $makeType) {
                                                        $selected = ($makeType === $bookingDetails['make']) ? 'selected' : '';
                                                        echo '<option value="' . $makeType . '" ' . $selected . '>' . $makeType . '</option>';
                                                    }
                                                    ?>  
                                                    </select>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">Fuel type
                                                    <select name="fuelTypeInput" id="fuelTypeInput" type="text" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['fuel_type']; ?>" >
                        <!-- Populate options dynamically based on your requirements -->
                        <?php
                    // Assuming $fuelTypes is an array containing your fuel types
                    $fuelTypes = array("Gasoline", "LPG", "Diesel - None Turbo", "Turbo Diesel");

                    // Loop to generate options
                    foreach ($fuelTypes as $fuelType) {
                        $selected = ($fuelType === $bookingDetails['fuel_type']) ? 'selected' : '';
                        echo '<option value="' . $fuelType . '" ' . $selected . '>' . $fuelType . '</option>';
                    }
                    ?>
                                                    </select>
                                                    </td>
                                                    <td class="text-end">Series
                                                    <select name="seriesInput" id="seriesInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;">
                    <?php
                    // Assuming $fuelTypes is an array containing your fuel types
                    $seriesTypes = array("Sedan", "SUV", "Truck", "Hatchback");

                    // Loop to generate options
                    foreach ($seriesTypes as $seriesType) {
                        $selected = ($seriesType === $bookingDetails['series']) ? 'selected' : '';
                        echo '<option value="' . $seriesType . '" ' . $selected . '>' . $seriesType . '</option>';
                    }
                    ?> 
                    </select>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">Purpose
                                                    <select name="purposeInput" id="purposeInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;">
                                                    <?php
                                                    // Assuming $fuelTypes is an array containing your fuel types
                                                    $purposeTypes = array("For Registration", "For Compliance", "Plate Redemption");

                                                    // Loop to generate options
                                                    foreach ($purposeTypes as $purposeType) {
                                                        $selected = ($purposeType === $bookingDetails['purpose']) ? 'selected' : '';
                                                        echo '<option value="' . $purposeType . '" ' . $selected . '>' . $purposeType . '</option>';
                                                    }
                                                    ?>
                                                    </select>
                                                    <td class="text-end">Color
                                                    <select name="colorInput" id="colorInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;">
                                                    <?php
                                                    // Assuming $fuelTypes is an array containing your fuel types
                                                    $colorTypes = array("Red", "Blue", "Green", "Black");

                                                    // Loop to generate options
                                                    foreach ($colorTypes as $colorType) {
                                                        $selected = ( $colorType === $bookingDetails['color']) ? 'selected' : '';
                                                        echo '<option value="' .  $colorType . '" ' . $selected . '>' .  $colorType . '</option>';
                                                    }
                                                    ?> 
                                                    </select>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">MV Type
                                                    <select name="mvTypeInput" id="mvTypenput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;">
                                                    <?php
                                                    // Assuming $fuelTypes is an array containing your fuel types
                                                    $mvTypes = array("Car", "Mopeds (0-49 cc)", "Motorcycle w/ side car", "Motorcycle w/o side car", "Non-conventional MC (Car)", "Shuttle Bus", "Sports utility Vehicle", "Tourist Bus", "Tricycle", "Truck Bus", "Trucks", "Utility Vehicle", "School bus", "Rebuilt", "Mobil Clinic", "Trailer");

                                                    // Loop to generate options
                                                    foreach ($mvTypes as $mvType) {
                                                        $selected = ($mvType === $bookingDetails['mv_type']) ? 'selected' : '';
                                                        echo '<option value="' . $mvType . '" ' . $selected . '>' . $mvType . '</option>';
                                                    }
                                                    ?>
                                                    </select>
                                                    <td class="text-end">
    Gross Weight
    <input id="grossWeightInput" type="text" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['gross_weight']; ?>" >
</td>
                                                   
                                                </tr>
                                                
                                                <tr>
                                                   <td class="text-end">MV File No.<input id="mvFileInput" type="text"class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['mv_file_no']; ?>" ></td>  
                                           
                                                    <td class="text-end">Net Capacity<input id="netCapacityInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $bookingDetails['net_capacity']; ?>" ></td>
                                                </tr> 
                                                <tr>
                                                      <!-- <td class="text-end" style="width: 200px;">Ticketing ID<input type="text" style="margin-left: 5px;" ></td> -->
                                                      <td class="text-end">Classification
                                                      <select name="classification" id="classificationInput" class="form-control" style="max-width: 230px; display: inline-block; margin-left: 8px;" >
                                                        <?php
                                                        // Assuming $fuelTypes is an array containing your fuel types
                                                        $classificationTypes = array("Diplomatic-Consular Corps", "Diplomatic-Chief of Mission", "Diplomatic-Diplomatic Corps", "Exempt-Government", "Diplomatic Exempt-Economics Z", "Government", "For hire", "Diplomatic-OEV", "Private", "Exempt-For-Hire", "Exempt-Private");

                                                        // Loop to generate options
                                                        foreach ($classificationTypes as $classificationType) {
                                                            $selected = ($classificationType === $bookingDetails['classification']) ? 'selected' : '';
                                                            echo '<option value="' . $classificationType . '" ' . $selected . '>' . $classificationType . '</option>';
                                                        }
                                                        ?>                   
                                                        </select>
                                                    <td class="text-end">Region
                                                    <select name="region" id="regionInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;">
                    <?php
                    // Assuming $fuelTypes is an array containing your fuel types
                    $regionTypes = array("Region I", "Region II", "Region III", "Region IV‑A", "MIMAROPA", "Region V", "Region VI", "Region VII", "'Region VIII", "Region IX", "Region X", "Region XI", "Region XII", "Region XIII", "NCR", "CAR", "BARMM");

                    // Loop to generate options
                    foreach ($regionTypes as $regionType) {
                        $selected = ($regionType === $bookingDetails['region']) ? 'selected' : '';
                        echo '<option value="' . $regionType . '" ' . $selected . '>' . $regionType . '</option>';
                    }
                    ?>
                    </select>
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
<button type="button" class="btn btn-warning" onclick="finishEditModal()">Finish Edit</button>    
<button type="button" class="btn btn-danger m-2" onclick="cancelFunction()">Cancel</button>

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
                Are you sure you want to go back to View Booking? Any unsaved changes will be lost.
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
function displaySelectedPicture() {
    // Show the modal when the button is clicked
    $('#carPictureModal').modal('show');
}
</script>



<script>
    function navigateBack() {
        // Redirect to home.php
        var bookingId = '<?php echo $bookingId; ?>';
        window.location.href = 'view-booking.php?id=' + bookingId;
    }
</script>

</body>

</html>
