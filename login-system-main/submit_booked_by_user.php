<?php
session_start();
include("connect/connection.php");

// Validate input data (you may want to perform more validation based on your requirements)
$user_id = isset($_POST['userId']) ? mysqli_real_escape_string($connect, $_POST['userId']) : '';
$event_id = isset($_POST['schedId']) ? mysqli_real_escape_string($connect, $_POST['schedId']) : '';
$first_name = isset($_POST['firstName']) ? mysqli_real_escape_string($connect, $_POST['firstName']) : '';
$middle_name = isset($_POST['middleName']) ? mysqli_real_escape_string($connect, $_POST['middleName']) : '';
$last_name = isset($_POST['lastName']) ? mysqli_real_escape_string($connect, $_POST['lastName']) : '';
$address = isset($_POST['address']) ? mysqli_real_escape_string($connect, $_POST['address']) : '';

// Check if the plate number has already been booked
$plateNumberLetters = isset($_POST['plateNumberLetters']) ? mysqli_real_escape_string($connect, $_POST['plateNumberLetters']) : '';
$plateNumberNumbers = isset($_POST['plateNumberNumbers']) ? mysqli_real_escape_string($connect, $_POST['plateNumberNumbers']) : '';
$plate_number = $plateNumberLetters . ' ' . $plateNumberNumbers;

if (isPlateNumberBooked($plate_number)) {
    $response['status'] = 'error';
    $response['message'] = 'This plate number has already been booked.';
    echo json_encode($response);
    exit();
}


$result = isPlateNumberTestedWithinYear($plate_number);

if ($result['status']) {
    $response['status'] = 'error';
    $response['message'] = 'This plate number has already been tested within the last 12 months. Please come back after 1 year. You can come back after ' . $result['comeBackDate'];
    echo json_encode($response);
    exit();
}

// Continue with other input data
$status = 'booked';
date_default_timezone_set('Asia/Manila');
$appdate = date('Y-m-d H:i:s');


if (!isAvailableSpaceInSchedule($event_id)) {
    $response['status'] = 'error';
    $response['message'] = 'There\'s someone booked first. No available space in the schedule.';
    echo json_encode($response);
    exit();
}


$vehicleCrNo = isset($_POST['vehicleCrNo']) ? mysqli_real_escape_string($connect, $_POST['vehicleCrNo']) : '';
$vehicleOrNo = isset($_POST['vehicleOrNo']) ? mysqli_real_escape_string($connect, $_POST['vehicleOrNo']) : '';
$firstReg = isset($_POST['firstReg']) ? mysqli_real_escape_string($connect, $_POST['firstReg']) : '';
$yearModel = isset($_POST['yearModel']) ? mysqli_real_escape_string($connect, $_POST['yearModel']) : '';
$fuelType = isset($_POST['fuelType']) ? mysqli_real_escape_string($connect, $_POST['fuelType']) : '';
$purpose = isset($_POST['purpose']) ? mysqli_real_escape_string($connect, $_POST['purpose']) : '';
$mvType = isset($_POST['mvType']) ? mysqli_real_escape_string($connect, $_POST['mvType']) : '';
$region = isset($_POST['region']) ? mysqli_real_escape_string($connect, $_POST['region']) : '';
$mvFileNo = isset($_POST['mvFile']) ? mysqli_real_escape_string($connect, $_POST['mvFile']) : '';
$classification = isset($_POST['classification']) ? mysqli_real_escape_string($connect, $_POST['classification']) : '';

$paymentDate = "0000-00-00T00:00";
$petc_or = 0;

$amount = isset($_POST['amount']) ? mysqli_real_escape_string($connect, $_POST['amount']) : '';
$organization = isset($_POST['organization']) ? mysqli_real_escape_string($connect, $_POST['organization']) : '';
$engine = isset($_POST['engine']) ? mysqli_real_escape_string($connect, $_POST['engine']) : '';
$chassis = isset($_POST['chassis']) ? mysqli_real_escape_string($connect, $_POST['chassis']) : '';
$make = isset($_POST['make']) ? mysqli_real_escape_string($connect, $_POST['make']) : '';
$series = isset($_POST['series']) ? mysqli_real_escape_string($connect, $_POST['series']) : '';
$color = isset($_POST['color']) ? mysqli_real_escape_string($connect, $_POST['color']) : '';
$grossWeight = isset($_POST['grossWeight']) ? mysqli_real_escape_string($connect, $_POST['grossWeight']) : '';
$netCapacity = isset($_POST['netCapacity']) ? mysqli_real_escape_string($connect, $_POST['netCapacity']) : '';
$cecNumber = 0;
$mvectOperator = 'pending';
$paymentMethod = 'pending';
$paymentStatus = 'unpaid';
$amountPaid = '0';
$reference1 = '0';
// $paymentStatus = isset($_POST['paymentStatus']) ? mysqli_real_escape_string($connect, $_POST['paymentStatus']) : '';
// // Check payment method and update payment status accordingly
// if ($paymentMethod === 'cash') {
//     $paymentStatus = 'unpaid';
// } elseif ($paymentMethod === 'paymaya') {
//     $paymentStatus = 'pending';
// }

$ticketingId = generateTicketingId();
$referenceNumber = generateReferenceNumber();
$dateTested = "0000-00-00T00:00";

// Check if a file is selected
if (isset($_FILES['carPicture']) && $_FILES['carPicture']['error'] == UPLOAD_ERR_OK) {
    // Define the directory to store the uploaded car pictures
    $uploadDir = 'uploads/car_picture/';

    // Generate a unique filename
    $uploadFile = $uploadDir . uniqid() . '_' . basename($_FILES['carPicture']['name']);

    // Move the uploaded file to the destination directory
    if (move_uploaded_file($_FILES['carPicture']['tmp_name'], $uploadFile)) {
        // File upload success
        $carPicturePath = $uploadFile;
    } else {
        // File upload failed
        $carPicturePath = '';
    }
} else {
    // No file selected or an error occurred
    $carPicturePath = '';
}



// Perform the database insert with prepared statement and bind_param
$query = "INSERT INTO car_emission (event_id, user_id, plate_number, customer_first_name, customer_middle_name, customer_last_name, address, status, app_date, vehicle_cr_no, vehicle_or_no, first_reg_date, year_model, fuel_type, purpose, mv_type, region, mv_file_no, classification, payment_date, petc_or, amount, organization, engine, chassis, make, series, color, gross_weight, net_capacity, cec_number, mvect_operator, car_picture, paymentMethod, paymentStatus, ticketing_id, reference_number, date_tested) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $connect->prepare($query);

// Bind parameters
$stmt->bind_param("iisssssssssssssssssssdssssssssssssssss", $event_id, $user_id, $plate_number, $first_name, $middle_name, $last_name, $address, $status, $appdate, $vehicleCrNo, $vehicleOrNo, $firstReg, $yearModel, $fuelType, $purpose, $mvType, $region, $mvFileNo, $classification, $paymentDate, $petc_or, $amount, $organization, $engine, $chassis, $make, $series, $color, $grossWeight, $netCapacity, $cecNumber, $mvectOperator, $carPicturePath, $paymentMethod, $paymentStatus, $ticketingId, $referenceNumber, $dateTested);

// Execute the statement
$stmt->execute();

$response = array();

// Check if the query was successful
if ($stmt->affected_rows > 0) {
    // If successful, update the reserve_count for the booked event
    $updateCountQuery = $connect->prepare("UPDATE schedule_list SET reserve_count = reserve_count + 1 WHERE id = ?");
    $updateCountQuery->bind_param("i", $event_id);

    if ($updateCountQuery->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'Data inserted successfully';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error updating reserve count';
    }

    $updateCountQuery->close();
} else {
    $response['status'] = 'error';
    $response['message'] = 'Error inserting data into the database';
}

// Output the response as JSON
echo json_encode($response);

// Close the statement
$stmt->close();

// Close the database connection if needed
mysqli_close($connect);

// Function to generate a unique ticketing ID
function generateTicketingId() {
    // Generate a random 7-digit number
    $ticketingId = rand(1000000, 9999999);

    // Check if the generated ID already exists in the database
    global $connect;
    $stmt = $connect->prepare("SELECT ticketing_id FROM car_emission WHERE ticketing_id = ?");
    $stmt->bind_param("s", $ticketingId);
    $stmt->execute();
    $stmt->store_result();
    $numRows = $stmt->num_rows;
    $stmt->close();

    // If the ID already exists, generate a new one
    if ($numRows > 0) {
        return generateTicketingId();
    }

    return $ticketingId;
}

// Function to generate a unique reference number
function generateReferenceNumber() {
    // Generate a random 5-digit number
    $referenceNumber = rand(10000, 99999);

    // Check if the generated number already exists in the database
    global $connect;
    $stmt = $connect->prepare("SELECT reference_number FROM car_emission WHERE reference_number = ?");
    $stmt->bind_param("s", $referenceNumber);
    $stmt->execute();
    $stmt->store_result();
    $numRows = $stmt->num_rows;
    $stmt->close();

    // If the number already exists, generate a new one
    if ($numRows > 0) {
        return generateReferenceNumber();
    }

    return $referenceNumber;
}

// Function to check if the plate number is already booked
// Function to check if the plate number is already booked
function isPlateNumberBooked($plateNumber) {
    global $connect;
    $stmt = $connect->prepare("SELECT plate_number FROM car_emission WHERE plate_number = ? AND status = 'booked'");
    $stmt->bind_param("s", $plateNumber);
    $stmt->execute();
    $stmt->store_result();
    $numRows = $stmt->num_rows;
    $stmt->close();

    return $numRows > 0;
}


// Function to check if the plate number is tested within the last 12 months
// Function to check if the plate number is tested within the last 12 months
function isPlateNumberTestedWithinYear($plateNumber) {
    global $connect;

    // Get the latest date_tested for the given plate number
    $stmt = $connect->prepare("SELECT date_tested FROM car_emission WHERE plate_number = ? AND status = 'doned' ORDER BY date_tested DESC LIMIT 1");
    $stmt->bind_param("s", $plateNumber);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($latestTestedDate);
        $stmt->fetch();

        // Compare the latest tested date with the current date
        $currentDate = date('Y-m-d H:i:s');
        $latestTestedDateTime = new DateTime($latestTestedDate);
        $currentDateTime = new DateTime($currentDate);
        $difference = $latestTestedDateTime->diff($currentDateTime);

        // Check if the difference is less than 1 year
        if ($difference->y < 1) {
            // Calculate the date when they can come back
            $comeBackDate = $latestTestedDateTime->add(new DateInterval('P1Y'))->format('Y-m-d H:i:s');
            
            $stmt->close();
            return array('status' => true, 'comeBackDate' => $comeBackDate); // Plate number tested within the last 12 months
        }
    }

    $stmt->close();
    return array('status' => false, 'comeBackDate' => null); // Plate number not tested within the last 12 months or no testing records found
}


// Function to check if there is available space in the schedule
// Function to check if there is available space in the schedule
function isAvailableSpaceInSchedule($event_id) {
    global $connect;

    // Get the reserve_count for the given event_id from the schedule_list table
    $stmt = $connect->prepare("SELECT qty_of_person FROM schedule_list WHERE id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($reserveCount);
        $stmt->fetch();

        // Check the number of persons with the "booked" status for the given event_id in the car_emission table
        $stmt->prepare("SELECT COUNT(*) FROM car_emission WHERE event_id = ? AND status = 'booked'");
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        $stmt->bind_result($bookedPersonsCount);
        $stmt->fetch();

        $stmt->close();

        // Compare the booked persons count with the reserve count
        return $bookedPersonsCount < $reserveCount;
    }

    $stmt->close();
    return false; // Event_id not found in schedule_list
}


?>
