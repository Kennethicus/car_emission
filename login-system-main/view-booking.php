<?php
session_start();
include("partials/navbar.php");
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
} else {
    // If 'id' parameter is not set, redirect to the schedule status page
    header('Location: schedule-status.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>View Booking</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
      .form-group {
            margin-bottom: 10px; /* Remove the default bottom margin */
            display: flex;
            align-items: center; /* Align label and input vertically */
        }

        .form-group label {
            flex: 1; /* Make label take available space */
            padding-right: 10px; /* Add some right padding to create space between label and input */
            text-align: right; /* Right-align the label text */
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            flex: 2; /* Make input take more space */
        }

        #viewPictureLink {
            margin-left: 10px; /* Adjust left margin for spacing */
        }

        

    </style>
</head>

<body class="bg-light">
    <div class="container py-5">
        <h2>View Booking for <?php echo $customerDetails['first_name'] . ' ' . $customerDetails['last_name']; ?></h2>

        <div class="row">

            <div class="col-md-6">

                    <div class="form-group">
                    <label for="ticketing_id"> Ticketing ID </label>
                    <input type="text" name="ticketing_id" id="ticketing_id" class="form-control" value="<?php echo $bookingDetails['ticketing_id']; ?>" readonly>
                    </div>
            
                    <div class="form-group">
                    <label for="plate_num"> Plate No. </label>
                    <input type="text" name="plate_num" id="plate_num" class="form-control" value="<?php echo $bookingDetails['plate_number']; ?>" readonly>
                    </div>
<!-- d-flex justify-content-end -->
                    <div class="form-group d-flex">
                    <label for="viewPictureLink"> Car Picture </label>
    <a href="#" id="viewPictureLink" class="btn btn-secondary">View Picture</a>
</div>

                
                <div class="modal fade" id="carPictureModal" tabindex="-1" role="dialog" aria-labelledby="carPictureModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="carPictureModalLabel">Car Picture</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img src="<?php echo $bookingDetails['car_picture']; ?>" alt="Car Picture" class="img-fluid">
            </div>
        </div>
    </div>
</div>
                    <div class="form-group">
                    <label for="appDate">Application Date</label>
                    <input type="datetime-local" name="appDate" id="appDate" class="form-control" value="<?php echo $bookingDetails['app_date']; ?>" readonly>
                    </div>

                    <div class="form-group">
                    <label for="vehicleCrNo">Vehicle CR No.</label>
                    <input type="text" name="vehicleCrNo" id="vehicleCrNo" class="form-control" value="<?php echo $bookingDetails['vehicle_cr_no']; ?>" readonly>
                    </div>

                    
                    <div class="form-group">
                    <label for="vehicleOrNo">Vehicle OR No.</label>
                    <input type="text" name="vehicleOrNo" id="vehicleOrNo" class="form-control" value="<?php echo $bookingDetails['vehicle_or_no']; ?>" readonly>
                    </div>

                    <div class="form-group">
                    <label for="firstRegDate">First Registration Date</label>
                    <input type="date" name="firstRegDate" id="firstRegDate" class="form-control" value="<?php echo $bookingDetails['first_reg_date']; ?>" readonly>
                    </div>

                    
                    <div class="form-group">
                    <label for="yearModel">Year Model</label>
                    <input type="text" name="yearModel" id="yearModel" class="form-control" value="<?php echo $bookingDetails['year_model']; ?>" readonly>
                    </div>

                    <div class="form-group">
                    <label for="fuelType">Fuel Type</label>
                    <input type="text" name="fuelType" id="fuelType" class="form-control" value="<?php echo $bookingDetails['fuel_type']; ?>" readonly>
                        <!-- Populate options dynamically based on your requirements -->
                    </div>

                    <div class="form-group">
                    <label for="purpose">Purpose</label>
                    <input name="purpose" id="purpose" class="form-control" value="<?php echo $bookingDetails['purpose']; ?>" readonly>
                        <!-- Populate options dynamically based on your requirements -->
                    </div>

                    <div class="form-group">
                    <label for="mvType">MV Type</label>
                    <input name="mvType" id="mvType" class="form-control" value="<?php echo $bookingDetails['mv_type']; ?>" readonly>
                        <!-- Populate options dynamically based on your requirements -->
                    </div>

                    
                    <div class="form-group">
                    <label for="region">Region</label>
                    <input name="region" id="region" class="form-control" value="<?php echo $bookingDetails['region']; ?>" readonly>
                        <!-- Populate options dynamically based on your requirements -->
                    </div>

                    <div class="form-group">
                    <label for="mvFileNo">MV File No.</label>
                    <input type="text" name="mvFileNo" id="mvFileNo" class="form-control" value="<?php echo $bookingDetails['mv_file_no']; ?>" readonly>
                    </div>

                    <div class="form-group">
                    <label for="classification">Classification</label>
                    <input name="classification" id="classification" class="form-control" value="<?php echo $bookingDetails['classification']; ?>" readonly>
              
                    </div>

                    
                    <!-- <div class="form-group">
                    <label for="paymentDate">Payment Date</label>
                    <input type="datetime-local" name="paymentDate" id="paymentDate" class="form-control" value="<?php echo $bookingDetails['payment_date']; ?>" readonly>
                    </div> -->

                    <!-- <div class="form-group">
                    <label for="petcOr">PETC OR</label>
                    <input type="text" name="petcOr" id="petcOr" class="form-control" value="<?php echo $bookingDetails['petc_or']; ?>" readonly>
                    </div> -->

                    <div class="form-group">
                    <label for="amount">Amount</label>
                    <div class="input-group-prepend">
                     <span class="input-group-text">₱</span>
                    </div>
                    <input type="text" name="amount" id="amount" class="form-control" value="<?php echo $bookingDetails['amount']; ?>" readonly>
                    </div>
                <!-- Add more details as needed -->


          

                         </div>

            <div class="col-md-6">

                    <div class="form-group">
                    <label for="organization">Organization </label>
                    <input type="text" name="organization" id="organization" class="form-control" value="<?php echo $bookingDetails['organization']; ?>" readonly>
                    </div>

                    <div class="form-group">
                    <label for="last_name"> Last name </label>
                    <input type="text" name="customerLastName" id="last_name" class="form-control" value="<?php echo $bookingDetails['customer_last_name']; ?>" readonly>
                    </div>

                    <div class="form-group">
                    <label for="middle_name"> Middle name </label>
                    <input type="text" name="customerMiddleName" id="middle_name" class="form-control" value="<?php echo $bookingDetails['customer_middle_name']; ?>" readonly>
                    </div>  

                    <div class="form-group">
                    <label for="first_name"> First name </label>
                    <input type="text" name="customerFirstName" id="first_name" class="form-control" value="<?php echo $bookingDetails['customer_first_name']; ?>" readonly>
                    </div>

                    <div class="form-group">
                    <label for="address">Address</label>
                    <input type="type" name="customerAddress" id="address" class="form-control"  value="<?php echo $bookingDetails['address']; ?>" readonly>
                    </div>

                    <div class="form-group">
                    <label for="engine">Engine</label>
                    <input type="text" name="engine" id="engine" class="form-control" value="<?php echo $bookingDetails['engine']; ?>" readonly>
                    </div>

                    <div class="form-group">
                    <label for="chassis">Chassis</label>
                    <input type="text" name="chassis" id="chassis" class="form-control" value="<?php echo $bookingDetails['chassis']; ?>" readonly>
                    </div>

                    <div class="form-group">
                    <label for="make">Make</label>
                    <input name="make" id="make" class="form-control" value="<?php echo $bookingDetails['make']; ?>" readonly>
                        <!-- Populate options dynamically based on your requirements -->
                    </div>

                    
                    <div class="form-group">
                    <label for="series">Series</label>
                    <input name="series" id="series" class="form-control" value="<?php echo $bookingDetails['series']; ?>" readonly>
                        <!-- Populate options dynamically based on your requirements -->
                  </div>
                    <div class="form-group">
                    <label for="color">Color</label>
                    <input name="color" id="color" class="form-control" value="<?php echo $bookingDetails['color']; ?>" readonly>
                        <!-- Populate options dynamically based on your requirements -->
                    </div>
                    <div class="form-group">
                    <label for="grossWeight">Gross Weight</label>
                    <input type="text" name="grossWeight" id="grossWeight" class="form-control" value="<?php echo $bookingDetails['gross_weight']; ?>" readonly>
                    </div>
                  
                    <div class="form-group">
                    <label for="netCapacity">Net Capacity</label>
                    <input type="text" name="netCapacity" id="netCapacity" class="form-control" value="<?php echo $bookingDetails['net_capacity']; ?>" readonly>
                    </div>
                  
                    <!-- <div class="form-group">
                    <label for="cecNumber">CEC Number</label>
                    <input type="text" name="cecNumber" id="cecNumber" class="form-control" value="<?php echo $bookingDetails['cec_number']; ?>" readonly>
                    </div> -->

                    <!-- <div class="form-group">
                    <label for="mvectOperator">MVECT/Operator</label>
                    <input type="text" name="mvectOperator" id="mvectOperator" class="form-control" value="<?php echo $bookingDetails['cec_number']; ?>" readonly>
                    </div> -->

                    <div class="col-md-13">
    <!-- ... (additional form fields) ... -->
<!-- ... (previous code) ... -->

<div class="col-md-13">
    <!-- ... (additional form fields) ... -->
    
    <div class="d-flex justify-content-end mt-md-5 mt-3">
    <a href="schedule-status.php" class="btn btn-primary mr-2">Back to Schedule Status</a>
    <?php if ($bookingDetails['status'] !== 'doned' && $bookingDetails['status'] !== 'canceled'): ?>
        <a href="edit-booking.php?id=<?php echo $bookingDetails['id']; ?>" class="btn btn-warning">Edit Booking</a>
    <?php endif; ?>
</div>

    
</div>

<!-- ... (remaining code) ... -->


            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script>
    // Wait for the document to be ready
    $(document).ready(function () {
        // Attach a click event to the "View Picture" link to show the modal
        $('#viewPictureLink').on('click', function (e) {
            e.preventDefault();
            $('#carPictureModal').modal('show');
        });
    });
</script>


<style>
    /* Add a CSS class to control visibility */
    .visible {
        display: block !important;
    }

    #carPicture {
        /* Hide the car picture initially */
        display: none;
    }
</style>


</body>

</html>
