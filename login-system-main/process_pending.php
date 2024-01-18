<!-- process_pending.php -->
<?php
session_start();
// Include database connection code (modify as needed)
include("connect/connection.php");

// Function to generate a unique numeric ticketing ID
function generateTicketingId() {
    // Generate a random 7-digit number
    $ticketingId = rand(1000000, 9999999);
    
    // Check if the generated ID already exists in the database
    // If it does, recursively call the function until a unique ID is generated
    // Note: You may need to modify this part based on your database structure
    if (checkTicketingIdExists($ticketingId)) {
        return generateTicketingId();
    }

    return $ticketingId;
}

// Function to check if a ticketing ID already exists in the database
function checkTicketingIdExists($ticketingId) {
    global $connect;
    $stmt = $connect->prepare("SELECT ticketing_id FROM car_emission WHERE ticketing_id = ?");
    $stmt->bind_param("s", $ticketingId);
    $stmt->execute();
    $stmt->store_result();
    $numRows = $stmt->num_rows;
    $stmt->close();
    return $numRows > 0;
}




function generateReferenceId() {
    // Generate a random 7-digit number
    $referenceId = rand(10000, 99999);
    
    // Check if the generated ID already exists in the database
    // If it does, recursively call the function until a unique ID is generated
    // Note: You may need to modify this part based on your database structure
    if (checkReferenceIdExists($referenceId)) {
        return generateReferenceId();
    }

    return $referenceId;
}

// Function to check if a reference ID already exists in the database
function checkReferenceIdExists($referenceId) {
    global $connect;
    $stmt = $connect->prepare("SELECT reference_number FROM car_emission WHERE reference_number = ?");
    $stmt->bind_param("s", $referenceId);
    $stmt->execute();
    $stmt->store_result();
    $numRows = $stmt->num_rows;
    $stmt->close();
    return $numRows > 0;
}


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form data
    $eventId = $_POST['eventId'];
    $userId = $_POST['userId'];
    $plateNumber = $_POST['plateNumber'];
    $customerEmail = $_POST['customerEmail'];
    $customerFirstName = $_POST['customerFirstName'];
    $customerMiddleName = $_POST['customerMiddleName'];
    $customerLastName = $_POST['customerLastName'];
    $customerAddress = $_POST['customerAddress'];
    $status = $_POST['status'];
   

    // Add the new form field

    $appDate = $_POST['appDate']; 
    $vehicleCrNo = $_POST['vehicleCrNo']; 
    $vehicleOrNo = $_POST['vehicleOrNo']; 
    $firstRegDate = $_POST['firstRegDate']; 
    $yearModel = $_POST['yearModel'];
    $fuelType = $_POST['fuelType'];
    $purpose = $_POST['purpose'];
    $mvType = $_POST['mvType'];
    $region = $_POST['region'];
    $mvFileNo = $_POST['mvFileNo'];
    $classification = $_POST['classification'];
    $paymentDate = $_POST['paymentDate'];
    $petcOr = $_POST['petcOr'];
    $amount = $_POST['amount'];
    $organization = $_POST['organization'];
    $engine = $_POST['engine'];
    $chassis = $_POST['chassis'];
    $make = $_POST['make'];
    $series = $_POST['series'];
    $color = $_POST['color'];
    $grossWeight = $_POST['grossWeight'];
    $netCapacity = $_POST['netCapacity'];
    $cecNumber = $_POST['cecNumber'];
    $mvectOperator = $_POST['mvectOperator'];
    $dateTested = $_POST['dateTested'];

    // Add payment method field
    $paymentMethod = $_POST['paymentMethod'];
    $paymentStatus = $_POST['payment_status'];
    
    $ticketingId = generateTicketingId();
    $referenceId = generateReferenceId();

    // Check if a file has been uploaded
    if (isset($_FILES["carPicture"]) && $_FILES["carPicture"]["error"] == UPLOAD_ERR_OK) {
        // File upload handling
        $targetDirectory = "uploads/";
        $carPicture = $targetDirectory . basename($_FILES["carPicture"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($carPicture, PATHINFO_EXTENSION));

        // Check if the file is an actual image
        $check = getimagesize($_FILES["carPicture"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($carPicture)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["carPicture"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["carPicture"]["tmp_name"], $carPicture)) {
                echo "The file " . htmlspecialchars(basename($_FILES["carPicture"]["name"])) . " has been uploaded.";

                // Insert car emission details into the car_emission table
                $stmt = $connect->prepare("INSERT INTO car_emission (event_id, user_id, plate_number, customer_email, customer_first_name, customer_middle_name, customer_last_name, address, status, car_picture, app_date, vehicle_cr_no, vehicle_or_no, first_reg_date, year_model, fuel_type, purpose, mv_type, region, mv_file_no, classification, payment_date, petc_or, amount, organization, engine, chassis, make, series, color, gross_weight, net_capacity, cec_number, mvect_operator, paymentMethod, paymentStatus, ticketing_id, reference_number, date_tested) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iissssssssssssssssssssdssssssssssssssss", $eventId ,$userId ,$plateNumber, $customerEmail, $customerFirstName, $customerMiddleName, $customerLastName, $customerAddress, $status, $carPicture, $appDate, $vehicleCrNo, $vehicleOrNo, $firstRegDate, $yearModel, $fuelType, $purpose, $mvType, $region, $mvFileNo, $classification, $paymentDate, $petcOr, $amount, $organization, $engine, $chassis,$make, $series, $color, $grossWeight, $netCapacity, $cecNumber, $mvectOperator, $paymentMethod, $paymentStatus, $ticketingId, $referenceId, $dateTested);

                if ($stmt->execute()) {
                    // If successful, update the reserve_count for the booked event
                    $updateCountQuery = $connect->prepare("UPDATE schedule_list SET reserve_count = reserve_count + 1 WHERE id = ?");
                    $updateCountQuery->bind_param("i", $eventId);
                    $updateCountQuery->execute();
                    $updateCountQuery->close();

                    // Redirect to a success page or perform other actions
                    header('Location: schedule-status.php');
                    exit();
                } else {
                    // If there's an error, handle it accordingly
                    echo "Error: " . $stmt->error;
                }

                // Close the statement
                $stmt->close();
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        // Handle the case where no file is uploaded
        echo "No file uploaded.";
    }
}

// Close the database connection
$connect->close();
?>