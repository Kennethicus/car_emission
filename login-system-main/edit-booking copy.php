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
} else {
    // If 'id' parameter is not set, redirect to the schedule status page
    header('Location: schedule-status.php');
    exit();
}

// Handle form submission for editing the booking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve updated values from the form
    // (you should perform proper validation and sanitation)
    
    $updatedPlateNum = $_POST['plate_num'];
    $updatedCrNo = $_POST['vehicleCrNo'];
    $updatedOrNo = $_POST['vehicleOrNo'];
    $updatedFirstRegDate = $_POST["firstRegDate"];
    $updatedYearModel = $_POST["yearModel"];
    $updatedfuelType = $_POST["fuelType"];
    $updatedPurpose = $_POST["purpose"];
    $updatedmvType = $_POST["mvType"];
    $updatedregionType = $_POST["region"];
    $updatedmvfileNo = $_POST["mvFileNo"];
    $updatedclassification = $_POST["classification"];
    $updatedengine = $_POST["engine"];
    $updatedchassis = $_POST["chassis"];
    $updatedmake = $_POST["make"];
    $updatedseries = $_POST["series"];
    $updatedcolor = $_POST["color"];
    $updatedgrossWeight = $_POST["grossWeight"];
    $updatednetCapacity = $_POST["netCapacity"];
    
    // Check if a new picture is uploaded
    if (!empty($_FILES['car_picture']['name'])) {
        $targetDirectory = "uploads/";  // Change this to your desired directory
        $targetFile = $targetDirectory . basename($_FILES['car_picture']['name']);

        if (move_uploaded_file($_FILES['car_picture']['tmp_name'], $targetFile)) {
            // File upload successful
            $updatedCarPic = $targetFile;
        } else {
            // File upload failed
            $updateError = "Failed to upload car picture.";
        }
    }

    // Update the booking details in the database
    $updateQuery = $connect->prepare("UPDATE car_emission SET plate_number = ?, car_picture = ?, vehicle_cr_no = ?, vehicle_or_no = ?, first_reg_date = ?, year_model = ?, fuel_type = ?, purpose = ?, mv_type = ?, region = ?, mv_file_no = ?, classification = ?, engine = ?, chassis = ?, make = ?, series = ?, color = ?, gross_weight = ?, net_capacity = ? WHERE id = ?");
    if (!$updateQuery) {
        die("Error in update query: " . $connect->error);
    }

    $carPicture = isset($updatedCarPic) ? $updatedCarPic : $bookingDetails['car_picture'];

    // If a new picture is uploaded, use $updatedCarPic; otherwise, use the existing value from the database
    $updateQuery->bind_param("sssssssssssssssssssi", $updatedPlateNum, $carPicture, $updatedCrNo, $updatedOrNo, $updatedFirstRegDate, $updatedYearModel, $updatedfuelType, $updatedPurpose, $updatedmvType, $updatedregionType, $updatedmvfileNo, $updatedclassification, $updatedengine, $updatedchassis, $updatedmake, $updatedseries, $updatedcolor, $updatedgrossWeight, $updatednetCapacity, $bookingId);
    $updateQuery->execute();

    if ($updateQuery->affected_rows > 0) {
        // Update successful
        // Redirect to the view booking page or show a success message
        header('Location: view-booking.php?id=' . $bookingId);
        exit();
    } else {
        // Update failed
        // Handle the error (show an error message or redirect to an error page)
        $updateError = "Failed to update booking details.";
    }

    // Close the update query statement
    $updateQuery->close();
}
?>

<!-- Rest of the HTML/PHP code for your page -->



<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Booking</title>
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

        /* Add your custom styles here */
    </style>
</head>

<body class="bg-light">
    <div class="container py-5">
        <h2>Edit Booking for <?php echo $customerDetails['first_name'] . ' ' . $customerDetails['last_name']; ?></h2>

        <!-- Display update error message if any -->
        <?php if (isset($updateError)) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $updateError; ?>
            </div>
        <?php endif; ?>
        <div>
  

    <!-- Display price details -->
    <p>Price 1: <?php echo $bookingDetails['price_1']; ?></p>
    <p>Price 2: <?php echo $bookingDetails['price_2']; ?></p>
    <p>Price 3: <?php echo $bookingDetails['price_3']; ?></p>
</div>
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="plate_num"> Plate Number </label>
                        <input type="text" name="plate_num" id="plate_num" class="form-control" value="<?php echo $bookingDetails['plate_number']; ?>" >
                    </div>

                    <div class="form-group">
                        <label for="car_picture">Car Picture</label>
                        <input type="file" name="car_picture" id="car_picture" class="form-control-file" accept="image/*" >
                    </div>

                    <div class="form-group">
                        <label for="vehicleCrNo">Vehicle CR No.</label>
                        <input type="text" name="vehicleCrNo" id="vehicleCrNo" class="form-control"  value="<?php echo $bookingDetails['vehicle_cr_no']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="vehicleOrNo">Vehicle OR No.</label>
                        <input type="text" name="vehicleOrNo" id="vehicleOrNo" class="form-control"  value="<?php echo $bookingDetails['vehicle_or_no']; ?>">
                    </div>

                    <div class="form-group">
                    <label for="firstRegDate">First Registration Date</label>
                    <input type="date" name="firstRegDate" id="firstRegDate" class="form-control" value="<?php echo $bookingDetails['first_reg_date']; ?>">
                    </div>

                    
                    <div class="form-group">
                    <label for="yearModel">Year Model</label>
                    <select name="yearModel" id="yearModel" class="form-control">
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
                    </div>


                    <div class="form-group">
                    <label for="fuelType">Fuel Type</label>
                    <select name="fuelType" id="fuelType" class="form-control">
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
                    </div>

                    <div class="form-group">
                    <label for="purpose">Purpose</label>
                    <select name="purpose" id="purpose" class="form-control">
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
                    </div>


                    <div class="form-group">
                    <label for="mvType">MV Type</label>
                    <select name="mvType" id="mvType" class="form-control">
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
                    </div>

                    <div class="form-group">
                    <label for="region">Region</label>
                    <select name="region" id="region" class="form-control">
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
                    </div>


                    <div class="form-group">
                    <label for="mvFileNo">MV File No.</label>
                    <input type="text" name="mvFileNo" id="mvFileNo" class="form-control" value="<?php echo $bookingDetails['mv_file_no']; ?>">
                    </div>


                    <div class="form-group">
                    <label for="classification">Classification</label>
                    <select name="classification" id="classification" class="form-control">
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
                    </div>

                    <div class="form-group">
                    <label for="amount">Amount</label>
                    <div class="input-group-prepend">
                    <span class="input-group-text">₱</span>
                    </div>
                    <input type="text" name="amount" id="amount" class="form-control" value="<?php echo $bookingDetails['amount']; ?>" readonly>
                    </div>
                    <!-- Add more fields for editing as needed -->

                    
                </div>

            <!-- Second Column -->
        <div class="col-md-6">

                <div class="form-group">
                    <label for="organization">Organization </label>
                    <input type="text" name="organization" id="organization" class="form-control" value="<?php echo $bookingDetails['organization']; ?>">
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
                    <input type="text" name="engine" id="engine" class="form-control" value="<?php echo $bookingDetails['engine']; ?>" >
                    </div>
           
                    <div class="form-group">
                    <label for="chassis">Chassis</label>
                    <input type="text" name="chassis" id="chassis" class="form-control" value="<?php echo $bookingDetails['chassis']; ?>">
                    </div>

                    <div class="form-group">
                    <label for="make">Make</label>
                    <select name="make" id="make" class="form-control">
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
                    </div>


                    <div class="form-group">
                    <label for="series">Series</label>
                    <select name="series" id="series" class="form-control">
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
                    </div>

                    <div class="form-group">
                    <label for="color">Color</label>
                    <select name="color" id="color" class="form-control">
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
                    </div>

                    <div class="form-group">
                    <label for="grossWeight">Gross Weight</label>
                    <input type="text" name="grossWeight" id="grossWeight" class="form-control" value="<?php echo $bookingDetails['gross_weight']; ?>">
                    </div>

                    <div class="form-group">
                    <label for="netCapacity">Net Capacity</label>
                    <input type="text" name="netCapacity" id="netCapacity" class="form-control" value="<?php echo $bookingDetails['net_capacity']; ?>">
                    </div>


                    <div class="col-md-13">
    <!-- ... (additional form fields) ... -->
    <div class="d-flex justify-content-end mt-md-5 mt-3">
        <button type="submit" class="btn btn-primary mr-2">Save Changes</button>
        <a href="view-booking.php?id=<?php echo $bookingDetails['id']; ?>" class="btn btn-secondary">Cancel</a>
    </div>
</div>
                  
                </div>
            </div> 
        </form>
    </div>

    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>
