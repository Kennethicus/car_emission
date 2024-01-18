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

    // Check if the id exists and is associated with the provided ticketId
    $detailsQuery = "SELECT * FROM car_emission WHERE id = '$reserve_id' AND ticketing_id = '$ticketId'";
    $detailsResult = $connect->query($detailsQuery);

    if ($detailsResult->num_rows === 1) {
        $details = $detailsResult->fetch_assoc();
        // Now you have the details of the specific car emission entry and ticketId

      // Check if there's an existing entry in the test_result table
$bookingId = $details['id']; // Assuming 'id' is the booking_id in your 'car_emission' table
$testResultQuery = "SELECT * FROM test_result WHERE booking_id = '$bookingId'";
$testResultResult = $connect->query($testResultQuery);

if ($testResultResult->num_rows === 1) {
    // If an entry exists, fetch the data
    $testResultData = $testResultResult->fetch_assoc();

    // Assign the fetched values to variables for later use
    $hcValue = $testResultData['HC'];
    $coValue = $testResultData['CO'];
    $co2Value = $testResultData['CO2'];
    $o2Value = $testResultData['O2'];
    $nValue = $testResultData['N'];
    $rpmValue = $testResultData['RPM'];
    $kAveValue = $testResultData['K_AVE'];
    $testValue = $testResultData['testing_status'];
    $testedValue = $testResultData['Tested'];
    $validValue = $testResultData['Valid'];
    $UploadedValue = $testResultData['Uploaded'];
    $UploadedImageValue = $testResultData['Uploaded_Image'];
    $recordValue = $testResultData['record_status'];
    $AuthValue = $testResultData['auth_code'];
    $finalize = $testResultData['finalize'];
} else {
    // If no entry exists, set default values or leave them empty
    $hcValue = $coValue = $co2Value = $o2Value = $nValue = $rpmValue = $kAveValue = '';

}
    } else {
        // Handle the case where details are not found or parameters are invalid
        echo "Invalid parameters or details not found!";
        exit();
    }
} else {
    // Handle the case where id or ticketId parameters are not provided
    echo "Reservation details not found!";
    exit();
}
?>

<!DOCTYPE html>
<html data-bs-theme="light" lang="en">
<style>
    .custom-btn {
        background-color: rgb(48, 120, 42);
        transition: background-color 0.3s ease;
    }

    .custom-btn:hover {
        background-color: #2e7031; /* Change to the desired hover color */
    }
</style>
<style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
<?php include 'partials/test-head.php' ?>

<body id="page-top">
    <div id="wrapper">
        <?php include 'partials/nav.php'?>
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <?php include 'partials/nav-2.php' ?>
                <!-- Display the details content here -->
                <div class="container mt-3">
                <!-- <div class="d-sm-flex justify-content-between align-items-center mb-4">
                        <h4 class="text-dark mb-0" style="--bs-body-font-size: 9rem;--bs-body-font-weight: normal;">Server Date -&nbsp;</h4>
                        <h4 class="text-dark mb-0" style="--bs-body-font-size: 9rem;--bs-body-font-weight: normal;margin-right: auto;margin-top: 2px;">Server Date&nbsp;&nbsp;</h4>
                    </div> -->
                    
                    <div class="row">
                    <div class="col-md-6 col-xl-3 col-xxl-5 mb-4">
    <ul >
        <li class="list-group-item text-center" style="margin-top: 3px; margin-bottom: 3px;">

<!-- Print Button -->
<button id="printDetails" class="btn btn-primary custom-btn" type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="Print" onclick="printTables()" <?php echo ($details['cec_number'] == 0) ? 'disabled' : ''; ?>>
    <i class="fas fa-print" style="font-size: 44px;"></i>
</button>



            <!-- Download Button -->
            <button class="btn btn-primary custom-btn" type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="Download">
                <i class="fas fa-download" style="font-size: 44px;"></i>
            </button>
        </li>
    </ul>
</div>

                        <div class="col-md-6 col-xl-3 col-xxl-6 mb-4 mx-auto">
                        <div id="testStatusText" class="" style=" font-size: 14px; margin-top: 5px;">
   
</div>
<h6 class="text-dark justify-content-end mb-0" style="--bs-body-font-size: 9rem;--bs-body-font-weight: normal;margin-top: 10px;margin-left: 20px;">Testing Status
    <?php echo getTestStatusHTML($testValue); ?>
</h6>

<h6 id ="get-record-status" class="text-dark justify-content-end mb-0" style="--bs-body-font-size: 9rem;--bs-body-font-weight: normal;margin-top: 10px;margin-left: 20px;">Record Status
    <?php echo getRecordStatusHTML($recordValue); ?>
</h6>
                        </div>
                        
                        <?php include 'assets/php-includes/test/test-div-form.php' ?>
                        <div class="col-lg-3">

                        <!-- <div class="col-lg-5 col-xl-4 col-xxl-4"> -->
                        <div class="">
                      
                        <div class="card shadow mb-4">
    <div class="card-header d-flex justify-content-between align-items-center" style="background: var(--bs-success-text-emphasis);">
        <h6 class="text-primary fw-bold m-0" style="background: Transparent;">
            <span style="color: rgb(244, 248, 244);">IMAGE</span>
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive" id="imageUploadFormContainer">
            <form id="imageUploadForm" action="upload_image.php" method="post" enctype="multipart/form-data" class="mb-3">
                <div class="input-group">
                    <!-- <label for="imageUpload" class="input-group-text">Choose File</label> -->
                   
                </div>
<input type="hidden" name="bookingId" value="<?php echo $bookingId; ?>">
<input type="file" name="imageUpload" id="imageUpload" accept="image/*" class="form-control" required <?php echo ($testedValue != 1 || $finalize == 1) ? 'disabled' : ''; ?>>
    <button id="uploadButton" type="button" onclick="uploadImage()" class="btn btn-primary mt-2" <?php echo ($testedValue != 1 || $finalize == 1) ? 'disabled' : ''; ?>>Upload</button>
  
            </form>
            <div id="uploadResult"></div>

            <div class="text-center">
                <?php
                // Assuming $bookingId is the unique identifier for the record
                $imageQuery = "SELECT vehicle_img FROM test_result WHERE booking_id = '$bookingId'";
                $imageResult = $connect->query($imageQuery);

                // ...

                if ($imageResult->num_rows === 1) {
                    $imageData = $imageResult->fetch_assoc();
                    $imagePath = $imageData['vehicle_img'];

                    // Path to the correct placeholder image if the actual image is not available
                    $placeholderPath = "assets/img/done.png";  // Adjust the path accordingly

                    // Check if the image path exists
                    if (file_exists($imagePath)) {
                        echo '<img id="imageElement" src="' . $imagePath . '" alt="Image" style="max-width: 100%; height: 100px;">';
                    } else {
                        echo '<img id="imageElement" src="' . $placeholderPath . '" alt="Placeholder Image" style="max-width: 100%; height: 100px;">';
                    }
                } else {
                    echo "Image data not found!";
                }

                // ...
                ?>
            </div>
        </div>
    </div>
</div>






<div class="card shadow mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center" style="background: var(--bs-success-text-emphasis);">
                                    <h6 class="text-primary fw-bold m-0" style="background: Transparent;"><span style="color: rgb(244, 248, 244);">TEST RESULT</span></h6>
                                   
                                </div>
    <div class="card-body">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th><span style="font-weight: normal !important;" >HC</span></th>
                    <th><input id="hcInput" type="text" style="width: 100%;" value="<?php echo $hcValue; ?>"></th>
                    <th><span style="font-weight: normal !important;">CO</span></th>
                    <th> <input id="coInput" type="text" style="width: 100%;" value="<?php echo $coValue; ?>"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>CO2</td>
                    <td><input id="co2Input" type="text" style="width: 100%;" value="<?php echo $co2Value; ?>"></td>
                    <td>O2</td>
                    <td><input id="o2Input" type="text" style="width: 100%;" value="<?php echo $o2Value; ?>"></td>
                </tr>
                <tr>
                    <td>N</td>
                    <td><input id="nInput" type="text" style="width: 100%;" value="<?php echo $nValue; ?>"></td>
                    <td>RPM</td>
                    <td><input id="rpmInput" type="text" style="width: 100%;" value="<?php echo $rpmValue; ?>"></td>
                </tr>

<tr> <td>K-AVE</td>
    <td colspan="3" style="text-align: center;">
    <input id="kAveInput" type="text" style="width: 100%;" value="<?php echo $kAveValue; ?>">
    </td>
</tr>     
   
            </tbody>     
                  
        </table>     
    </div>

    <div class="text-center">
    <button onclick="CheckAndSaveTestStatus()" id="checkTestStatusButton" class="btn btn-primary">Check Test Status</button>

    </div>

</div>

</div>
<!-- capping -->
<div></div>
<script>
    // JavaScript function to navigate back to details.php with specific parameters
    function goBack() {
        var reserveId = '<?php echo $reserve_id; ?>';
        var ticketId = '<?php echo $ticketId; ?>';
        window.location.href = 'details.php?id=' + reserveId + '&ticketId=' + ticketId;
    }
</script>
</div>
                </div>
            </div>   
        </div>
        <div class="container mt-3">
            <div class="row">
            <div class="col-lg-5 " >
            <?php include 'assets/php-includes/test/test-div-auth.php' ?>
            </div>
            <div class="col-lg-7" >
            <div class="card shadow mb-4" style="height: 270px;">
    <div class="card-header d-flex justify-content-between align-items-center" style="background: var(--bs-success-text-emphasis);">
        <h6 class="text-primary fw-bold m-0"><span style="color: rgb(244, 248, 244);">TEST DETAIL</span></h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <tbody>
                    <tr>
                        <td class="text-end" style="width: 200px;">Application Date</td>
                        <td><input type="text" style="margin-left: 5px;" readonly value="<?php echo $details['app_date']; ?>"></td>
                        <td class="text-end col-md-3 col-lg-4 text-nowrap">PETC OR</td>
                        <td><input id="petcValue" type="text" style="margin-left: 8px;" value="<?php echo $details['petc_or']; ?>" readonly></td>
                    </tr>
                    <tr>
                        <td class="text-end" style="width: 200px;">Purpose</td>
                        <td><input type="text" style="margin-left: 5px;" readonly value="<?php echo $details['purpose']; ?>"></td>
                        <td class="text-end col-md-3 col-lg-4 text-nowrap">Amount(PHP)</td>
                        <td><input type="text" style="margin-left: 5px;" readonly value="<?php echo $details['amount']?>"></td>
                    </tr>
                    <tr>
                        <td class="text-end" style="width: 200px;">Date Tested</td>
                       <td><input id="dateTestedInput" type="text" style="margin-left: 5px;" readonly value="<?php echo $details['date_tested']; ?>"></td>
                        <td class="text-end col-md-3 col-lg-4 text-nowrap">Reference No.</td>
                        <td><input type="text" style="margin-left: 8px;" readonly value="<?php echo $details['reference_number']; ?>"></td>
                    </tr>
         <!-- Add this inside the appropriate table row -->
         <tr>
    <td class="text-end" style="vertical-align: middle;">MVECT/Operator</td>
    <td>
        <!-- Replace the input with a dropdown -->
        <select id="mvectOperator" name="mvectOperator"  style="margin-left: 4px; width: 98%;" onchange="checkOperatorSelection()">
            <option value="" disabled selected>Select Operator</option>
            <?php
            // Assuming $operatorTypes is an array containing your operator types
            $operatorTypes = array("Operator 1", "Operator 2", "Operator 3");

            // Loop to generate options
            foreach ($operatorTypes as $operatorType) {
                $selected = ($operatorType === $details['mvect_operator']) ? 'selected' : '';
                echo '<option value="' . $operatorType . '" ' . $selected . '>' . $operatorType . '</option>';
            }
            ?>
        </select>
    </td>
    <td class="text-end col-md-3 col-lg-4 text-nowrap">Payment Date</td>
    <td><input id="paymentDateValue" type="text" style="margin-left: 8px;" value="<?php echo $details['payment_date']; ?>" readonly></td>
</tr>


                    <!-- Add more rows based on your structure -->
                </tbody>
            </table>
        </div>
    </div>
</div>
            </div>
            <!-- Add this modal at the end of your HTML body -->
<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="errorModalLabel">Error</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Please select an image to upload.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

            </div>
            <!-- Back button outside the card, positioned lower and larger with increased margin-top -->
            <div class="text-end mt-5">
    <button class="btn btn-secondary btn-lg" onclick="goBack()">Back</button>
   <!-- Confirm Button -->
<button id="confirmButton" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#confirmModal" disabled>Confirm</button>

</div>

<!-- Modal for Confirm or Cancel -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirm Action</h5>
                <button tyPe="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to confirm this action?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-Primary" onclick="confirmedAction()">Confirm</button>
            </div>
        </div>
    </div>
</div>
<td><input id="finalizeInput" type="hidden" style="margin-left: 8px;" readonly value="<?php echo $finalize ?>" readonly></td>

            </div>
            
        <?php include 'partials/footer.php'?>mvectOperatorDate
        <a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/chart.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/theme.js"></script>
<script>
    $(document).ready(function () {
        $('#carPictureModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var carPicture = button.data('car-picture');
            $('#carPicture').attr('src', carPicture);
        });
    });
</script>
<script>
   function checkOperatorSelection() {
    // Get the selected operator value
    var selectedOperator = document.getElementById('mvectOperator').value;

    // Get the "Check Test Status" button
    var checkTestStatusButton = document.getElementById('checkTestStatusButton');

    // Get the finalize value
    var finalizeValue = parseInt($("#finalizeInput").val()); // Assuming there is an input with id "finalizeInput"

    // Enable or disable the button based on whether an operator is selected and finalize is not 1
    checkTestStatusButton.disabled = (selectedOperator === "" || finalizeValue === 1);
}


    // Call the function on page load to initialize the button state
    checkOperatorSelection();
</script>

<script>
function CheckAndSaveTestStatus() {
    // Get the input values
    var hcValue = parseFloat(document.getElementById('hcInput').value) || 0;
    var coValue = parseFloat(document.getElementById('coInput').value) || 0;
    var co2Value = parseFloat(document.getElementById('co2Input').value) || 0;
    var o2Value = parseFloat(document.getElementById('o2Input').value) || 0;
    var nValue = parseFloat(document.getElementById('nInput').value) || 0;
    var kAveValue = parseFloat(document.getElementById('kAveInput').value) || 0;



    // Define safe and high-level criteria
    var safeCriteria = {
        hc: 200,
        co: 15,
        co2Min: 14,
        co2Max: 15,
        o2Min: 1,
        o2Max: 3,
        n: 1000,
        kAve: 25
    };
// Check if the values meet the criteria
// Evaluate the results

// Check the year model and fuel type to set kAve criteria
    var yearModel = parseInt(document.getElementById('yearModeValue').value) || 0;
    var fuelType = document.getElementById('fuelTypeValue').value;
    var mvType = document.getElementById('mvTypeValue').value;

    console.log("Before MV type -", mvType);
    console.log("Before Year Model -", yearModel);
    console.log("Before Fuel type -", fuelType);
    console.log("Before - CO:", safeCriteria.co);
console.log("Before - HC:", safeCriteria.hc);


if (((yearModel >= 1900 && yearModel <= 2007) || (yearModel >= 2008 && yearModel <= 2017)) && (fuelType === 'Diesel - None Turbo' || fuelType === 'Turbo Diesel')) {
        safeCriteria.kAve = 2.5;
    } else if ((yearModel >= 2008 && yearModel <= 2017) && (fuelType === 'Diesel - None Turbo' || fuelType === 'Turbo Diesel')) {
        safeCriteria.kAve = 2.0;
    } else if (yearModel >= 2018 && (fuelType === 'Diesel - None Turbo' || fuelType === 'Turbo Diesel')) {
        safeCriteria.kAve = 1.0;
    } else if ((yearModel >= 1900 && yearModel <= 2007) && (fuelType === 'Gasoline' || fuelType === 'LPG')) {
        safeCriteria.co = 3.5;
        safeCriteria.hc = 600;
    } else if ((yearModel >= 2008 && yearModel <= 2017) && (fuelType === 'Gasoline' || fuelType === 'LPG')) {
        safeCriteria.co = 0.50;
        safeCriteria.hc = 250;
    } else if (yearModel >= 2017 && (fuelType === 'Gasoline' || fuelType === 'LPG')) {
        safeCriteria.co = 0.25;
        safeCriteria.hc = 100;
    } 
    
    
if ((yearModel >= 1900 && yearModel <= 2011) && (mvType === 'Mopeds (0-49 cc)' || mvType === 'Motorcycle w/ side car' || mvType === 'Motorcycle w/o side car' || mvType === 'Non-conventional MC (Car)' || mvType === 'Tricycle')) {
        safeCriteria.co = 4.50;
        safeCriteria.hc = 6000;
} else if ((yearModel >= 2012 && yearModel <= 2017) && (mvType === 'Mopeds (0-49 cc)' || mvType === 'Motorcycle w/ side car' || mvType === 'Motorcycle w/o side car' || mvType === 'Non-conventional MC (Car)' || mvType === 'Tricycle')) {
        safeCriteria.co = 3.50;
        safeCriteria.hc = 4500;
} else if ((yearModel >= 2018) && (mvType === 'Mopeds (0-49 cc)' || mvType === 'Motorcycle w/ side car' || mvType === 'Motorcycle w/o side car' || mvType === 'Non-conventional MC (Car)' || mvType === 'Tricycle')) {
        safeCriteria.co = 2.50;
        safeCriteria.hc = 1000;
}



    console.log("After MV type -", mvType);
    console.log("After Year Model -", yearModel);
    console.log("After Fuel type -", fuelType);
    console.log("After - CO:", safeCriteria.co);
console.log("After - HC:", safeCriteria.hc);


var pass = true;
var result = {};

if (hcValue > safeCriteria.hc) {
    pass = false;
    result.hc = "HC concentration is too high";
}

if (coValue > safeCriteria.co) {
    pass = false;
    result.co = "CO concentration is too high";
}

if (co2Value < safeCriteria.co2Min || co2Value > safeCriteria.co2Max) {
    pass = false;
    result.co2 = "CO2 concentration is outside the safe range";
}

if (o2Value < safeCriteria.o2Min || o2Value > safeCriteria.o2Max) {
    pass = false;
    result.o2 = "O2 concentration is outside the safe range";
}

if (nValue > safeCriteria.n) {
    pass = false;
    result.n = "Nitrogen concentration is too high";
}

if (kAveValue > safeCriteria.kAve) {
    pass = false;
    result.kAve = "K-AVE concentration is too high";
}


var uploadButton = document.getElementById('uploadButton');
var imageUpload = document.getElementById('imageUpload');
var testedElement = document.getElementById('tested');
var finalizeValue = parseInt($("#finalizeInput").val());

if (pass) {
    testedElement.checked = true; // Check the checkbox if the test passed
    imageUpload.disabled = false;
    uploadButton.disabled = false;  // Disable the file input if the test passed
} else {
    testedElement.checked = false; // Uncheck the checkbox if the test failed
    imageUpload.disabled = true; // Enable the file input if the test failed
    uploadButton.disabled = true;
}


// Display the results
var statusText = document.getElementById('testStatusText');
var statusElement = document.getElementById('testStatus');

if (pass) {
    statusElement.value = "PASSED";
    statusElement.style.color = "green";
    statusText.innerHTML = "";  // Clear the content when the test is passed
} else {
    statusElement.value = "FAILED ";
    statusElement.style.color = "red";

    var errorMessage = "High Levels Detected:\n";
    for (var key in result) {
        if (result.hasOwnProperty(key)) {
            errorMessage += result[key] + "\n";
        }
    }

    statusText.innerHTML = errorMessage;
    statusText.style.color = "red";
}


    SaveTestStatus(pass);
    updateConfirmButton();

}



function SaveTestStatus(pass) {
    // Get the selected MVect operator
    var selectedOperator = document.getElementById('mvectOperator').value;

    // Get the input values
    var hcValue = parseFloat(document.getElementById('hcInput').value) || 0;
    var coValue = parseFloat(document.getElementById('coInput').value) || 0;
    var co2Value = parseFloat(document.getElementById('co2Input').value) || 0;
    var o2Value = parseFloat(document.getElementById('o2Input').value) || 0;
    var nValue = parseFloat(document.getElementById('nInput').value) || 0;
    var rpmValue = parseFloat(document.getElementById('rpmInput').value) || 0;
    var kAveValue = parseFloat(document.getElementById('kAveInput').value) || 0;
 // Save the current date and time
 var currentDate = new Date();
    var formattedDate = currentDate.toISOString().slice(0, 19).replace("T", " ");

    // Update the date_tested input field
    document.getElementById('dateTestedInput').value = formattedDate;

    // Create an object to store the test results and MVect operator
    var testResults = {
        hc: hcValue,
        co: coValue,
        co2: co2Value,
        o2: o2Value,
        n: nValue,
        rpm: rpmValue,
        kAve: kAveValue
    };

    // Determine the testing status based on the pass variable
    var testingStatus = pass ? 1 : 2;
    var tested = pass ? 1 : 0;

      // Send the test results, MVect operator, and testing status to the server using AJAX
    $.ajax({
        type: "POST",
        url: "update_test_result.php", // Specify the path to your PHP script
        data: {
            bookingId: <?php echo $bookingId; ?>, // Pass the bookingId to identify the record
            mvectOperator: selectedOperator,
            testResults: testResults,
            testingStatus: testingStatus,
            tested: tested,
            dateTested: formattedDate // Include the updated date_tested in the data
        },
        success: function (response) {
            // Handle the response from the server (if needed)
            console.log(response);
 updateConfirmButton();
        },
        error: function (xhr, status, error) {
            // Handle errors (if needed)
            console.error(xhr.responseText);
        }
    });
   
}
</script>


<script>
function uploadImage() {
    // Check if the file input has a value
    var fileInput = document.getElementById('imageUpload');
    if (!fileInput.value) {
        // Show an error message in a Bootstrap modal
        $('#errorModal').modal('show');
        return;
    }

    var formData = new FormData(document.getElementById('imageUploadForm'));

    $.ajax({
        type: "POST",
        url: "upload_image.php",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            // Handle the response from the server
            
            var responseData = JSON.parse(response);
            if (responseData.success) {
             // Update the image on the page
        $('#imageElement').attr('src', responseData.imagePath);

// Update other elements based on the fetched values
// $('#valid').prop('checked', responseData.values.Valid);
$('#uploaded').prop('checked', responseData.values.Uploaded);
$('#uploadedImage').prop('checked', responseData.values.Uploaded_image);

// Enable the checkbox if the image is uploaded
$('#valid').prop('disabled', false);

                // Update recordStatusInput value and style based on fetched values
                // var recordStatusInput = document.getElementById('recordStatus');
//                 console.log('Before setting recordStatusInput value:', recordStatusInput.value);
// recordStatusInput.value = responseData.values.record_status === 1 ? 'FAILED' : 'CERTIFIED';
// console.log('After setting recordStatusInput value:', recordStatusInput.value);

//                 recordStatusInput.style.color = responseData.values.record_status === 1 ? 'failed' : 'green';
            } else {
                 // Show an error message in a Bootstrap modal
        $('#errorModal').modal('show');
        // Disable the checkbox if the image upload was not successful
        $('#valid').prop('disabled', true);
            }
            updateConfirmButton();
        },
        error: function (xhr, status, error) {
            console.error(xhr.responseText);
            // Show an error message in a Bootstrap modal
            $('#errorModal').modal('show');
        }
    });
}





function updateImage() {
    // Code to update the displayed image on the page if needed
    // For example, you can reload the image using AJAX or other methods
    // You can use the same logic as in your existing code to display the updated image
    // Assuming $bookingId is the unique identifier for the record
    var imageQuery = "SELECT vehicle_img, Uploaded, Valid, Uploaded_Image FROM test_result WHERE booking_id = '<?php echo $bookingId; ?>'";
    $.ajax({
        type: "GET",
        url: "fetch_image.php", // Replace with the actual file
        data: { bookingId: '<?php echo $bookingId; ?>' },
        success: function (data) {
            // Update the image element with the new image source
            var imagePath = data.imagePath;
            var uploaded = data.uploaded;
            var valid = data.valid;
            var uploadedImage = data.uploadedImage;
            
            $('#imageElement').attr('src', imagePath);

           
        },
        error: function (xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}



</script>
<script>
    // Call updateImage() once the DOM is ready
    $(document).ready(function () {
        updateImage();
    });
</script>

<script>
 // JavaScript function to handle the confirm action
function confirmAction() {
    // Open the confirmation modal
    $('#confirmModal').modal('show');
}

// JavaScript function to handle the confirmed action
function confirmedAction() {
    // Add your confirmation logic here
    // alert("Confirmed!");  
    // Replace this with your actual confirmation logic

    // If you want to update fields in the database, make an AJAX request here
    updateDatabaseFields();

    // Close the modal
    $('#confirmModal').modal('hide');
}

 // JavaScript function to check conditions and enable/disable the "Confirm" button
function updateConfirmButton() {
    // Get the bookingId from the PHP variable
    var bookingId = <?php echo $bookingId; ?>;

    // Make an AJAX request to the check_conditions.php script
    $.ajax({
        type: "POST",
        url: "test_confirm_button_conditions.php",
        data: { bookingId: bookingId },
        dataType: "json",
        success: function (response) {
            // Check conditions and disable/enable the "Confirm" button accordingly
            if (response.Tested === 1 && response.Valid === 1 && response.Uploaded === 1 && response.Uploaded_Image === 1 && response.finalize !== 1) {
                $('#confirmButton').prop('disabled', false);  // Enable the button
            } else {
                $('#confirmButton').prop('disabled', true);   // Disable the button
            }
        },
        error: function (xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}


    // Call the function to initialize the button state when the document is ready
    $(document).ready(function () {
        updateConfirmButton();
    });



    // JavaScript function to handle the confirm action
function confirmAction() {
    // Add your confirmation logic here
    alert("Confirmed!");  // Replace this with your actual confirmation logic

    // If you want to update fields in the database, make an AJAX request here
    updateDatabaseFields();
}

// JavaScript function to update fields in the database after confirmation
function updateDatabaseFields() {
    // Get the bookingId from the PHP variable
    var bookingId = <?php echo $bookingId; ?>;

    // Make an AJAX request to update fields in the car_emission table
    // $.ajax({
    //     type: "POST",
    //     url: "update_car_emission.php", // Replace with the actual file
    //     data: {
    //         bookingId: bookingId,
    //         // Add any other fields you want to update in the car_emission table
    //     },
    //     success: function (response) {
    //         // Handle the response from the server (if needed)
    //         console.log(response);
    //     },
    //     error: function (xhr, status, error) {
    //         // Handle errors (if needed)
    //         console.error(xhr.responseText);
    //     }
    // });

    $(document).ready(function () {
    // Call the function on page load to initialize the button state
    checkOperatorSelection();

    $.ajax({
        type: "POST",
        url: "update_test_details_and_status.php", // Replace with the actual file
        data: {
            bookingId: bookingId,
            // Add any other fields you want to update in the test_result table
        },
        success: function (response) {
    // Handle the response from the server (if needed)
    console.log(response);

    // Parse the JSON response
    var updatedData = JSON.parse(response);

    // Update elements
    $("#authCode").val(updatedData.authCode);
    $("#paymentDateValue").val(updatedData.dateTested);
    $("#petcValue").val(updatedData.petcValue);
    $("#cecValue").val(updatedData.cecNumber);

    // Update cecValue for the check condition on the printDetails button
    var cecValue = updatedData.cecNumber;
    // Disable the printDetails button if cecValue is zero
    $('#printDetails').prop('disabled', cecValue === 0);

    // Check if finalize is equal to 1, then disable the checkTestStatusButton
    if (updatedData.finalize === 1) {
        $('#checkTestStatusButton').prop('disabled', true);
    }

    // Reload the page without notifying the user
    location.reload(true);
},
        error: function (xhr, status, error) {
            // Handle errors (if needed)
            console.error(xhr.responseText);
        }
    });
});



}

</script>

<script>
    $(document).ready(function() {
        // Function to update the record status based on isChecked value
        function updateRecordStatus(isChecked) {
            var bookingId = <?php echo $bookingId; ?>;
            // Make an AJAX request to update the database
            $.ajax({
                type: 'POST',
                url: 'fetch_valid.php', // Replace with the actual URL to your server-side script
                data: {
                    bookingId: bookingId,
                    isChecked: isChecked
                    // Add any other data you need to send to the server
                },
                success: function(response) {
                    // Handle the response from the server if needed
                    console.log(response);
                    var data = JSON.parse(response);

                    // Call the updateConfirmButton function to update the "Confirm" button
                    updateConfirmButton();

                    // Update the content of the h6 element with the new record status
                    $('#recordStatus').text('Record Status: ' + data.recordStatus);

                    // Update recordStatusInput value and style based on fetched values
                    var recordStatusInput = document.getElementById('recordStatus');
                    console.log('Before setting recordStatusInput value:', recordStatusInput.value);
                    recordStatusInput.value = data.recordStatus ==  1 ? 'CERTIFIED' : 'FAILED';
                    console.log('After setting recordStatusInput value:', recordStatusInput.value);

                    recordStatusInput.style.color = data.recordStatus == 1 ? 'green' : 'red';
                },
                error: function(xhr, status, error) {
                    // Handle errors if any
                    console.error(xhr.responseText);
                }
            });
        }

        // Attach a change event listener to the checkbox
        $('#valid').change(function() {
            // Get the checked status of the checkbox
            var isChecked = $(this).prop('checked') ? 1 : 0;
            updateRecordStatus(isChecked);
        });

        // Attach an uncheck event listener to the checkbox
        $('#valid').on('uncheck', function() {
            // Update record status when the checkbox is unchecked
            updateRecordStatus(0);
        });
    });
</script>





<?php
function getTestStatusHTML($status) {
    switch ($status) {
        case 0:
            return '<input id="testStatus" class="align-items-start" type="text" style="margin-left: 15px; color: orange;" value="PENDING" readonly>';
        case 1:
            return '<input id="testStatus" class="align-items-start" type="text" style="margin-left: 15px; color: green;" value="PASSED" readonly>';
        case 2:
            return '<input id="testStatus" class="align-items-start" type="text" style="margin-left: 15px; color: red;" value="FAILED" readonly>';
        default:
            return '<input id="testStatus" class="align-items-start" type="text" style="margin-left: 15px; color: gray;" value="UNKNOWN" readonly>';
    }
}

function getRecordStatusHTML($record) {
    switch ($record) {
        case 0:
            return '<input id="recordStatus" class="align-items-start" type="text" style="margin-left: 15px; color: orange;" value="FOR REVIEW" readonly>';
        case 1:
            return '<input id="recordStatus" class="align-items-start" type="text" style="margin-left: 15px; color: green;" value="CERTIFIED" readonly>';
        case 2:
            return '<input id="recordStatus" class="align-items-start" type="text" style="margin-left: 15px; color: red;" value="FAILED" readonly>';
        default:
            return '<input id="recordStatus" class="align-items-start" type="text" style="margin-left: 15px; color: gray;" value="UNKNOWN" readonly>';
    }
}
?>



</body>

</html>