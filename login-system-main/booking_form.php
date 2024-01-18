<!-- booking_form.php -->
<?php
session_start();

date_default_timezone_set('Asia/Manila');

include("partials/navbar.php");
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
<html lang="en">

<head>
    <!-- Include the Select2 CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">

<!-- Include jQuery (required for Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include the Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

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
        </style>
    <title>Booking Form</title>
</head>

<body class="bg-light">
    <div class="container py-5">
        <!-- Display event details -->
        <h2>Booking Details for <?php echo $event['title']; ?></h2>
        <p><strong>DATE:</strong> <?php echo date('M. j, Y', strtotime($event['start_datetime'])); ?></p>
        <p><strong>TIME:</strong> <?php echo date('g:ia', strtotime($event['start_datetime'])) . ' to ' . date('g:ia', strtotime($event['end_datetime'])); ?></p>
        <p><strong>SLOT:</strong> <?php echo $event['qty_of_person']; ?></p>
        <p><strong>PRICE:</strong> <?php echo $event['price_1']; ?></p>
        <p><strong>PRICE:</strong> <?php echo $event['price_2']; ?></p>
        <p><strong>PRICE:</strong> <?php echo $event['price_3']; ?></p>
        <!-- Car Emission Form -->
        <h2>Car Emission Details</h2>
        <form action="process_pending.php" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <!-- First Column -->
                    <input type="hidden" name="eventId" value="<?php echo $eventId; ?>">
                    
                    <div class="form-group">
                    <label for="plateNumber">Plate Number</label>
                    <input type="text" name="plateNumber" id="plateNumber" class="form-control" required>
                    </div>

                    <div class="form-group">
                    <label for="carPicture">Car Picture</label>
                    <input type="file" name="carPicture" id="carPicture" class="form-control-file" accept="image/*" required>
                    </div>
                    
                    <!-- <div class="form-group">
                    <label for="appDate">Application Date</label>
                    <input type="datetime-local" name="appDate" id="appDate" class="form-control" required>
                    </div> -->

                    <div class="form-group">
                    <label for="vehicleCrNo">Vehicle CR No.</label>
                    <input type="text" name="vehicleCrNo" id="vehicleCrNo" class="form-control" required>
                    </div>

                    <div class="form-group">
                    <label for="vehicleOrNo">Vehicle OR No.</label>
                    <input type="text" name="vehicleOrNo" id="vehicleOrNo" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                    <label for="firstRegDate">First Registration Date</label>
                    <input type="date" name="firstRegDate" id="firstRegDate" class="form-control" required>
                    </div>

                    <div class="form-group">
                    <label for="yearModel">Year Model</label>
                    <select name="yearModel" id="yearModel" class="form-control" >
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
                    </div>

                    <div class="form-group">
                    <label for="fuelType">Fuel Type</label>
                    <select name="fuelType" id="fuelType" class="form-control">
                        <!-- Populate options dynamically based on your requirements -->
                    </select>
                    </div>

                    

                    <div class="form-group">
                    <label for="purpose">Purpose</label>
                    <select name="purpose" id="purpose" class="form-control">
                        <!-- Populate options dynamically based on your requirements -->
                    </select>
                    </div>
                   
                  

                    

                    <div class="form-group">
                    <label for="mvType">MV Type</label>
                    <select name="mvType" id="mvType" class="form-control">
                        <!-- Populate options dynamically based on your requirements -->
                    </select>
                    </div>

               


                    <div class="form-group">
                    <label for="region">Region</label>
                    <select name="region" id="region" class="form-control">
                        <!-- Populate options dynamically based on your requirements -->
                    </select>
                    </div>

                  

                    <div class="form-group">
                    <label for="mvFileNo">MV File No.</label>
                    <input type="text" name="mvFileNo" id="mvFileNo" class="form-control">
                    </div>

                    <div class="form-group">
                    <label for="classification">Classification</label>
                    <select name="classification" id="classification" class="form-control">
                        <!-- Populate options dynamically based on your requirements -->
                    </select>
                    </div>
<!-- 
                    <div class="form-group">
                    <label for="paymentDate">Payment Date</label>
                    <input type="datetime-local" name="paymentDate" id="paymentDate" class="form-control">
                    </div> -->

                    <!-- <div class="form-group">
                    <label for="petcOr">PETC OR</label>
                    <input type="text" name="petcOr" id="petcOr" class="form-control">
                    </div> -->


                    <div class="form-group">
                    <label for="amount">Amount</label>
                    <div class="input-group-prepend">
        <span class="input-group-text">₱</span>
    </div>
    <input type="text" name="amount" id="amount" class="form-control" value="<?php echo $event['price_1']; ?>" readonly>
                    </div>
                </div>

                <div class="col-md-6">
                    <!-- Second Column -->
                    
                    <div class="form-group">
                    <label for="organization">Organization </label>
                    <input type="text" name="organization" id="organization" class="form-control">
                    </div>

                    <div class="form-group">
                    <label for="last_name"> Last name </label>
                    <input type="text" name="customerLastName" id="last_name" class="form-control" value="<?php echo $customerDetails['last_name']; ?>" readonly>
                </div>

                <div class="form-group">
                <label for="middle_name"> Middle name </label>
                <input type="text" name="customerMiddleName" id="middle_name" class="form-control" value="<?php echo $customerDetails['middle_name']; ?>" readonly>
                </div>  

                <div class="form-group">
                <label for="first_name"> First name </label>
                <input type="text" name="customerFirstName" id="first_name" class="form-control" value="<?php echo $customerDetails['first_name']; ?>" readonly>
                </div>

                    <div class="form-group">
                    <label for="address">Address</label>
                    <input type="type" name="customerAddress" id="address" class="form-control"  value="<?php echo $customerDetails['address']; ?>" readonly>
                    </div>

                    <div class="form-group">
                    <label for="engine">Engine</label>
                    <input type="text" name="engine" id="engine" class="form-control">
                    </div>

                    <div class="form-group">
                    <label for="chassis">Chassis</label>
                    <input type="text" name="chassis" id="chassis" class="form-control">
                    </div>

                    <div class="form-group">
                    <label for="make">Make</label>
                    <select name="make" id="make" class="form-control">
                        <!-- Populate options dynamically based on your requirements -->
                    </select>
                    </div>

                    <div class="form-group">
                    <label for="series">Series</label>
                    <select name="series" id="series" class="form-control">
                        <!-- Populate options dynamically based on your requirements -->
                    </select>
                    </div>

                    <div class="form-group">
                    <label for="color">Color</label>
                    <select name="color" id="color" class="form-control">
                        <!-- Populate options dynamically based on your requirements -->
                    </select>
                    </div>

                    <div class="form-group">
                    <label for="grossWeight">Gross Weight</label>
                    <input type="text" name="grossWeight" id="grossWeight" class="form-control">
                    </div>

                    <div class="form-group">
                    <label for="netCapacity">Net Capacity</label>
                    <input type="text" name="netCapacity" id="netCapacity" class="form-control">
                    </div>

                    <!-- <div class="form-group">
                    <label for="cecNumber">CEC Number</label>
                    <input type="text" name="cecNumber" id="cecNumber" class="form-control">
                    </div> -->

                    <!-- <div class="form-group">
                    <label for="mvectOperator">MVECT/Operator</label>
                    <input type="text" name="mvectOperator" id="mvectOperator" class="form-control">
                    </div> -->

                </div>
                <div class="form-group">
    <label>Payment Method</label>
    <div>
        <button type="button" class="btn btn-primary payment-method" data-value="cash">Cash</button>
        <button type="button" class="btn btn-secondary payment-method" data-value="paymaya" disabled>Paymaya (Coming Soon)</button>
        <input type="hidden" name="paymentMethod" id="paymentMethod" value="cash"> <!-- Hidden input to store the selected value -->
    </div>
</div>

            </div>

            
            <!-- Add more fields as needed -->

            <!-- Display fetched customer details -->
            <!-- <div class="row mt-3">
                <div class="col-md-6">
                    <p><strong>First Name:</strong> <?php echo $customerDetails['first_name']; ?></p>
                    <p><strong>Middle Name:</strong> <?php echo $customerDetails['middle_name']; ?></p>
                    <p><strong>Last Name:</strong> <?php echo $customerDetails['last_name']; ?></p>
                    <p><strong>Address:</strong> <?php echo $customerDetails['address']; ?></p>
                </div>
            </div> -->

            <!-- Hidden fields for customer details -->
            <input type="hidden" name="userId" value="<?php echo $customerDetails['user_id']; ?>">
            <input type="hidden" name="customerEmail" value="<?php echo $customerEmail; ?>">
            <!-- <input type="hidden" name="customerFirstName" value="<?php echo $customerDetails['first_name']; ?>">
            <input type="hidden" name="customerMiddleName" value="<?php echo $customerDetails['middle_name']; ?>">
            <input type="hidden" name="customerLastName" value="<?php echo $customerDetails['last_name']; ?>">
            <input type="hidden" name="customerAddress" value="<?php echo $customerDetails['address']; ?>"> -->
            <input type="hidden" name="mvectOperator" id="mvectOperator" value="pending">
            <input type="hidden" name="petcOr" id="petcOr" value="0">
            <input type="hidden" name="cecNumber" id="cecNumber" value="0">
            <input type="hidden" name="paymentDate" id="paymentDate" class="form-control">
            <input type="hidden" name="appDate" id="appDate" class="form-control" >
            <input type="hidden" name="dateTested" id="dateTested" class="form-control" >
            <input type="hidden" name="ticketing_id">
            <input type="hidden" name="reference_id">
            <input type="hidden" name="status" value="booked">
            <input type="hidden" name="payment_status" value="unpaid">
            <button type="submit" class="btn btn-primary">Submit Booking</button>
        </form>
    </div>

<!-- Add this script after the Fuel Type and Purpose script -->

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Simulated data (replace it with your actual data fetching logic) 
        var fuelTypes = ['Gasoline', 'LPG', 'Diesel - None Turbo', 'Turbo Diesel'];
        var purposes = ['For Registration', 'For Compliance', 'Plate Redemption']; 
        var mvTypes = ['Car', 'Mopeds (0-49 cc)', 'Motorcycle w/ side car', 'Motorcycle w/o side car', 'Non-conventional MC (Car)', 'Shuttle Bus', 'Sports utility Vehicle', 'Tourist Bus', 'Tricycle', 'Truck Bus', 'Trucks', 'Utility Vehicle', 'School bus', 'Rebuilt', 'Mobil Clinic', 'Trailer'];     
        var regions = ['Region I', 'Region II', 'Region III', 'Region IV‑A', 'MIMAROPA', 'Region V', 'Region VI', 'Region VII', 'Region VIII', 'Region IX', 'Region X', 'Region XI', 'Region XII', 'Region XIII', 'NCR', 'CAR', 'BARMM'];

        var makes = ['Toyota', 'Honda', 'Mitsubishi', 'Ford', 'Chevrolet', 'Kia', 'Hyundai', 'MG', 'Geely', 'Chery'];
        var series = ['Sedan', 'SUV', 'Truck', 'Hatchback'];
        var colors = ['Red', 'Blue', 'Green', 'Black'];
        var classifications = ['Diplomatic-Consular Corps', 'Diplomatic-Chief of Mission', 'Diplomatic-Diplomatic Corps', 'Exempt-Government', 'Diplomatic Exempt-Economics Z', 'Government', 'For hire', 'Diplomatic-OEV', 'Private', 'Exempt-For-Hire', 'Exempt-Private'];
     
        // Get the select elements
        var makeSelect = document.getElementById('make');
        var seriesSelect = document.getElementById('series');
        var colorSelect = document.getElementById('color');
        var classificationSelect = document.getElementById('classification');
        var fuelTypeSelect = document.getElementById('fuelType');
        var purposeSelect = document.getElementById('purpose');
        var mvTypeSelect = document.getElementById('mvType');
        var regionSelect = document.getElementById('region');
        
        
        const paymentButtons = document.querySelectorAll('.payment-method');
        const paymentMethodInput = document.getElementById('paymentMethod');

  
        var bookingForm = document.querySelector('form');

       
    // Add a submit event listener to the form
    bookingForm.addEventListener('submit', function (event) {
    // Get the appDate input element
    var appDateInput = document.getElementById('appDate');
    // Get the paymentDate input element
    var paymentDateInput = document.getElementById('paymentDate');

    var dateTestedInput = document.getElementById('dateTested');
    // Set the current date and time in the Philippines timezone (Asia/Manila)
    var currentDate = new Date();
    var offset = 8 * 60; // Philippines timezone offset (in minutes)
    
    // Adjust the time to the correct timezone
    currentDate.setMinutes(currentDate.getMinutes() + offset);

    // Format as "YYYY-MM-DDTHH:mm"
    var currentDateString = currentDate.toISOString().slice(0, 16);
    
    // Set the appDateInput value
    appDateInput.value = currentDateString;
    
    // Set the value of paymentDate to zero
    paymentDateInput.value = "0000-00-00T00:00";
    
    // Additionally, set the dateTested to the same value as appDate
    dateTestedInput.value = "0000-00-00T00:00";
});




        paymentButtons.forEach(button => {
            button.addEventListener('click', function () {
                const selectedValue = this.getAttribute('data-value');
                paymentMethodInput.value = selectedValue;

                // Optionally, you can add styles to highlight the selected button
                paymentButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Populate options dynamically
        fuelTypes.forEach(function (fuelType) {
            var option = document.createElement('option');
            option.value = fuelType; //.toLowerCase() You can set the value based on your requirements
            option.text = fuelType;
            fuelTypeSelect.add(option);
        });

        // Populate options dynamically
        purposes.forEach(function (purpose) {
            var option = document.createElement('option');
            option.value = purpose; //.toLowerCase() You can set the value based on your requirements
            option.text = purpose;
            purposeSelect.add(option);
        });


        // Populate options dynamically
        mvTypes.forEach(function (mvType) {
            var option = document.createElement('option');
            option.value = mvType; //.toLowerCase() You can set the value based on your requirements
            option.text = mvType;
            mvTypeSelect.add(option);
        });

        // Populate options dynamically
        regions.forEach(function (region) {
            var option = document.createElement('option');
            option.value = region; //.toLowerCase() You can set the value based on your requirements
            option.text = region;
            regionSelect.add(option);
        });    
        
        // Populate options dynamically
        makes.forEach(function (make) {
            var option = document.createElement('option');
            option.value = make; //.toLowerCase()
            option.text = make;
            makeSelect.add(option);
        });

        series.forEach(function (serie) {
            var option = document.createElement('option');
            option.value = serie; //.toLowerCase()
            option.text = serie;
            seriesSelect.add(option);
        });

        colors.forEach(function (color) {
            var option = document.createElement('option');
            option.value = color; //.toLowerCase()
            option.text = color;
            colorSelect.add(option);
        });

        classifications.forEach(function (classification) {
            var option = document.createElement('option');
            option.value = classification; //.toLowerCase()
            option.text = classification;
            classificationSelect.add(option);
        });

        function updateAmount() {
            var mvTypeSelect = document.getElementById('mvType');
            var amountInput = document.getElementById('amount');
            var price1 = <?php echo $event['price_1']; ?>;
            var price2 = <?php echo $event['price_2']; ?>;
            var price3 = <?php echo $event['price_3']; ?>;

            // Get the selected mvType
            var selectedMvType = mvTypeSelect.value;

            // Update the amount based on mvType
            if (selectedMvType === 'Mopeds (0-49 cc)' || selectedMvType === 'Motorcycle w/ side car' || selectedMvType === 'Motorcycle w/o side car' || selectedMvType === 'Non-conventional MC (Car)' || selectedMvType === 'Tricycle') {
                amountInput.value = price1;
            } else if (selectedMvType === 'Car' || selectedMvType === 'Sports utility Vehicle' || selectedMvType === 'Utility Vehicle' ||  selectedMvType === 'Rebuilt' || selectedMvType === 'Mobil Clinic') {
                amountInput.value = price2;
            } else if (selectedMvType === 'School bus' || selectedMvType === 'Shuttle Bus' || selectedMvType === 'Tourist Bus' || selectedMvType === 'Truck Bus' || selectedMvType === 'Trucks' || selectedMvType === 'Trailer') {
                amountInput.value = price3;
            } else {
                // Handle other cases or set a default value
                amountInput.value = 0;
            }
        }

        // Add an event listener to the mvType select element
        var mvTypeSelect = document.getElementById('mvType');
        mvTypeSelect.addEventListener('change', updateAmount);

        // Call the function initially to set the default amount
        updateAmount();
    });
</script>

    <!-- Include your scripts and other body elements here -->
</body>

</html>
