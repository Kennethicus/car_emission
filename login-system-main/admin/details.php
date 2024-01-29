<?php
// Include necessary files and start the session
session_start();
include("../connect/connection.php");

// Check if the admin is logged in, if not, redirect to the login page
if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}

// Fetch admin details based on the session information
$username = $_SESSION['admin'];
$query = "SELECT * FROM smurf_admin WHERE username = ?";
$stmt = $connect->prepare($query);

if (!$stmt) {
    // Handle query preparation error
    echo "Query preparation error!";
    exit();
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $admin = $result->fetch_assoc();
} else {
    // Handle the case where admin details are not found
    echo "Admin details not found!";
    exit();
}

// Check if the id and ticketId parameters are set
if (isset($_GET['id']) && isset($_GET['ticketId'])) {
    $reserve_id = $_GET['id'];
    $ticketId = $_GET['ticketId'];

    // Fetch car emission details based on the provided id parameter
    $detailsQuery = "SELECT * FROM car_emission WHERE id = ?";
    $detailsStmt = $connect->prepare($detailsQuery);

    if (!$detailsStmt) {
        // Handle query preparation error
        echo "Query preparation error!";
        exit();
    }

    $detailsStmt->bind_param("i", $reserve_id);
    $detailsStmt->execute();
    $detailsResult = $detailsStmt->get_result();

    if ($detailsResult->num_rows === 1) {
        $details = $detailsResult->fetch_assoc();

        // Fetch user details using user_id from car_emission table
        $loginId = $details['user_id'];
        $userQuery = "SELECT `user_id`, `first_name`, `middle_name`, `last_name` FROM login WHERE user_id = ?";
        $userStmt = $connect->prepare($userQuery);

        if (!$userStmt) {
            // Handle query preparation error
            echo "Query preparation error!";
            exit();
        }

        $userStmt->bind_param("i", $loginId);
        $userStmt->execute();
        $userResult = $userStmt->get_result();

        if ($userResult->num_rows === 1) {
            $loginDetails = $userResult->fetch_assoc();
        } else {
            // Handle user details not found
            echo "User details not found!";
            exit();
        }
    } else {
        // Handle car emission details not found
        echo "Car emission details not found!";
        exit();
    }
    $carPictureDirectory = "../uploads/"; // Directory where car pictures are stored
    $orPictureDirectory = "../uploads/or_picture/";   // Directory where OR pictures are stored
    $crPictureDirectory = "../uploads/cr_picture/";   // Directory where CR pictures are stored
    
    $carPicturePath = $carPictureDirectory . $details['car_picture'];
    $orPicturePath = $orPictureDirectory . $details['vehicle_or_pic'];
    $crPicturePath = $crPictureDirectory . $details['vehicle_cr_pic'];
    

} else {
    // Handle id or ticketId parameters not provided
    echo "Reservation details not found!";
    exit();
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
    <label for="userNameInput">User:</label>
    <?php if ($loginDetails): ?>
        <input type="text" id="userNameInput" name="userNameInput" class="form-control" style="max-width: 270px; display: inline-block; margin-left: 8px;" value="<?php echo $loginDetails['first_name'] . ' ' .  $loginDetails['middle_name'] . ' ' .  $loginDetails['last_name']; ?>" disabled>
    <?php else: ?>
        <span>No account</span>
    <?php endif; ?>
</td>

                                                    <td class="text-end" style="width: 200px;">Organization<input id="organizationInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text" value="<?php echo $details['organization']; ?>" disabled></td>
                                                </tr>
                                               <tr>

                                               <td class="text-end" style="width: 200px;">
                                                Plate No. 
                                                <input id="plateNumberLetters" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text" value="<?php echo $details['plate_number']; ?>" disabled>
                                            
                                                 </td>

           
                                               
                                            
                                                    <td class="text-end">First Name<input id="firstnameInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text" value="<?php echo $details['customer_first_name']; ?>" disabled></td>
                                                </tr> 
                                                <tr>

                                                <td class="text-end">
                 <!-- Input for uploading a picture -->
                <label for="carPictureInput" class="btn btn-primary">
                Vehicle Picture
                <input type="file" id="carPictureInput" class="d-none" accept="image/*" onchange="displaySelectedPicture(this)" disabled>
                </label>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#carPictureModal" data-car-picture="../<?php echo $details['car_picture']; ?>">
        Display
    </button>
                </td>
                                                        <td class="text-end" style="width: 200px;">Middle Name<input id="middleNameInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text" value="<?php echo $details['customer_middle_name']; ?>" disabled></td>
                                                </tr>
                                            
                                                <tr>
                                            
                                                <td class="text-end">
    <!-- Input for uploading a picture -->
    <label for="CrInputPicture" class="btn btn-primary">
        CR Picture
        <input type="file" id="CrInputPicture" class="d-none" accept="image/*" onchange="displaySelectedCrPicture(this)" disabled>
    </label>
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#crPictureModal" data-cr-picture="../<?php echo $details['vehicle_cr_pic']; ?>">
        Display
    </button>
</td>




                                                
                                                    <td class="text-end" style="width: 200px;">Last Name<input id="lastNameInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text" value="<?php echo $details['customer_last_name']; ?>" disabled></td>
                                                </tr>
                                                <tr>
                                                <td class="text-end">
    <!-- Input for uploading a picture -->
    <label for="OrInputPicture" class="btn btn-primary">
        OR Picture
        <input type="file" id="OrInputPicture" class="d-none" accept="image/*" onchange="displaySelectedOrPicture(this)">
    </label>

    <!-- Button to trigger the OR picture modal -->
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#orPictureModal" data-or-picture="../<?php echo $details['vehicle_or_pic']; ?>">
        Display
    </button>  
</td>




<td class="text-end" style="width: 200px;">Address<input id="addressInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text" value="<?php echo $details['address']; ?>" disabled></td>
                                                                        
                                         

                                                </tr>   
                                                <tr>

                                                  
                                                </tr>  
                                   
                                                <tr>
                                                <td class="text-end" style="vertical-align: middle;">Vehicle CR No.<input id="vehicleCrInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text" value="<?php echo $details['vehicle_cr_no']; ?>" disabled></td>


                                                    <td class="text-end">Engine<input id="engineInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text" value="<?php echo $details['engine']; ?>" disabled></td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: right;">Vehicle OR No.<input id="vehicleOrInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text" value="<?php echo $details['vehicle_or_no']; ?>" disabled></td>
                                                    <td style="text-align: right;">Chassis<input type="text" id="chassisInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text" value="<?php echo $details['chassis']; ?>" disabled></td>
                                                </tr>
                                                <tr>
                                                <td class="text-end">First Registration Date
                                                <input id="firstRegInput" type="date" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $details['first_reg_date']; ?>" disabled>
    </td>
                                                    <td class="text-end">Make
                                                    <input name="makeInput" id="makeInput"  class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" value="<?php echo $details['make']; ?>" disabled>
                                                 
                                                    </input>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">Year Model
                                                    <input id="yearModelInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;"  value="<?php echo $details['year_model']; ?>" disabled> <!-- Adjust the width as needed -->
                                             
                                                </input>
                                                    </td>
                                                    <td class="text-end">Series
                                                    <input name="seriesInput" id="seriesInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;"  value="<?php echo $details['series']; ?>" disabled>
                                                    
                                                </input>    
                                                </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">Fuel type
                                                    <input name="fuelTypeInput" id="fuelTypeInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;"  value="<?php echo $details['fuel_type']; ?>" disabled>

                    </input>
                                                    </td>
                                                    <td class="text-end">Color
                                                    <input name="colorInput" id="colorInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;"  value="<?php echo $details['color']; ?>" disabled>
                                                 
                                                    </input>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">Purpose
                                                    <input name="purposeInput" id="purposeInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;"  value="<?php echo $details['purpose']; ?>" disabled>
                                                  
                                                    </input>
                                                    </td>
                                                <td class="text-end">
                                                    Gross Weight
                                                    <input id="grossWeightInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text"  value="<?php echo $details['gross_weight']; ?>" disabled>
                                                </td>

                                                </tr>
                                                <tr>
                                                    <td class="text-end">MV Type
                                                    <input name="mvTypeInput" id="mvTypeInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;"  value="<?php echo $details['mv_type']; ?>" disabled>
                                                 
                                                    </input>
                                                    </td>
                                                    <td class="text-end">Net Capacity<input id="netCapacityInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;" type="text"  value="<?php echo $details['net_capacity']; ?>" disabled></td>
                                                </tr>
                                                <tr>
     
                                                    <td class="text-end">MV File No.<input id="mvFileInput" type="text"class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;"  value="<?php echo $details['mv_file_no']; ?>" disabled></td>  
                                                    <td class="text-end">Region
                                                    <input name="regionInput" id="regionInput" class="form-control" style="max-width: 200px; display: inline-block; margin-left: 8px;"  value="<?php echo $details['region']; ?>" disabled>
                                               
                                                    </input>
                                                    </td>
                                                 </tr>
                                                <tr>
                                                 
         
                                                </tr>
                                                
                                                
                                              
                                              
                                                <tr>

                                                 <!-- <td class="text-end" style="width: 200px;">Ticketing ID<input type="text" style="margin-left: 5px;" ></td> -->
                                                 <td class="text-end">Classification
                                                    <input name="classification" id="classificationInput"  class="form-control" style="max-width: 230px; display: inline-block; margin-left: 8px;" type="text"  value="<?php echo $details['classification']; ?>" disabled>
                                                   
                                                    </input>
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
                <tr>
                        <th><span style="font-weight: normal !important;">Amount(PHP)</span></th>
                    </tr>   
                    <tr>
                       <td class="text-center" style="font-size: 23px;"><?php echo $details['amount']; ?></td>
                    </tr> 
               
                <tr>
                        <th><span style="font-weight: normal !important;">Payment Status</span></th>
                    </tr>
                    <tr>
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
                        <th><span style="font-weight: normal !important;">Book Status</span></th>
                    </tr>
                    <tr>
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
                        <th><span style="font-weight: normal !important;">Mode of Payment I</span></th>
                    </tr>   
                    <tr>
                       <td class="text-center" style="font-size: 23px;"><?php echo strtoupper($details['paymentMethod']); ?></td>
                    </tr>   
             <tr>
                        <th><span style="font-weight: normal !important;">Amount Paid I</span></th>
                    </tr>   
                    <tr>
                    <td class="text-center" style="font-size: 23px;"><?php echo !empty($details['payAmount1']) ? $details['payAmount1'] : '0'; ?></td>
                    </tr> 
                    <?php if ($details['payAmount2'] !== null): ?>
                    <tr>
                        <th><span style="font-weight: normal !important;">Mode of Payment II</span></th>
                    </tr>   
                    <tr>
                       <td class="text-center" style="font-size: 23px;"><?php echo strtoupper($details['paymentMethod2']); ?></td>
                    </tr>   
                    <tr>
                        <th><span style="font-weight: normal !important;">Amount Paid II</span></th>
                    </tr>   
                    <tr>
                    <td class="text-center" style="font-size: 23px;"><?php echo !empty($details['payAmount2']) ? $details['payAmount2'] : '0'; ?></td>
                    </tr> 
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>




                            <div class="card" style="height: 220px; margin-bottom: 20px;">
    <div class="card-header" style="background: var(--bs-success-text-emphasis);">
        <h6 class="mb-0" style="text-align: center; color: var(--bs-body-bg); font-weight: bold; font-size: 16px;">ACTIONS</h6>
    </div>
    <div class="card-body" style="text-align: center; padding-top: 10px;">

<?php if ($details['status'] !== 'canceled' && $details['status'] !== 'doned') { ?>
    <button class="btn btn-primary m-2" onclick="editFunction()">Edit</button>
    <a href="review.php?id=<?php echo $reserve_id; ?>&ticketId=<?php echo $ticketId; ?>" class="btn btn-info m-2">Review I</a>
    <?php if (!($details['paymentStatus'] == 'pending' || $details['paymentStatus'] == 'unpaid' || ($details['paymentStatus'] == 'fully paid' && $details['return_switch_1'] == '2' &&  $details['return_switch_2'] == ''))) : ?>
    <a href="review-full-payment.php?id=<?php echo $reserve_id; ?>&ticketId=<?php echo $ticketId; ?>" class="btn btn-info m-2">Review II</a>
<?php endif; ?>


    <a href="test.php?id=<?php echo $reserve_id; ?>&ticketId=<?php echo $ticketId; ?>" class="btn btn-success m-2" onclick="testFunction()">Test</a>
    <button class="btn btn-danger m-2" onclick="cancelBookFunction()">Cancel Book</button>
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


<!-- Modal for Car Picture -->
<div class="modal fade" id="carPictureModal" tabindex="-1" aria-labelledby="carPictureModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="carPictureModalLabel">Car Picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="carPicture" class="img-fluid" alt="Car Picture">
            </div>
        </div>
    </div>
</div>


<!-- Modal for CR Picture -->
<div class="modal fade" id="crPictureModal" tabindex="-1" aria-labelledby="crPictureModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="crPictureModalLabel">CR Picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="crPicture" class="img-fluid" alt="CR Picture">
            </div>
        </div>
    </div>
</div>


<!-- Modal for OR Picture -->
<div class="modal fade" id="orPictureModal" tabindex="-1" aria-labelledby="orPictureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orPictureModalLabel">OR Picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="orPicture" class="img-fluid" alt="OR Picture">
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
    $(document).ready(function () {
        $('#carPictureModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var carPicture = button.data('car-picture');
            $('#carPicture').attr('src', carPicture);
        });
    });
</script>

<!-- Add this script at the end of your HTML, after including jQuery and Bootstrap JS -->


<script>
$(document).ready(function () {
    // OR Picture Modal
    $('#orPictureModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var orPicture = button.data('or-picture');
        $('#orPicture').attr('src', orPicture);
    });
});

</script>

<script>
   // CR Picture Modal
   $('#crPictureModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var crPicture = button.data('cr-picture');
        $('#crPicture').attr('src', crPicture);
    });
</script>

<script>
    // JavaScript function to handle the "Test" button click
    function testFunction() {
        var reserveId = <?php echo $reserve_id; ?>;
        var ticketId = <?php echo $ticketId; ?>;
        
        // Make an AJAX request to the PHP script for inserting test results
        $.ajax({
            url: 'insert_test_result.php', // Replace with the actual path to your PHP script
            method: 'POST',
            data: {
                reserveId: reserveId,
                ticketId: ticketId
            },
            success: function(response) {
                // Handle the response if needed
                console.log(response);
            },
            error: function(error) {
                // Handle errors if needed
                console.error(error);
            }
        });
    }
</script>


</body>

</html>