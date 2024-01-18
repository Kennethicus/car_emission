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

<?php include 'partials/test-head.php' ?>

<body id="page-top">
    <div id="wrapper">
        <?php include 'partials/nav.php'?>
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <?php include 'partials/nav-2.php' ?>
                <!-- Display the details content here -->
                <div class="container mt-3">
                <div class="d-sm-flex justify-content-between align-items-center mb-4">
                        <h4 class="text-dark mb-0" style="--bs-body-font-size: 9rem;--bs-body-font-weight: normal;">Server Date -&nbsp;</h4>
                        <h4 class="text-dark mb-0" style="--bs-body-font-size: 9rem;--bs-body-font-weight: normal;margin-right: auto;margin-top: 2px;">Server Date&nbsp;&nbsp;</h4>
                    </div>
                    
                    <div class="row">
                    <div class="col-md-6 col-xl-3 col-xxl-5 mb-4">                               
    <ul class="">
        <li class="list-group-item text-center" style="margin-top: 3px; margin-bottom: 3px;">
            <button class="btn btn-primary" type="button" style="background: rgb(48,120,42); ">
                <i class="fas fa-camera" style="font-size: 44px; "></i>
            </button>

            <button class="btn btn-primary" type="button" style="background: rgb(48,120,42); margin-left: 15px; margin-right: 15px;">
                <i class="fas fa-print" style="font-size: 44px;"></i>
            </button>

            <button class="btn btn-primary" type="button" style="background: rgb(48,120,42);">
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

                            <h6 class="text-dark justify-content-end mb-0" style="--bs-body-font-size: 9rem;--bs-body-font-weight: normal;margin-top: 10px;margin-left: 22px;">Record Status<input class="align-items-start" type="text" style="margin-left: 15px;" readonly=""></h6>
                        </div>
                        
                        <?php include 'assets/php-includes/test/test-div-form.php' ?>
                        <div class="col-lg-3">

                        <!-- <div class="col-lg-5 col-xl-4 col-xxl-4"> -->
                        <div class="">
                      
                        <div class="card shadow mb-4">
    <div class="card-header d-flex justify-content-between align-items-center" style="background: var(--bs-success-text-emphasis);">
        <h6 class="text-primary fw-bold m-0" style="background: Transparent;"><span style="color: rgb(244, 248, 244);">IMAGE</span></h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <form action="upload_image.php" method="post" enctype="multipart/form-data">
                <label for="imageUpload">Upload Image:</label>
                <input type="file" name="imageUpload" id="imageUpload" accept="image/*" required>
                <input type="hidden" name="bookingId" value="<?php echo $bookingId; ?>">
                <button type="submit" name="submit">Upload</button>
            </form>

            <table class="table">
                <thead>
                    <tr>
                        <td class="text-center" style="border: 0;">
                            <?php
                            // Assuming $bookingId is the unique identifier for the record
                            $imageQuery = "SELECT vehicle_img FROM test_result WHERE booking_id = '$bookingId'";
                            $imageResult = $connect->query($imageQuery);

                            if ($imageResult->num_rows === 1) {
                                $imageData = $imageResult->fetch_assoc();
                                $imagePath = $imageData['vehicle_img'];

                                // Path to placeholder image if the actual image is not available
                                $placeholderPath = "assets/img/done.png";

                                echo '<img src="' . ($imagePath ? $imagePath : $placeholderPath) . '" alt="Image" style="max-width: 100%; height: 100px;">';
                            } else {
                                echo "Image data not found!";
                            }
                            ?>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <!-- Add more rows based on your table structure if needed -->
                </tbody>
            </table>
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

                            <div class="card" style="height: auto;">
    <div class="card-header" style="background: var(--bs-success-text-emphasis);">
        <h5 class="mb-0" style="text-align: center; color: var(--bs-body-bg); font-weight: bold; font-size: 16px;">CAPPING FOR FUEL TYPE</h5>
    </div>
    <div class="card-body" style="text-align: center; color: var(--bs-emphasis-color);">
        <div class="d-flex justify-content-center">
            <div class="mx-2">
                <small style="color: var(--bs-emphasis-color);">Valid Gas:</small>
                <small style="color: var(--bs-emphasis-color); margin-left: 5px;">number</small>
            </div>
            <div class="mx-2">
                <small style="color: var(--bs-emphasis-color);">Valid Diesel:</small>
                <small style="color: var(--bs-emphasis-color); margin-left: 5px;">number</small>
            </div>
        </div>
    </div>
</div>
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
            <div class="col-lg-6">
            <?php include 'assets/php-includes/test/test-div-auth.php' ?>
            </div>
            <div class="col-lg-6">
            <div class="card shadow mb-4">
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
                        <td><input type="text" style="margin-left: 8px;" readonly></td>
                    </tr>
                    <tr>
                        <td class="text-end" style="width: 200px;">Purpose</td>
                        <td><input type="text" style="margin-left: 5px;" readonly value="<?php echo $details['ticketing_id']; ?>"></td>
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
    <td><input type="text" style="margin-left: 8px;" readonly></td>
</tr>


                    <!-- Add more rows based on your structure -->
                </tbody>
            </table>
        </div>
    </div>
</div>
            </div>
            </div>
            <!-- Back button outside the card, positioned lower and larger with increased margin-top -->
<div class="text-end mt-5">
    <button class="btn btn-secondary btn-lg" onclick="goBack()">Back</button>
</div>
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

        // Enable or disable the button based on whether an operator is selected
        checkTestStatusButton.disabled = (selectedOperator === "");
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



var testedElement =document.getElementById('tested');
if (pass) {
        testedElement.checked = true; // Check the checkbox if the test passed
    } else {
        testedElement.checked = false; // Uncheck the checkbox if the test failed
    }


// Display the results
var statusText = document.getElementById('testStatusText');
var statusElement = document.getElementById('testStatus');

if (pass) {
    statusElement.value = "Test Passed";
    statusElement.style.color = "green";
    statusText.innerHTML = "";  // Clear the content when the test is passed
} else {
    statusElement.value = "Test Failed ";
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

        },
        error: function (xhr, status, error) {
            // Handle errors (if needed)
            console.error(xhr.responseText);
        }
    });
}


       
                    

                    </script>

<?php
function getTestStatusHTML($status) {
    switch ($status) {
        case 0:
            return '<input id="testStatus" class="align-items-start" type="text" style="margin-left: 15px; color: orange;" value="Pending" readonly>';
        case 1:
            return '<input id="testStatus" class="align-items-start" type="text" style="margin-left: 15px; color: green;" value="Passed" readonly>';
        case 2:
            return '<input id="testStatus" class="align-items-start" type="text" style="margin-left: 15px; color: red;" value="Failed" readonly>';
        default:
            return '<input id="testStatus" class="align-items-start" type="text" style="margin-left: 15px; color: gray;" value="Unknown" readonly>';
    }
}
?>



</body>

</html>