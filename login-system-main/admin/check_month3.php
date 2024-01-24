
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate the input (you may need to enhance this depending on your requirements)
    if (isset($_POST['month']) && !empty($_POST['month'])) {
        $selectedMonth = $_POST['month'];

        // Perform your logic to check if the selected month has specific days with slots
        // For this example, let's assume it has slots if it's January
        if (date('m', strtotime($selectedMonth)) == '01') {
            echo 'has_slots';
        } else {
            echo 'no_slots';
        }
    } else {
        // Invalid or missing input
        echo 'error';
    }
} else {
    // Not a POST request
    echo 'error';
}






// Handle Delete Month button
if (isset($_POST['delete_month'])) {
    $monthToDelete = $_POST['month'];

    // Extract year and month from the selected date
    $yearToDelete = date('Y', strtotime($monthToDelete));
    $monthToDelete = date('m', strtotime($monthToDelete));

    // Delete all time slots for the specified month and year
    $sqlDelete = "DELETE FROM schedule_list WHERE YEAR(start_datetime) = $yearToDelete AND MONTH(start_datetime) = $monthToDelete";
    $resultDelete = $connect->query($sqlDelete);

    if ($resultDelete) {
        echo "All time slots for the selected month have been deleted.";
    } else {
        echo "Error deleting time slots: " . $connect->error;
    }
}


?>
