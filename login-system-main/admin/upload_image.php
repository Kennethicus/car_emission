<?php
session_start();
include("../connect/connection.php");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the bookingId from the form data
    $bookingId = $_POST['bookingId'];

    // Check if a file is uploaded
    if (isset($_FILES["imageUpload"])) {
        // Define the target directory for storing uploaded images
        $targetDir = "assets/img/test_img/";

        // Create the target directory if it doesn't exist
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Get the uploaded image file
        $imageName = basename($_FILES["imageUpload"]["name"]);
        $targetFilePath = $targetDir . $imageName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Check if the file is an actual image
        $check = getimagesize($_FILES["imageUpload"]["tmp_name"]);
        if ($check !== false) {
            // Allow certain image file formats (you can customize this based on your needs)
            $allowedExtensions = ["jpg", "jpeg", "png", "gif"];
            if (in_array($fileType, $allowedExtensions)) {
                // Upload the image file
                if (move_uploaded_file($_FILES["imageUpload"]["tmp_name"], $targetFilePath)) {
                    // Update the database with the image file information
                    $updateQuery = "UPDATE test_result SET  vehicle_img = '$targetFilePath',
                    Uploaded_image = 1,
                    Uploaded = 1
                    -- Valid = 1,
                    -- record_status = 1
                    WHERE booking_id = '$bookingId'";
                    $connect->query($updateQuery);

                    // Fetch the updated values from the database
                    $fetchQuery = "SELECT Uploaded_image, Uploaded FROM test_result WHERE booking_id = '$bookingId'";
                    $result = $connect->query($fetchQuery);
                    $row = $result->fetch_assoc();

                    // Return success, the image path, and updated values as JSON
                    echo json_encode(['success' => true, 'imagePath' => $targetFilePath, 'values' => $row]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Sorry, there was an error uploading your file.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Sorry, only JPG, JPEG, PNG, and GIF files are allowed.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'File is not an image.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No file uploaded.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
